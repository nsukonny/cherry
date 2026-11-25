<?php

/**
 * Collector for all repositories
 */

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class Repositories
{
    private ?CategoryRepository $categories = null;
    private ?PostRepository $posts = null;

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function categories(): CategoryRepository
    {
        return $this->categories ??= new CategoryRepository($this->pdo);
    }

    public function posts(): PostRepository
    {
        return $this->posts ??= new PostRepository($this->pdo);
    }
}
