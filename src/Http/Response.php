<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Answer for the HTTP requests
 */
final class Response
{
    /**
     * @param string $body
     * @param int $status
     * @param array<string, string> $headers
     */
    public function __construct(
        public readonly string $body,
        public readonly int $status = 200,
        public readonly array $headers = [],
    ) {
    }

    /**
     * Send the response to the client
     *
     * @param bool $withBody false for HEAD requests: same headers, no body
     *
     * @return void
     */
    public function send(bool $withBody = true): void
    {
        http_response_code($this->status);
        header('Content-Length: ' . strlen($this->body));
        header('Content-Type: text/html; charset=utf-8');

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        if ($withBody) {
            echo $this->body;
        }
    }
}
