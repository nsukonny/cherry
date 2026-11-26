<?php
/**
 * Read incoming HTTP request: method, path and query parameters.
 */

declare(strict_types=1);

namespace App\Http;

final readonly class Request
{

    public function __construct(
        public string $method = 'GET',
        public string $path = '/',
        private array $query = [],
        public string $categorySlug = '',
        public string $postSlug = ''
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

    private static function normalizePath(string $path): string
    {
        return '/' . trim($path, '/');
    }
}
