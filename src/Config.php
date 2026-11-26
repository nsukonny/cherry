<?php

declare(strict_types=1);

namespace App;

use Dotenv\Dotenv;
use RuntimeException;

final class Config
{
    /**
     * @return array{
     *     name: string,
     *     environment: string,
     *     database: array{
     *         host: string,
     *         port: int,
     *         name: string,
     *         username: string,
     *         password: string
     *     }
     * }
     */
    public static function load(string $root): array
    {
        Dotenv::createImmutable($root)->safeLoad();

        $read = static function (string $key, ?string $default = null): string {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;

            if (!is_string($value)) {
                throw new RuntimeException("Environment variable {$key} is not set (see .env_example)");
            }

            return $value;
        };

        return [
            'name' => $read('APP_NAME', 'Cherry'),
            'environment' => $read('APP_ENV', 'production'),
            'database' => [
                'host' => $read('DB_HOST'),
                'port' => (int)$read('DB_PORT', '3306'),
                'name' => $read('DB_DATABASE'),
                'username' => $read('DB_USERNAME'),
                'password' => $read('DB_PASSWORD'),
            ],
        ];
    }
}
