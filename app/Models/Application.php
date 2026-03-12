<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class Application
{
    /**
     * Find an application by ID
     * Includes property name joined from properties table
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT a.*, p.name as property_name
             FROM applications a
             LEFT JOIN properties p ON a.property_id = p.id
             WHERE a.id = ?',
            [$id]
        );
    }

    /**
     * Get all applications with optional filters
     *
     * @param array $filters Optional filters: search (applicant name/email), status, date_from, date_to
     * @return array Array of application records with property names
     */
    public static function all(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $where[] = '(a.first_name LIKE ? OR a.last_name LIKE ? OR a.email LIKE ?)';
            $params = array_merge($params, [$search, $search, $search]);
        }

        if (!empty($filters['status'])) {
            $where[] = 'a.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = 'DATE(a.submitted_at) >= ?';
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = 'DATE(a.submitted_at) <= ?';
            $params[] = $filters['date_to'];
        }

        $sql = 'SELECT a.*, p.name as property_name
                FROM applications a
                LEFT JOIN properties p ON a.property_id = p.id';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY a.submitted_at DESC';

        return Database::all($sql, $params);
    }

    /**
     * Create a new application
     *
     * @param array $data Application data
     * @return int Last inserted application ID
     */
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO applications
                (property_id, first_name, last_name, email, phone, employment, monthly_income,
                 credit_score, desired_move_in, lease_term, source, status, notes, submitted_at, reviewed_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)';

        $params = [
            (int) $data['property_id'],
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['employment'] ?? null,
            isset($data['monthly_income']) ? (float) $data['monthly_income'] : null,
            isset($data['credit_score']) ? (int) $data['credit_score'] : null,
            $data['desired_move_in'] ?? null,
            isset($data['lease_term']) ? (int) $data['lease_term'] : null,
            $data['source'] ?? null,
            $data['status'] ?? 'pending',
            $data['notes'] ?? null,
            $data['reviewed_by'] ?? null
        ];

        Database::query($sql, $params);
        return (int) Database::getInstance()->lastInsertId();
    }

    /**
     * Update application status with reviewer information
     */
    public static function updateStatus(int $id, string $status, ?int $reviewedBy = null): bool
    {
        $sql = 'UPDATE applications SET status = ?, reviewed_by = ? WHERE id = ?';
        Database::query($sql, [$status, $reviewedBy, $id]);
        return true;
    }

    /**
     * Delete an application
     */
    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM applications WHERE id = ?', [$id]);
        return true;
    }

    /**
     * Get total count of applications with optional filters
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

        $sql = 'SELECT COUNT(*) as count FROM applications';
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $result = Database::one($sql, $params);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get application count grouped by status
     *
     * @return array Associative array with status as key and count as value
     */
    public static function countByStatus(): array
    {
        $results = Database::all(
            'SELECT status, COUNT(*) as count FROM applications GROUP BY status',
            []
        );

        $counts = [
            'submitted' => 0,
            'under_review' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'withdrawn' => 0
        ];

        foreach ($results as $row) {
            $status = $row['status'] ?? '';
            if (isset($counts[$status])) {
                $counts[$status] = (int) $row['count'];
            }
        }

        return $counts;
    }
}
