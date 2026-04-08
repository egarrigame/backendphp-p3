<?php

declare(strict_types=1);

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../app/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            die("La vista {$view} no existe.");
        }

        require __DIR__ . '/../app/views/layouts/header.php';
        require __DIR__ . '/../app/views/layouts/navbar.php';
        require $viewPath;
        require __DIR__ . '/../app/views/layouts/footer.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}