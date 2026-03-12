<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class Renter
{
    /**
     * Find a renter by ID
     * Joins users and properties tables
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT r.*, u.username, u.email, u.first_name, u.last_name, u.phone,
                    p.name as property_name, p.address, p.city, p.state, p.zip
             FROM renters r
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN properties p ON r.property_id = p.id
             WHERE r.id = ?',
            [$id]
        );
    }

    /**
     * Find a renter by user ID
     * Joins properties table
     */
    public static function findByUserId(int $userId): ?array
    {
        return Database::one(
            'SELECT r.*, p.name as property_name, p.address, p.city, p.state, p.zip
             FROM renters r
             LEFT JOIN properties p ON r.property_id = p.id
             WHERE r.user_id = ?',
            [$userId]
        );
    }

    /**
     * Get all renters with optional filters
     *
     * @param array $filters Optional filters: search (name/email), status, property_id
     * @return array Array of renter records with user and property information
     */
    public static function all(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $where[] = '(u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)';
            $params = array_merge($params, [$search, $search, $search]);
        }

        if (!empty($filters['status'])) {
            $where[] = 'r.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['property_id'])) {
            $where[] = 'r.property_id = ?';
            $params[] = (int) $filters['property_id'];
        }

        $sql = 'SELECT r.*, u.username, u.email, u.first_name, u.last_name, u.phone,
                       p.name as property_name, p.address, p.city, p.state, p.zip
                FROM renters r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN properties p ON r.property_id = p.id';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY r.created_at DESC';

        return Database::all($sql, $params);
    }

    /**
     * Create a new renter
     *
     * @param array $data Renter data including user_id, property_id, move_in_date, lease_end,
     *                     monthly_rent, security_deposit, status, emergency_contact, notes
     * @return int Last inserted renter ID
     */
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO renters
                (user_id, property_id, move_in_date, lease_end, monthly_rent, security_deposit,
                 status, emergency_contact, notes, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';

        $params = [
            (int) $data['user_id'],
            (int) $data['property_id'],
            $data['move_in_date'],
            $data['lease_end'] ?? null,
            (float) $data['monthly_rent'],
            (float) $data['security_deposit'],
            $data['status'] ?? 'active',
            $data['emergency_contact'] ?? null,
            $data['notes'] ?? null
        ];

        Database::query($sql, $params);
        return (int) Database::getInstance()->lastInsertId();
    }

    /**
     * Update a renter
     */
    public static function update(int $id, array $data): bool
    {
        $updates = [];
        $params = [];

        $allowedFields = ['user_id', 'property_id', 'move_in_date', 'lease_end', 'monthly_rent',
                         'security_deposit', 'status', 'emergency_contact', 'notes'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";

                if (in_array($field, ['user_id', 'property_id'])) {
                    $params[] = (int) $data[$field];
                } elseif (in_array($field, ['monthly_rent', 'security_deposit'])) {
                    $params[] = (float) $data[$field];
                } else {
                    $params[] = $data[$field];
                }
            }
        }

        if (empty($updates)) {
            return false;
        }

        $params[] = $id;
        $sql = 'UPDATE renters SET ' . implode(', ', $updates) . ', updated_at = NOW() WHERE id = ?';

        Database::query($sql, $params);
        return true;
    }

    /**
     * Delete a renter
     */
    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM renters WHERE id = ?', [$id]);
        return true;
    }

    /**
     * Get total count of renters
     */
    public static function count(): int
    {
        $result = Database::one('SELECT COUNT(*) as count FROM renters', []);
        return (int) ($result['count'] ?? 0);
    }
}
