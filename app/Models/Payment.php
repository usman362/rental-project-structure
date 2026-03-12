<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class Payment
{
    /**
     * Find a payment by ID
     * Joins renters, users, and properties tables
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT p.*, r.move_in_date, u.first_name, u.last_name, u.email, u.phone,
                    pr.name as property_name, pr.address, pr.city
             FROM payments p
             LEFT JOIN renters r ON p.renter_id = r.id
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN properties pr ON p.property_id = pr.id
             WHERE p.id = ?',
            [$id]
        );
    }

    /**
     * Get all payments with optional filters
     *
     * @param array $filters Optional filters: search (renter name), status, method, date_from, date_to
     * @return array Array of payment records with renter and property information
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
            $where[] = 'p.status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['method'])) {
            $where[] = 'p.method = ?';
            $params[] = $filters['method'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = 'DATE(p.due_date) >= ?';
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = 'DATE(p.due_date) <= ?';
            $params[] = $filters['date_to'];
        }

        $sql = 'SELECT p.*, r.move_in_date, u.first_name, u.last_name, u.email, u.phone,
                       pr.name as property_name, pr.address, pr.city
                FROM payments p
                LEFT JOIN renters r ON p.renter_id = r.id
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN properties pr ON p.property_id = pr.id';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY p.due_date DESC';

        return Database::all($sql, $params);
    }

    /**
     * Get all payments for a specific renter
     */
    public static function forRenter(int $renterId): array
    {
        return Database::all(
            'SELECT p.*, r.move_in_date, u.first_name, u.last_name,
                    pr.name as property_name, pr.address
             FROM payments p
             LEFT JOIN renters r ON p.renter_id = r.id
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN properties pr ON p.property_id = pr.id
             WHERE p.renter_id = ?
             ORDER BY p.due_date DESC',
            [$renterId]
        );
    }

    /**
     * Create a new payment
     *
     * @param array $data Payment data
     * @return int Last inserted payment ID
     */
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO payments
                (renter_id, property_id, amount, due_date, paid_date, method, status,
                 period_from, period_to, notes, receipt_number, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';

        $params = [
            (int) $data['renter_id'],
            (int) $data['property_id'],
            (float) $data['amount'],
            $data['due_date'],
            $data['paid_date'] ?? null,
            $data['method'] ?? null,
            $data['status'] ?? 'pending',
            $data['period_from'] ?? null,
            $data['period_to'] ?? null,
            $data['notes'] ?? null,
            $data['receipt_number'] ?? null
        ];

        Database::query($sql, $params);
        return (int) Database::getInstance()->lastInsertId();
    }

    /**
     * Update a payment
     */
    public static function update(int $id, array $data): bool
    {
        $updates = [];
        $params = [];

        $allowedFields = ['renter_id', 'property_id', 'amount', 'due_date', 'paid_date', 'method',
                         'status', 'period_from', 'period_to', 'notes', 'receipt_number'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";

                if (in_array($field, ['renter_id', 'property_id'])) {
                    $params[] = (int) $data[$field];
                } elseif ($field === 'amount') {
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
        $sql = 'UPDATE payments SET ' . implode(', ', $updates) . ', updated_at = NOW() WHERE id = ?';

        Database::query($sql, $params);
        return true;
    }

    /**
     * Delete a payment
     */
    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM payments WHERE id = ?', [$id]);
        return true;
    }

    /**
     * Get payment summary statistics
     *
     * @return array Summary with keys: total_collected, pending, overdue
     */
    public static function summary(): array
    {
        $collected = Database::one(
            'SELECT SUM(amount) as total FROM payments WHERE status = ?',
            ['paid']
        );

        $pending = Database::one(
            'SELECT SUM(amount) as total FROM payments WHERE status = ? AND due_date >= CURDATE()',
            ['pending']
        );

        $overdue = Database::one(
            'SELECT SUM(amount) as total FROM payments WHERE status = ? AND due_date < CURDATE()',
            ['pending']
        );

        return [
            'total_collected' => (float) ($collected['total'] ?? 0),
            'pending' => (float) ($pending['total'] ?? 0),
            'overdue' => (float) ($overdue['total'] ?? 0)
        ];
    }

    /**
     * Get monthly summary for a date range
     *
     * @param string $dateFrom Start date (Y-m-d)
     * @param string $dateTo End date (Y-m-d)
     * @return array Summary with total_collected, total_pending, total_overdue, payment_count
     */
    public static function monthlySummary(string $dateFrom, string $dateTo): array
    {
        $collected = Database::one(
            'SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = ? AND due_date BETWEEN ? AND ?',
            ['paid', $dateFrom, $dateTo]
        );

        $pending = Database::one(
            'SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = ? AND due_date BETWEEN ? AND ?',
            ['pending', $dateFrom, $dateTo]
        );

        $overdue = Database::one(
            'SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = ? AND due_date < CURDATE() AND due_date BETWEEN ? AND ?',
            ['pending', $dateFrom, $dateTo]
        );

        $count = Database::one(
            'SELECT COUNT(*) as total FROM payments WHERE due_date BETWEEN ? AND ?',
            [$dateFrom, $dateTo]
        );

        return [
            'total_collected' => (float) ($collected['total'] ?? 0),
            'total_pending' => (float) ($pending['total'] ?? 0),
            'total_overdue' => (float) ($overdue['total'] ?? 0),
            'payment_count' => (int) ($count['total'] ?? 0)
        ];
    }

    /**
     * Get revenue data for the last 6 months (for charts)
     *
     * @return array With 'labels' (month names) and 'values' (revenue amounts)
     */
    public static function lastSixMonthsRevenue(): array
    {
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-{$i} months"));
            $monthEnd = date('Y-m-t', strtotime("-{$i} months"));
            $monthLabel = date('M Y', strtotime("-{$i} months"));

            $result = Database::one(
                'SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = ? AND paid_date BETWEEN ? AND ?',
                ['paid', $monthStart, $monthEnd]
            );

            $labels[] = $monthLabel;
            $values[] = (float) ($result['total'] ?? 0);
        }

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    /**
     * Get total count of payments
     */
    public static function count(): int
    {
        $result = Database::one('SELECT COUNT(*) as count FROM payments', []);
        return (int) ($result['count'] ?? 0);
    }
}
