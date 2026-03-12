<?php
declare(strict_types=1);

class CSRF
{
    private const SESSION_KEY = '_csrf_token';
    private const FIELD_NAME = '_token';

    public static function generate(): string
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function field(): string
    {
        $token = self::generate();
        return sprintf(
            '<input type="hidden" name="%s" value="%s">',
            self::FIELD_NAME,
            htmlspecialchars($token)
        );
    }

    public static function verify(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }

        $token = $_POST[self::FIELD_NAME] ?? '';

        if (!isset($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        if (!hash_equals($_SESSION[self::SESSION_KEY], $token)) {
            return false;
        }

        return true;
    }

    public static function middleware(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (!self::verify()) {
            $_SESSION['_error'] = 'CSRF token mismatch. Please try again.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
            exit;
        }
    }
}
