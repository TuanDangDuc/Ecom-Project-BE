<?php

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, callable $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function patch(string $path, callable $handler): void
    {
        $this->routes['PATCH'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $handler = null;
        $params = [];

        foreach ($this->routes[$method] ?? [] as $routePath => $routeHandler) {
            $matched = $this->matchRoute($routePath, $path, $params);

            if ($matched) {
                $handler = $routeHandler;
                break;
            }
        }

        if ($handler === null) {
            Response::json([
                'success' => false,
                'message' => 'Route not found'
            ], 404);
            return;
        }

        if ($params === []) {
            call_user_func($handler);
            return;
        }

        call_user_func_array($handler, $params);
    }

    private function matchRoute(string $routePath, string $path, array &$params): bool
    {
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $routePath, $matches);
        $paramNames = $matches[1];

        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        if (!preg_match($pattern, $path, $routeMatches)) {
            return false;
        }

        foreach ($paramNames as $index => $name) {
            $params[] = $routeMatches[$index + 1];
        }

        return true;
    }
}