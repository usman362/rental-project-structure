<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class Setting
{
    /**
     * Get a single setting by key
     *
     * @param string $key The setting key
     * @param mixed $default Default value if key not found
     * @return mixed The setting value or default
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $result = Database::one(
            'SELECT value FROM settings WHERE key_name = ?',
            [$key]
        );

        if ($result === null) {
            return $default;
        }

        return $result['value'];
    }

    /**
     * Set a setting value (INSERT ON DUPLICATE KEY UPDATE)
     *
     * @param string $key The setting key
     * @param string $value The setting value
     * @return bool Success status
     */
    public static function set(string $key, string $value): bool
    {
        $sql = 'INSERT INTO settings (key_name, value, updated_at)
                VALUES (?, ?, NOW())
                ON DUPLICATE KEY UPDATE
                value = VALUES(value),
                updated_at = NOW()';

        Database::query($sql, [$key, $value]);
        return true;
    }

    /**
     * Get multiple settings by keys
     *
     * @param array $keys Array of setting keys
     * @return array Associative array with key => value pairs
     */
    public static function getMultiple(array $keys): array
    {
        if (empty($keys)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $sql = "SELECT key_name, value FROM settings WHERE key_name IN ($placeholders)";

        $results = Database::all($sql, $keys);

        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key_name']] = $row['value'];
        }

        // Add missing keys with null values
        foreach ($keys as $key) {
            if (!isset($settings[$key])) {
                $settings[$key] = null;
            }
        }

        return $settings;
    }

    /**
     * Set multiple settings at once
     *
     * @param array $data Associative array of key => value pairs
     * @return void
     */
    public static function setMultiple(array $data): void
    {
        foreach ($data as $key => $value) {
            self::set($key, (string) $value);
        }
    }

    /**
     * Get all settings
     *
     * @return array Associative array with all key => value pairs
     */
    public static function all(): array
    {
        $results = Database::all(
            'SELECT key_name, value FROM settings ORDER BY key_name ASC',
            []
        );

        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key_name']] = $row['value'];
        }

        return $settings;
    }
}
