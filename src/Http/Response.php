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
     */
    public function __construct(
        public readonly string $body,
        public readonly int $status = 200,
    ) {
    }

    /**
     * Send the response to the client
     *
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->status);
        header('Content-Length: ' . strlen($this->body));
        header('Content-Type: text/html; charset=utf-8');

        echo $this->body;
    }
}
