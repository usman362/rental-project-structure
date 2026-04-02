<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class UserSetting
{
    /**
     * Default settings for a new user
     */
    private static array $defaults = [
        'email_notifications' => '1',
        'sms_notifications' => '0',
        'payment_reminders' => '1',
        'maintenance_updates' => '1',
        'newsletter' => '0',
        'marketing' => '0',
        'show_profile' => '1',
        'show_phone' => '0',
        'show_email' => '0',
        'allow_data_collection' => '0',
        'language' => 'en',
        'timezone' => 'America/Denver',
        'date_format' => 'MM/DD/YYYY'
    ];

    /**
     * Boolean setting keys (stored as '0'/'1')
     */
    private static array $booleanKeys = [
        'email_notifications', 'sms_notifications', 'payment_reminders',
        'maintenance_updates', 'newsletter', 'marketing',
        'show_profile', 'show_phone', 'show_email', 'allow_data_collection'
    ];

    /**
     * Get all settings for a user, merged with defaults
     */
    public static function allForUser(int $userId): array
    {
        $rows = Database::all(
            'SELECT setting_key, setting_value FROM user_settings WHERE user_id = ?',
            [$userId]
        );

        $settings = self::$defaults;

        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        // Convert boolean keys to actual booleans for the view
        foreach (self::$booleanKeys as $key) {
            if (isset($settings[$key])) {
                $settings[$key] = (bool)(int)$settings[$key];
            }
        }

        return $settings;
    }

    /**
     * Save all settings for a user (INSERT ON DUPLICATE KEY UPDATE)
     */
    public static function saveForUser(int $userId, array $settings): void
    {
        $now = date('Y-m-d H:i:s');

        foreach ($settings as $key => $value) {
            // Only save known keys
            if (!array_key_exists($key, self::$defaults)) {
                continue;
            }

            // Convert booleans to '0'/'1'
            if (in_array($key, self::$booleanKeys)) {
                $value = $value ? '1' : '0';
            }

            Database::query(
                'INSERT INTO user_settings (user_id, setting_key, setting_value, updated_at)
                 VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = VALUES(updated_at)',
                [$userId, $key, (string)$value, $now]
            );
        }
    }
}
