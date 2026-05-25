<?php

namespace App;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): self
    {
        $this->routes['GET'][$path] = $handler;
        return $this;
    }

    public function post(string $path, callable $handler): self
    {
        $this->routes['POST'][$path] = $handler;
        return $this;
    }

    public function resolve(string $method, string $uri): mixed
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            return ['error' => 'Not Found'];
        }

        return $handler();
    }
}
