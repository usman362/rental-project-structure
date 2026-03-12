<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class MaintenanceRequest
{
    /**
     * Find a maintenance request by ID
     * Joins properties and renters->users tables
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT m.*, p.name as property_name, p.address, p.city,
                    u.first_name, u.last_name, u.email, u.phone
             FROM maintenance_requests m
             LEFT JOIN properties p ON m.property_id = p.id
             LEFT JOIN renters r ON m.renter_id = r.id
             LEFT JOIN users u ON r.user_id = u.id
             WHERE m.id = ?',
            [$id]
        );
    }

    /**
     * Get all maintenance requests with optional filters
     *
     * @param array $filters Optional filters: search (title/description), status, priority, property_id
     * @return array Array of maintenance request records with property and renter information
     */
    public static function all(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $where[] = '(m.title LIKE ? OR m.description LIKE ?)';
            $params = array_merge($params, [$search, $search]);
        }

        if (!empty($filters['status'])) {
            $where[] = 'm.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['priority'])) {
            $where[] = 'm.priority = ?';
            $params[] = $filters['priority'];
        }

        if (!empty($filters['property_id'])) {
            $where[] = 'm.property_id = ?';
            $params[] = (int) $filters['property_id'];
        }

        $sql = 'SELECT m.*, p.name as property_name, p.address, p.city,
                       u.first_name, u.last_name, u.email, u.phone
                FROM maintenance_requests m
                LEFT JOIN properties p ON m.property_id = p.id
                LEFT JOIN renters r ON m.renter_id = r.id
                LEFT JOIN users u ON r.user_id = u.id';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY m.priority DESC, m.created_at DESC';

        return Database::all($sql, $params);
    }

    /**
     * Get all maintenance requests for a specific renter
     */
    public static function forRenter(int $renterId): array
    {
        return Database::all(
            'SELECT m.*, p.name as property_name, p.address
             FROM maintenance_requests m
             LEFT JOIN properties p ON m.property_id = p.id
             WHERE m.renter_id = ?
             ORDER BY m.priority DESC, m.created_at DESC',
            [$renterId]
        );
    }

    /**
     * Create a new maintenance request
     *
     * @param array $data Maintenance request data
     * @return int Last inserted maintenance request ID
     */
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO maintenance_requests
                (property_id, renter_id, title, description, category, priority, status,
                 assigned_to, estimated_cost, actual_cost, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';

        $params = [
            (int) $data['property_id'],
            isset($data['renter_id']) ? (int) $data['renter_id'] : null,
            $data['title'],
            $data['description'] ?? null,
            $data['category'] ?? null,
            $data['priority'] ?? 'medium',
            $data['status'] ?? 'open',
            isset($data['assigned_to']) ? (int) $data['assigned_to'] : null,
            isset($data['estimated_cost']) ? (float) $data['estimated_cost'] : null,
            isset($data['actual_cost']) ? (float) $data['actual_cost'] : null
        ];

        Database::query($sql, $params);
        return (int) Database::getInstance()->lastInsertId();
    }

    /**
     * Update maintenance request status
     */
    public static function updateStatus(int $id, string $status): bool
    {
        Database::query(
            'UPDATE maintenance_requests SET status = ?, updated_at = NOW() WHERE id = ?',
            [$status, $id]
        );
        return true;
    }

    /**
     * Update a maintenance request
     */
    public static function update(int $id, array $data): bool
    {
        $updates = [];
        $params = [];

        $allowedFields = ['property_id', 'renter_id', 'title', 'description', 'category',
                         'priority', 'status', 'assigned_to', 'estimated_cost', 'actual_cost'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";

                if (in_array($field, ['property_id', 'renter_id', 'assigned_to'])) {
                    $params[] = isset($data[$field]) ? (int) $data[$field] : null;
                } elseif (in_array($field, ['estimated_cost', 'actual_cost'])) {
                    $params[] = isset($data[$field]) ? (float) $data[$field] : null;
                } else {
                    $params[] = $data[$field];
                }
            }
        }

        if (empty($updates)) {
            return false;
        }

        $updates[] = 'updated_at = NOW()';
        $params[] = $id;
        $sql = 'UPDATE maintenance_requests SET ' . implode(', ', $updates) . ' WHERE id = ?';

        Database::query($sql, $params);
        return true;
    }

    /**
     * Delete a maintenance request
     */
    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM maintenance_requests WHERE id = ?', [$id]);
        return true;
    }

    /**
     * Get total count of maintenance requests with optional filters
     *
     * @param array $filters Optional filters: status
     * @return int
     */
    public static function count(array $filters = []): int
    {
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'status = ?';
            $params[] = $filters['status'];
        }

        $sql = 'SELECT COUNT(*) as count FROM maintenance_requests';
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $result = Database::one($sql, $params);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get maintenance request count grouped by status
     *
     * @return array Associative array with status as key and count as value
     */
    public static function countByStatus(): array
    {
        $results = Database::all(
            'SELECT status, COUNT(*) as count FROM maintenance_requests GROUP BY status',
            []
        );

        $counts = [
            'open' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];

        foreach ($results as $row) {
            if (isset($counts[$row['status']])) {
                $counts[$row['status']] = (int) $row['count'];
            }
        }

        return $counts;
    }
}
