<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class Property
{
    /**
     * Find a property by ID
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT * FROM properties WHERE id = ?',
            [$id]
        );
    }

    /**
     * Get all properties with optional filters
     *
     * @param array $filters Optional filters: search (name, address), status (available, occupied, maintenance), type (apartment, house, condo, townhouse)
     * @return array Array of property records
     */
    public static function all(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $where[] = '(name LIKE ? OR address LIKE ? OR city LIKE ?)';
            $params = array_merge($params, [$search, $search, $search]);
        }

        if (!empty($filters['status'])) {
            $where[] = 'status = ?';
            $params[] = $filters['status'];
        }

        if (!empty($filters['type'])) {
            $where[] = 'type = ?';
            $params[] = $filters['type'];
        }

        $sql = 'SELECT * FROM properties';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY created_at DESC';

        return Database::all($sql, $params);
    }

    /**
     * Create a new property
     * Auto-generates listing_number as SMP-YY-NNN format
     *
     * @param array $data Property data
     * @return int Last inserted property ID
     */
    public static function create(array $data): int
    {
        // Generate listing number: SMP-YY-NNN (e.g., SMP-26-001)
        $year = substr(date('Y'), -2);
        $count = self::count() + 1;
        $listingNumber = 'SMP-' . $year . '-' . str_pad((string) $count, 3, '0', STR_PAD_LEFT);

        $sql = 'INSERT INTO properties
                (name, address, unit, city, state, zip, type, listing_number, monthly_rent, deposit,
                 status, bedrooms, bathrooms, sqft, description, amenities, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';

        $params = [
            $data['name'],
            $data['address'],
            $data['unit'] ?? null,
            $data['city'],
            $data['state'],
            $data['zip'],
            $data['type'],
            $listingNumber,
            (float) $data['monthly_rent'],
            (float) $data['deposit'],
            $data['status'] ?? 'available',
            (int) ($data['bedrooms'] ?? 0),
            (float) ($data['bathrooms'] ?? 0),
            (int) ($data['sqft'] ?? 0),
            $data['description'] ?? null,
            isset($data['amenities']) ? json_encode($data['amenities']) : null
        ];

        Database::query($sql, $params);
        return (int) Database::getInstance()->lastInsertId();
    }

    /**
     * Update a property
     */
    public static function update(int $id, array $data): bool
    {
        $updates = [];
        $params = [];

        $allowedFields = ['name', 'address', 'unit', 'city', 'state', 'zip', 'type', 'monthly_rent',
                         'deposit', 'status', 'bedrooms', 'bathrooms', 'sqft', 'description', 'amenities'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";

                if ($field === 'amenities') {
                    $params[] = is_array($data[$field]) ? json_encode($data[$field]) : $data[$field];
                } elseif (in_array($field, ['monthly_rent', 'deposit'])) {
                    $params[] = (float) $data[$field];
                } elseif (in_array($field, ['bedrooms', 'sqft'])) {
                    $params[] = (int) $data[$field];
                } elseif ($field === 'bathrooms') {
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
        $sql = 'UPDATE properties SET ' . implode(', ', $updates) . ', updated_at = NOW() WHERE id = ?';

        Database::query($sql, $params);
        return true;
    }

    /**
     * Delete a property
     */
    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM properties WHERE id = ?', [$id]);
        return true;
    }

    /**
     * Get total count of properties
     */
    public static function count(): int
    {
        $result = Database::one('SELECT COUNT(*) as count FROM properties', []);
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get property count grouped by status
     *
     * @return array Associative array with status as key and count as value
     */
    public static function countByStatus(): array
    {
        $results = Database::all(
            'SELECT status, COUNT(*) as count FROM properties GROUP BY status',
            []
        );

        $counts = [
            'available' => 0,
            'occupied' => 0,
            'maintenance' => 0
        ];

        foreach ($results as $row) {
            $counts[$row['status']] = (int) $row['count'];
        }

        return $counts;
    }

    /**
     * Get only available properties
     */
    public static function available(): array
    {
        return Database::all(
            'SELECT * FROM properties WHERE status = ? ORDER BY name ASC',
            ['available']
        );
    }
}
