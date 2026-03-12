<?php
declare(strict_types=1);

class AuthMiddleware
{
    public function handle(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
    }
}
