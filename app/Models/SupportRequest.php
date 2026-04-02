<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class SupportRequest
{
    /**
     * Get all support requests for a user, ordered by most recent first
     */
    public static function forUser(int $userId, int $limit = 10): array
    {
        return Database::all(
            'SELECT * FROM support_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT ' . $limit,
            [$userId]
        );
    }

    /**
     * Create a new support request
     */
    public static function create(array $data): int
    {
        $now = date('Y-m-d H:i:s');

        Database::query(
            'INSERT INTO support_requests (user_id, subject, category, message, status, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                (int)$data['user_id'],
                $data['subject'],
                $data['category'],
                $data['message'],
                $data['status'] ?? 'open',
                $now,
                $now
            ]
        );

        return (int)Database::getInstance()->lastInsertId();
    }

    /**
     * Find a support request by ID
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT * FROM support_requests WHERE id = ?',
            [$id]
        );
    }

    /**
     * Get all support requests (admin view)
     */
    public static function all(): array
    {
        return Database::all(
            'SELECT sr.*, u.first_name, u.last_name, u.email
             FROM support_requests sr
             LEFT JOIN users u ON sr.user_id = u.id
             ORDER BY sr.created_at DESC',
            []
        );
    }

    /**
     * Update status of a support request
     */
    public static function updateStatus(int $id, string $status, ?string $adminReply = null): void
    {
        $now = date('Y-m-d H:i:s');

        if ($adminReply !== null) {
            Database::query(
                'UPDATE support_requests SET status = ?, admin_reply = ?, replied_at = ?, updated_at = ? WHERE id = ?',
                [$status, $adminReply, $now, $now, $id]
            );
        } else {
            Database::query(
                'UPDATE support_requests SET status = ?, updated_at = ? WHERE id = ?',
                [$status, $now, $id]
            );
        }
    }

    /**
     * Count open support requests for a user
     */
    public static function countOpenForUser(int $userId): int
    {
        $result = Database::one(
            'SELECT COUNT(*) as cnt FROM support_requests WHERE user_id = ? AND status != ?',
            [$userId, 'closed']
        );
        return (int)($result['cnt'] ?? 0);
    }
}
