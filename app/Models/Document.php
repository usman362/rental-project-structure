<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class Document
{
    /**
     * Find a document by ID
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT d.*, p.name as property_name, p.address
             FROM documents d
             LEFT JOIN properties p ON d.property_id = p.id
             WHERE d.id = ?',
            [$id]
        );
    }

    /**
     * Get all documents for a specific renter
     */
    public static function forRenter(int $renterId): array
    {
        return Database::all(
            'SELECT d.*, p.name as property_name
             FROM documents d
             LEFT JOIN properties p ON d.property_id = p.id
             WHERE d.renter_id = ?
             ORDER BY d.created_at DESC',
            [$renterId]
        );
    }

    /**
     * Get all documents for a specific property
     */
    public static function forProperty(int $propertyId): array
    {
        return Database::all(
            'SELECT d.*, u.first_name, u.last_name
             FROM documents d
             LEFT JOIN users u ON d.user_id = u.id
             WHERE d.property_id = ?
             ORDER BY d.created_at DESC',
            [$propertyId]
        );
    }

    /**
     * Get all documents with optional filters
     */
    public static function all(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['renter_id'])) {
            $where[] = 'd.renter_id = ?';
            $params[] = (int) $filters['renter_id'];
        }

        if (!empty($filters['type'])) {
            $where[] = 'd.type = ?';
            $params[] = $filters['type'];
        }

        $sql = 'SELECT d.*, p.name as property_name, u.first_name, u.last_name
                FROM documents d
                LEFT JOIN properties p ON d.property_id = p.id
                LEFT JOIN users u ON d.user_id = u.id';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY d.created_at DESC';

        return Database::all($sql, $params);
    }

    /**
     * Create a new document record
     */
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO documents
                (renter_id, property_id, user_id, title, type, file_name, file_path, file_size, mime_type, uploaded_by, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';

        $params = [
            isset($data['renter_id']) ? (int) $data['renter_id'] : null,
            isset($data['property_id']) ? (int) $data['property_id'] : null,
            isset($data['user_id']) ? (int) $data['user_id'] : null,
            $data['title'],
            $data['type'] ?? 'other',
            $data['file_name'],
            $data['file_path'],
            (int) ($data['file_size'] ?? 0),
            $data['mime_type'] ?? null,
            $data['uploaded_by'] ?? 'renter'
        ];

        Database::query($sql, $params);
        return (int) Database::getInstance()->lastInsertId();
    }

    /**
     * Delete a document
     */
    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM documents WHERE id = ?', [$id]);
        return true;
    }

    /**
     * Count documents for a renter
     */
    public static function countForRenter(int $renterId): int
    {
        $result = Database::one(
            'SELECT COUNT(*) as count FROM documents WHERE renter_id = ?',
            [$renterId]
        );
        return (int) ($result['count'] ?? 0);
    }
}
