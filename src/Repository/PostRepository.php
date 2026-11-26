<?php

/**
 * SQL queries for posts.
 */

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class PostRepository
{
    private const COLUMNS = 'p.id, p.slug, p.title, p.description, p.image, p.views,
                             p.published_at';

    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Latest posts of every category for the home page, in one query.
     *
     * @return array<int, list<array<string, mixed>>> category id => posts
     */
    public function findLatestPerCategory(int $limit): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM (
                 SELECT pc.category_id, ' . self::COLUMNS . ',
                        ROW_NUMBER() OVER (
                            PARTITION BY pc.category_id
                            ORDER BY p.published_at DESC, p.id DESC
                        ) AS row_num
                 FROM posts p
                 JOIN posts_categories pc ON pc.post_id = p.id
             ) ranked
             WHERE row_num <= :limit
             ORDER BY category_id, row_num'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $grouped = [];
        foreach ($this->withCategories($stmt->fetchAll()) as $post) {
            $grouped[$post['category_id']][] = $post;
        }

        return $grouped;
    }

    /**
     * One page of a category.
     *
     * @return list<array<string, mixed>>
     */
    public function findByCategory(int $categoryId, PostSort $sort, int $limit, int $offset): array
    {
        $orderBy = match ($sort) {
            PostSort::Date => 'p.published_at DESC, p.id DESC',
            PostSort::Views => 'p.views DESC, p.id DESC',
        };

        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNS . '
             FROM posts p
             JOIN posts_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id
             ORDER BY ' . $orderBy . '
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $this->withCategories($stmt->fetchAll());
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM posts_categories WHERE category_id = :category_id'
        );
        $stmt->execute(['category_id' => $categoryId]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * Full post for the article page.
     *
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNS . ', p.content
             FROM posts p
             WHERE p.slug = :slug
             LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        $post = $stmt->fetch();

        return $post === false ? null : $this->withCategories([$post])[0];
    }

    /**
     * Atomic +1: two simultaneous requests won't overwrite each other.
     */
    public function incrementViews(int $postId): void
    {
        $this->pdo
            ->prepare('UPDATE posts SET views = views + 1 WHERE id = :id')
            ->execute(['id' => $postId]);
    }

    /**
     * Posts sharing categories with the given one: more shared categories first,
     * then newer.
     *
     * @return list<array<string, mixed>>
     */
    public function findRelated(int $postId, int $limit): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNS . ', COUNT(*) AS shared_categories
             FROM posts_categories current_pc
             JOIN posts_categories other_pc
                  ON other_pc.category_id = current_pc.category_id
                 AND other_pc.post_id <> current_pc.post_id
             JOIN posts p ON p.id = other_pc.post_id
             WHERE current_pc.post_id = :post_id
             GROUP BY p.id
             ORDER BY shared_categories DESC, p.published_at DESC, p.id DESC
             LIMIT :limit'
        );
        $stmt->bindValue('post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $this->withCategories($stmt->fetchAll());
    }

    /**
     * Adds categories to each post with one extra query instead of one per post (N+1).
     *
     * @param list<array<string, mixed>> $posts
     * @return list<array<string, mixed>>
     */
    private function withCategories(array $posts): array
    {
        if ($posts === []) {
            return [];
        }

        $ids = array_values(array_unique(array_column($posts, 'id')));
        $placeholders = implode(', ', array_fill(0, count($ids), '?'));

        $stmt = $this->pdo->prepare(
            "SELECT pc.post_id, c.slug, c.title
             FROM posts_categories pc
             JOIN categories c ON c.id = pc.category_id
             WHERE pc.post_id IN ({$placeholders})
             ORDER BY c.id"
        );
        $stmt->execute($ids);

        $byPost = [];
        foreach ($stmt->fetchAll() as $row) {
            $byPost[$row['post_id']][] = ['slug' => $row['slug'], 'title' => $row['title']];
        }

        return array_map(
            static fn(array $post): array => $post + ['categories' => $byPost[$post['id']] ?? []],
            $posts,
        );
    }
}
