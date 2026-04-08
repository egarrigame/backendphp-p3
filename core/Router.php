<?php

declare(strict_types=1);

class Router
{
    private array $getRoutes = [];
    private array $postRoutes = [];

    public function get(string $uri, array $action): void
    {
        $this->getRoutes[$this->normalizeUri($uri)] = $action;
    }

    public function post(string $uri, array $action): void
    {
        $this->postRoutes[$this->normalizeUri($uri)] = $action;
    }

    public function resolve(string $requestUri, string $requestMethod): void
    {
        $uri = $this->normalizeUri(parse_url($requestUri, PHP_URL_PATH) ?? '/');

        $routes = strtoupper($requestMethod) === 'POST' ? $this->postRoutes : $this->getRoutes;

        if (!isset($routes[$uri])) {
            http_response_code(404);
            echo "<h1>404 - Página no encontrada</h1>";
            return;
        }

        [$controllerName, $methodName] = $routes[$uri];

        if (!class_exists($controllerName)) {
            http_response_code(500);
            die("El controlador {$controllerName} no existe.");
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            http_response_code(500);
            die("El método {$methodName} no existe en {$controllerName}.");
        }

        $controller->$methodName();
    }

    private function normalizeUri(string $uri): string
    {
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        return $uri === '' ? '/' : $uri;
    }
}