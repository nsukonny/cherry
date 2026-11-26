<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use RuntimeException;
use Throwable;

/**
 * Заливает категории и статьи из database/seed-data.php.
 *
 * Запуск повторяемый: запись ищется по slug и обновляется, если уже есть,
 * иначе вставляется. Связи статьи с категориями пересобираются целиком.
 * Всё выполняется в одной транзакции — при ошибке база остаётся как была.
 */
final class Seeder
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @param array{
     *     categories: list<array{slug: string, title: string, description: string}>,
     *     posts: list<array{
     *         slug: string, title: string, description: string, content: string,
     *         published_at: string, views: int, categories: list<string>, image?: string|null
     *     }>
     * } $data
     *
     * @return array{categories: int, posts: int, links: int}
     */
    public function run(array $data): array
    {
        $this->pdo->beginTransaction();

        try {
            $categoryIds = [];
            foreach ($data['categories'] as $category) {
                $categoryIds[$category['slug']] = $this->saveCategory($category);
            }

            $links = 0;
            foreach ($data['posts'] as $post) {
                $postId = $this->savePost($post);
                $links += $this->syncCategories($postId, $post['categories'], $categoryIds);
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return [
            'categories' => count($categoryIds),
            'posts' => count($data['posts']),
            'links' => $links,
        ];
    }

    /**
     * @param array{slug: string, title: string, description: string} $category
     */
    private function saveCategory(array $category): int
    {
        $id = $this->findId('categories', $category['slug']);
        $params = [
            'slug' => $category['slug'],
            'title' => $category['title'],
            'description' => $category['description'],
        ];

        if ($id === null) {
            $this->pdo
                ->prepare('INSERT INTO categories (slug, title, description) VALUES (:slug, :title, :description)')
                ->execute($params);

            return (int) $this->pdo->lastInsertId();
        }

        $this->pdo
            ->prepare('UPDATE categories SET title = :title, description = :description WHERE slug = :slug')
            ->execute($params);

        return $id;
    }

    /**
     * @param array{
     *     slug: string, title: string, description: string, content: string,
     *     published_at: string, views: int, image?: string|null
     * } $post
     */
    private function savePost(array $post): int
    {
        $id = $this->findId('posts', $post['slug']);
        $params = [
            'slug' => $post['slug'],
            'title' => $post['title'],
            'description' => $post['description'],
            'content' => $post['content'],
            'image' => $post['image'] ?? null,
            'views' => $post['views'],
            'published_at' => $post['published_at'],
        ];

        if ($id === null) {
            $this->pdo->prepare(
                'INSERT INTO posts (slug, title, description, content, image, views, published_at)
                 VALUES (:slug, :title, :description, :content, :image, :views, :published_at)'
            )->execute($params);

            return (int) $this->pdo->lastInsertId();
        }

        $this->pdo->prepare(
            'UPDATE posts
             SET title = :title, description = :description, content = :content,
                 image = :image, views = :views, published_at = :published_at
             WHERE slug = :slug'
        )->execute($params);

        return $id;
    }

    /**
     * @param list<string> $slugs
     * @param array<string, int> $categoryIds
     */
    private function syncCategories(int $postId, array $slugs, array $categoryIds): int
    {
        $this->pdo
            ->prepare('DELETE FROM posts_categories WHERE post_id = :post_id')
            ->execute(['post_id' => $postId]);

        $insert = $this->pdo->prepare(
            'INSERT INTO posts_categories (post_id, category_id) VALUES (:post_id, :category_id)'
        );

        foreach ($slugs as $slug) {
            $categoryId = $categoryIds[$slug]
                ?? throw new RuntimeException("Unknown category slug '{$slug}' in post #{$postId}");

            $insert->execute(['post_id' => $postId, 'category_id' => $categoryId]);
        }

        return count($slugs);
    }

    /**
     * Имя таблицы приходит только из этого класса, не из внешних данных.
     *
     * @param 'categories'|'posts' $table
     */
    private function findId(string $table, string $slug): ?int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM {$table} WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $id = $stmt->fetchColumn();

        return $id === false ? null : (int) $id;
    }
}
