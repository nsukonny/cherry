<?php

/**
 * Page numbers and links for a paginated list.
 */

declare(strict_types=1);

namespace App\View;

final class Pagination
{
    public readonly int $pages;

    /**
     * @param string               $path  page URL without query string, e.g. '/morozostoykie/'
     * @param array<string,string> $query parameters every link must keep, e.g. ['sort' => 'views']
     */
    public function __construct(
        public readonly int $current,
        public readonly int $total,
        public readonly int $perPage,
        private readonly string $path,
        private readonly array $query = [],
    ) {
        $this->pages = max(1, (int) ceil($total / $perPage));
    }

    public function exists(): bool
    {
        return $this->current >= 1 && $this->current <= $this->pages;
    }

    public function offset(): int
    {
        return ($this->current - 1) * $this->perPage;
    }

    public function hasPrevious(): bool
    {
        return $this->current > 1;
    }

    public function hasNext(): bool
    {
        return $this->current < $this->pages;
    }

    /**
     * @return list<int>
     */
    public function numbers(): array
    {
        return range(1, $this->pages);
    }

    /**
     * Link to a page. Page 1 has no ?page=, so the first page has one URL.
     */
    public function url(int $page): string
    {
        $query = $this->query + ($page > 1 ? ['page' => $page] : []);

        return $this->path . ($query === [] ? '' : '?' . http_build_query($query));
    }
}
