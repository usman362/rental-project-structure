<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

class User
{
    /**
     * Find a user by ID
     */
    public static function find(int $id): ?array
    {
        return Database::one(
            'SELECT * FROM users WHERE id = ?',
            [$id]
        );
    }

    /**
     * Find a user by email
     */
    public static function findByEmail(string $email): ?array
    {
        return Database::one(
            'SELECT * FROM users WHERE email = ?',
            [$email]
        );
    }

    /**
     * Find a user by username
     */
    public static function findByUsername(string $username): ?array
    {
        return Database::one(
            'SELECT * FROM users WHERE username = ?',
            [$username]
        );
    }

    /**
     * Find a user by username or email (for login)
     */
    public static function findByUsernameOrEmail(string $identifier): ?array
    {
        return Database::one(
            'SELECT * FROM users WHERE username = ? OR email = ?',
            [$identifier, $identifier]
        );
    }

    /**
     * Create a new user
     * Password is automatically hashed
     *
     * @param array $data Associative array with keys: username, email, password, first_name, last_name, phone, role
     * @return int Last inserted user ID
     */
    public static function create(array $data): int
    {
        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (username, email, password, first_name, last_name, phone, role, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())';

        $params = [
            $data['username'],
            $data['email'],
            $hashedPassword,
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['phone'] ?? null,
            $data['role'] ?? 'user'
        ];

        Database::query($sql, $params);
        return (int) Database::getInstance()->lastInsertId();
    }

    /**
     * Update a user
     */
    public static function update(int $id, array $data): bool
    {
        $updates = [];
        $params = [];

        $allowedFields = ['username', 'email', 'first_name', 'last_name', 'phone', 'role', 'password'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";

                if ($field === 'password') {
                    $params[] = password_hash($data[$field], PASSWORD_DEFAULT);
                } else {
                    $params[] = $data[$field];
                }
            }
        }

        if (empty($updates)) {
            return false;
        }

        $params[] = $id;
        $sql = 'UPDATE users SET ' . implode(', ', $updates) . ', updated_at = NOW() WHERE id = ?';

        Database::query($sql, $params);
        return true;
    }

    /**
     * Delete a user
     */
    public static function delete(int $id): bool
    {
        Database::query('DELETE FROM users WHERE id = ?', [$id]);
        return true;
    }

    /**
     * Get all users with optional filters
     *
     * @param array $filters Optional filters: search (searches username, email, first_name, last_name), role
     * @return array Array of user records
     */
    public static function all(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $where[] = '(username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)';
            $params = array_merge($params, [$search, $search, $search, $search]);
        }

        if (!empty($filters['role'])) {
            $where[] = 'role = ?';
            $params[] = $filters['role'];
        }

        $sql = 'SELECT * FROM users';

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY created_at DESC';

        return Database::all($sql, $params);
    }

    /**
     * Get total count of users
     */
    public static function count(): int
    {
        $result = Database::one('SELECT COUNT(*) as count FROM users', []);
        return (int) ($result['count'] ?? 0);
    }
}
