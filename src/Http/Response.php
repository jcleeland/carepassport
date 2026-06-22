<?php

declare(strict_types=1);

namespace CarePassport\Http;

final class Response
{
    public function __construct(
        private readonly string $content = '',
        private readonly int $status = 200,
        /** @var array<string, string> */
        private readonly array $headers = [],
    ) {
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;
    }

    public static function redirect(string $path): self
    {
        return new self('', 302, ['Location' => $path]);
    }
}
