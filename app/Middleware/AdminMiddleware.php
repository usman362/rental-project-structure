<?php
declare(strict_types=1);

class AdminMiddleware
{
    public function handle(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        if ($_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo '403 - Access Denied';
            exit;
        }
    }
}
