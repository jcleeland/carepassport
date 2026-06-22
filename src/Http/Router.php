<?php

declare(strict_types=1);

namespace CarePassport\Http;

final class Router
{
    /** @var array<string, callable(): Response> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET ' . $path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST ' . $path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $handler = $this->routes[$request->method . ' ' . $request->path] ?? null;

        if ($handler === null) {
            return new Response('Not found', 404);
        }

        return $handler();
    }
}
