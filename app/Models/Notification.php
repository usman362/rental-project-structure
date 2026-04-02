<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class Notification
{
    /**
     * Get all notifications for a user (newest first)
     */
    public static function forUser(int $userId, int $limit = 20): array
    {
        return Database::all(
            'SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ' . $limit,
            [$userId]
        );
    }

    /**
     * Get unread notifications for a user
     */
    public static function unreadForUser(int $userId): array
    {
        return Database::all(
            'SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC',
            [$userId]
        );
    }

    /**
     * Count unread notifications for a user
     */
    public static function unreadCount(int $userId): int
    {
        $result = Database::one(
            'SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0',
            [$userId]
        );
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Create a new notification
     */
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO notifications (user_id, type, icon, title, message, is_read, link, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())';

        $params = [
            (int) $data['user_id'],
            $data['type'] ?? 'info',
            $data['icon'] ?? 'bell',
            $data['title'],
            $data['message'],
            (int) ($data['is_read'] ?? 0),
            $data['link'] ?? null
        ];

        Database::query($sql, $params);
        return (int) Database::getInstance()->lastInsertId();
    }

    /**
     * Mark a single notification as read
     */
    public static function markRead(int $id): bool
    {
        Database::query(
            'UPDATE notifications SET is_read = 1 WHERE id = ?',
            [$id]
        );
        return true;
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllRead(int $userId): bool
    {
        Database::query(
            'UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0',
            [$userId]
        );
        return true;
    }

    /**
     * Delete a notification
     */
    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM notifications WHERE id = ?', [$id]);
        return true;
    }

    /**
     * Find a notification by ID
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT * FROM notifications WHERE id = ?',
            [$id]
        );
    }
}
