<?php
declare(strict_types=1);

abstract class Controller
{
    public function view(string $name, array $data = []): void
    {
        extract($data);

        $name = str_replace('.', '/', $name);
        $viewPath = BASE_PATH . '/app/Views/' . $name . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "View not found: $name";
            return;
        }

        require $viewPath;
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    public function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
