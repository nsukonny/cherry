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

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            self::normalizePath(is_string($path) ? $path : '/'),
            $_GET,
        );
    }

    private static function normalizePath(string $path): string
    {
        return '/' . trim($path, '/');
    }
}
