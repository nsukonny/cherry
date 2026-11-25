<?php

/**
 * SQL queries for categories.
 */

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class CategoryRepository
{
    private const COLUMNS = 'c.ID AS id, c.slug, c.title, c.description';

    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * All categories in the order they were created (header, footer, home page).
     *
     * @return list<array{id: int, slug: string, title: ?string, description: ?string}>
     */
    public function findAll(): array
    {
        return $this->pdo
            ->query('SELECT ' . self::COLUMNS . ' FROM categories c ORDER BY c.ID')
            ->fetchAll();
    }

    /**
     * @return array{id: int, slug: string, title: ?string, description: ?string}|null
     */
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNS . ' FROM categories c WHERE c.slug = :slug LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);

        return $stmt->fetch() ?: null;
    }
}
