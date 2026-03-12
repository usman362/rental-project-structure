<?php
declare(strict_types=1);

function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function old(string $key, string $default = ''): string
{
    if (isset($_SESSION['_old_input'][$key])) {
        return $_SESSION['_old_input'][$key];
    }

    return $default;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }

    if (isset($_SESSION['_flash'][$key])) {
        $value = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    return null;
}

function csrf_field(): string
{
    return CSRF::field();
}

function auth(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_admin(): bool
{
    $user = auth();
    return $user && isset($user['role']) && $user['role'] === 'admin';
}

function is_renter(): bool
{
    $user = auth();
    return $user && isset($user['role']) && $user['role'] === 'renter';
}

function route(string $name, string $param = ''): string
{
    // Handle asset routes
    if ($name === 'assets.css') {
        return '/assets/css/' . ($param ?: 'app.css');
    }
    if ($name === 'assets.js') {
        return '/assets/js/' . ($param ?: 'app.js');
    }

    $routes = [
        'home' => '/',
        'application' => '/application',
        'rental-application' => '/application',
        'login' => '/login',
        'logout' => '/logout',
        'auth.login' => '/login',
        'auth.logout' => '/logout',
        'admin.dashboard' => '/admin/dashboard',
        'admin.renters' => '/admin/renters',
        'admin.applications' => '/admin/applications',
        'admin.properties' => '/admin/properties',
        'admin.payments' => '/admin/payments',
        'admin.maintenance' => '/admin/maintenance',
        'admin.reports' => '/admin/reports',
        'admin.settings' => '/admin/settings',
        'renter.portal' => '/renter/portal',
        'renter.profile' => '/renter/profile',
        'renter.settings' => '/renter/settings',
        'renter.help' => '/renter/help',
    ];

    $url = $routes[$name] ?? '/';

    // If param is an ID, append it
    if ($param && !str_starts_with($name, 'assets.')) {
        $url .= '/' . $param;
    }

    return $url;
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function url(string $path): string
{
    $baseUrl = $GLOBALS['config']['BASE_URL'] ?? '';
    return $baseUrl . '/' . ltrim($path, '/');
}

function dd(mixed $var): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    exit;
}

function session_flash_errors(array $errors): void
{
    $_SESSION['_errors'] = $errors;
}

function session_flash_old_input(array $data): void
{
    $_SESSION['_old_input'] = $data;
}

$GLOBALS['config'] = require BASE_PATH . '/config/app.php';
