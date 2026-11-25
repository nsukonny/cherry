<?php
/**
 * Exception for HTTP errors
 */

declare(strict_types=1);

namespace App\Http;

use RuntimeException;

final class HttpException extends RuntimeException
{
    private function __construct(
        int $status,
        string $message,
        public readonly array $headers = [],
    ) {
        parent::__construct($message, $status);
    }

    public static function notFound(): self
    {
        return new self(404, 'Страница не найдена');
    }

    public static function methodNotAllowed(array $allowed): self
    {
        return new self(405, 'Метод не поддерживается', [
            'Allow' => implode(', ', array_unique($allowed)),
        ]);
    }

    public function status(): int
    {
        return $this->getCode();
    }
}
