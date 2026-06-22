<?php

declare(strict_types=1);

namespace CarePassport\Http;

final class Request
{
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $post
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        private readonly array $query,
        private readonly array $post,
    ) {
    }

    public static function capture(): self
    {
        $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
        $path = parse_url($uri, PHP_URL_PATH);

        return new self(
            strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')),
            $path !== false && $path !== null ? $path : '/',
            $_GET,
            $_POST,
        );
    }

    public function input(string $key, string $default = ''): string
    {
        $value = $this->post[$key] ?? $this->query[$key] ?? $default;

        if (is_array($value)) {
            return $default;
        }

        return trim((string) $value);
    }

    /**
     * @return array<string, mixed>
     */
    public function arrayInput(string $key): array
    {
        $value = $this->post[$key] ?? [];

        return is_array($value) ? $value : [];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function file(string $key): ?array
    {
        $file = $_FILES[$key] ?? null;

        return is_array($file) ? $file : null;
    }
}
