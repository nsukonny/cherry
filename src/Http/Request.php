<?php
/**
 * Read incoming HTTP request: method, path and query parameters.
 */

declare(strict_types=1);

namespace App\Http;

final class Request
{
    public function __construct(
        public readonly string $method = 'GET',
        public readonly string $path = '/',
        private readonly array $query = [],
        public readonly string $categorySlug = '',
        public readonly string $postSlug = ''
    ) {
    }

    /**
     * Create a Request object from the current HTTP request.
     *
     * @return self
     */
    public static function parseRequest(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path = self::normalizePath(is_string($path) ? $path : '/');

        $explodedPath = explode('/', trim($path, '/'));

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            $path,
            $_GET,
            $explodedPath[0] ?? '',
            $explodedPath[1] ?? ''
        );
    }

    /**
     * Query string parameter (?sort=views). Arrays (?sort[]=x) are ignored.
     */
    public function query(string $key): ?string
    {
        $value = $this->query[$key] ?? null;

        return is_string($value) ? $value : null;
    }

    private static function normalizePath(string $path): string
    {
        return '/' . trim($path, '/');
    }
}
