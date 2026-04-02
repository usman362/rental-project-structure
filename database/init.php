<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Database.php';

$pdo = Database::getInstance();

try {
    // Enable foreign keys
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            role ENUM('admin','renter') NOT NULL,
            username VARCHAR(100) NOT NULL UNIQUE,
            email VARCHAR(190) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(100),
            last_name VARCHAR(100),
            phone VARCHAR(50),
            avatar VARCHAR(255),
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            INDEX idx_username (username),
            INDEX idx_email (email),
            INDEX idx_role (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create properties table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS properties (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(190) NOT NULL,
            address VARCHAR(255) NOT NULL,
            unit VARCHAR(50),
            city VARCHAR(100),
            state VARCHAR(50),
            zip VARCHAR(20),
            type ENUM('apartment','house','condo','townhouse') DEFAULT 'apartment',
            listing_number VARCHAR(100) UNIQUE,
            monthly_rent DECIMAL(12,2) NOT NULL,
            deposit DECIMAL(12,2) DEFAULT 0,
            status VARCHAR(30) NOT NULL DEFAULT 'available',
            bedrooms INT DEFAULT 1,
            bathrooms DECIMAL(4,1) DEFAULT 1,
            sqft INT DEFAULT 700,
            description TEXT,
            amenities TEXT,
            created_at DATETIME NOT NULL,
            INDEX idx_name (name),
            INDEX idx_status (status),
            INDEX idx_city_state (city, state),
            INDEX idx_listing_number (listing_number)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create applications table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS applications (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            property_id INT UNSIGNED,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(190) NOT NULL,
            phone VARCHAR(50),
            employment VARCHAR(190),
            monthly_income DECIMAL(12,2) DEFAULT 0,
            credit_score INT DEFAULT 0,
            desired_move_in DATE,
            lease_term INT DEFAULT 12,
            source VARCHAR(100),
            status VARCHAR(30) DEFAULT 'submitted',
            notes TEXT,
            submitted_at DATETIME NOT NULL,
            reviewed_by INT UNSIGNED,
            INDEX idx_property_id (property_id),
            INDEX idx_status (status),
            INDEX idx_email (email),
            INDEX idx_reviewed_by (reviewed_by),
            CONSTRAINT fk_applications_property_id
                FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL,
            CONSTRAINT fk_applications_reviewed_by
                FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create renters table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS renters (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED,
            property_id INT UNSIGNED,
            move_in_date DATE,
            lease_end DATE,
            monthly_rent DECIMAL(12,2),
            security_deposit DECIMAL(12,2) DEFAULT 0,
            status VARCHAR(30) DEFAULT 'active',
            emergency_contact VARCHAR(255),
            notes TEXT,
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            INDEX idx_user_id (user_id),
            INDEX idx_property_id (property_id),
            INDEX idx_status (status),
            CONSTRAINT fk_renters_user_id
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            CONSTRAINT fk_renters_property_id
                FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create payments table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS payments (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            renter_id INT UNSIGNED,
            property_id INT UNSIGNED,
            amount DECIMAL(12,2) NOT NULL,
            due_date DATE NOT NULL,
            paid_date DATE,
            method VARCHAR(100),
            status VARCHAR(30) DEFAULT 'pending',
            period_from DATE,
            period_to DATE,
            notes TEXT,
            receipt_number VARCHAR(100),
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            INDEX idx_renter_id (renter_id),
            INDEX idx_property_id (property_id),
            INDEX idx_status (status),
            INDEX idx_due_date (due_date),
            INDEX idx_paid_date (paid_date),
            CONSTRAINT fk_payments_renter_id
                FOREIGN KEY (renter_id) REFERENCES renters(id) ON DELETE SET NULL,
            CONSTRAINT fk_payments_property_id
                FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create maintenance_requests table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS maintenance_requests (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            property_id INT UNSIGNED,
            renter_id INT UNSIGNED,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            category VARCHAR(100),
            priority VARCHAR(20) DEFAULT 'medium',
            status VARCHAR(30) DEFAULT 'open',
            assigned_to VARCHAR(190),
            estimated_cost DECIMAL(12,2),
            actual_cost DECIMAL(12,2),
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            INDEX idx_property_id (property_id),
            INDEX idx_renter_id (renter_id),
            INDEX idx_status (status),
            INDEX idx_priority (priority),
            INDEX idx_created_at (created_at),
            CONSTRAINT fk_maintenance_property_id
                FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL,
            CONSTRAINT fk_maintenance_renter_id
                FOREIGN KEY (renter_id) REFERENCES renters(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create notifications table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS notifications (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            type VARCHAR(50) NOT NULL DEFAULT 'info',
            icon VARCHAR(50) DEFAULT 'bell',
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            is_read TINYINT(1) DEFAULT 0,
            link VARCHAR(500),
            created_at DATETIME NOT NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_is_read (is_read),
            INDEX idx_type (type),
            INDEX idx_created_at (created_at),
            CONSTRAINT fk_notifications_user_id
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create documents table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS documents (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            renter_id INT UNSIGNED,
            property_id INT UNSIGNED,
            user_id INT UNSIGNED,
            title VARCHAR(255) NOT NULL,
            type VARCHAR(50) DEFAULT 'other',
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(500) NOT NULL,
            file_size INT UNSIGNED DEFAULT 0,
            mime_type VARCHAR(100),
            uploaded_by ENUM('admin','renter') DEFAULT 'renter',
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            INDEX idx_renter_id (renter_id),
            INDEX idx_property_id (property_id),
            INDEX idx_user_id (user_id),
            INDEX idx_type (type),
            CONSTRAINT fk_documents_renter_id
                FOREIGN KEY (renter_id) REFERENCES renters(id) ON DELETE SET NULL,
            CONSTRAINT fk_documents_property_id
                FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL,
            CONSTRAINT fk_documents_user_id
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create settings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            key_name VARCHAR(190) PRIMARY KEY,
            value TEXT,
            updated_at DATETIME NOT NULL,
            INDEX idx_updated_at (updated_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create user_settings table (per-user preferences)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_settings (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            setting_key VARCHAR(100) NOT NULL,
            setting_value VARCHAR(255) NOT NULL DEFAULT '',
            updated_at DATETIME NOT NULL,
            UNIQUE KEY uk_user_setting (user_id, setting_key),
            INDEX idx_user_id (user_id),
            CONSTRAINT fk_user_settings_user_id
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Create support_requests table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS support_requests (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            subject VARCHAR(255) NOT NULL,
            category VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            status VARCHAR(30) DEFAULT 'open',
            admin_reply TEXT,
            replied_at DATETIME,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            CONSTRAINT fk_support_requests_user_id
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Seed admin user (username: admin, password: password)
    $adminPassword = password_hash('password', PASSWORD_BCRYPT);
    $adminCreatedAt = date('Y-m-d H:i:s');

    $pdo->prepare("
        INSERT INTO users (role, username, email, password, first_name, last_name, phone, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE password = VALUES(password), role = VALUES(role), updated_at = NOW()
    ")->execute(['admin', 'admin', 'admin@sotelomanagement.com', $adminPassword, 'Admin', 'User', '1-800-SOTELO-1', $adminCreatedAt]);

    // Get admin user ID
    $adminRow = $pdo->query("SELECT id FROM users WHERE username = 'admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $adminId = $adminRow ? (int) $adminRow['id'] : null;

    // Seed renter user (username: test, password: password)
    $renterPassword = password_hash('password', PASSWORD_BCRYPT);
    $renterCreatedAt = date('Y-m-d H:i:s');

    $pdo->prepare("
        INSERT INTO users (role, username, email, password, first_name, last_name, phone, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE password = VALUES(password), username = VALUES(username), role = VALUES(role), updated_at = NOW()
    ")->execute(['renter', 'test', 'test@sotelomanagement.com', $renterPassword, 'Test', 'Renter', '555-1234', $renterCreatedAt]);

    // Get renter user ID for later use
    $renterUser = $pdo->query("SELECT id FROM users WHERE username = 'test' LIMIT 1")->fetch();
    $renterId = $renterUser ? $renterUser['id'] : null;

    // Seed sample properties
    $now = date('Y-m-d H:i:s');
    $propertyIds = [];

    $properties = [
        ['Maple Residency', '123 Maple Street', null, 'New York', 'NY', '10001', 'apartment', 'MAP-001', 1500.00, 1500.00, 'available', 2, 1, 850],
        ['Oak Apartments', '456 Oak Avenue', 'Unit B', 'Los Angeles', 'CA', '90001', 'apartment', 'OAK-001', 2000.00, 2000.00, 'available', 3, 2, 1200],
        ['Pine Condos', '789 Pine Road', null, 'Chicago', 'IL', '60601', 'condo', 'PIN-001', 1800.00, 1800.00, 'occupied', 2, 2, 950],
    ];

    foreach ($properties as $prop) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO properties
            (name, address, unit, city, state, zip, type, listing_number, monthly_rent, deposit, status, bedrooms, bathrooms, sqft, description, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $prop[0],  // name
            $prop[1],  // address
            $prop[2],  // unit
            $prop[3],  // city
            $prop[4],  // state
            $prop[5],  // zip
            $prop[6],  // type
            $prop[7],  // listing_number
            $prop[8],  // monthly_rent
            $prop[9],  // deposit
            $prop[10], // status
            $prop[11], // bedrooms
            $prop[12], // bathrooms
            $prop[13], // sqft
            'Beautiful property with modern amenities and great location.',  // description
            $now       // created_at
        ]);
    }

    // Get property IDs
    $properties = $pdo->query("SELECT id FROM properties ORDER BY id")->fetchAll();
    $propertyIds = array_column($properties, 'id');

    // Seed renter record (linking renter1 to first property)
    if ($renterId && !empty($propertyIds)) {
        $moveInDate = date('Y-m-d', strtotime('2025-01-15'));
        $leaseEndDate = date('Y-m-d', strtotime('+1 year', strtotime($moveInDate)));

        $pdo->prepare("
            INSERT IGNORE INTO renters
            (user_id, property_id, move_in_date, lease_end, monthly_rent, security_deposit, status, emergency_contact, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ")->execute([$renterId, $propertyIds[0], $moveInDate, $leaseEndDate, 1500.00, 1500.00, 'active', 'Jane Renter (555-5678)', $now]);
    }

    // Seed sample payments
    if (!empty($propertyIds) && $renterId) {
        $renterData = $pdo->prepare("SELECT id FROM renters WHERE user_id = ? LIMIT 1");
        $renterData->execute([$renterId]);
        $renterRecord = $renterData->fetch();

        if ($renterRecord) {
            $renterRecordId = $renterRecord['id'];

            $payments = [
                [
                    'renter_id' => $renterRecordId,
                    'property_id' => $propertyIds[0],
                    'amount' => 1500.00,
                    'due_date' => date('Y-m-d', strtotime('2026-03-01')),
                    'paid_date' => date('Y-m-d', strtotime('2026-02-28')),
                    'method' => 'bank_transfer',
                    'status' => 'paid',
                    'period_from' => date('Y-m-d', strtotime('2026-03-01')),
                    'period_to' => date('Y-m-d', strtotime('2026-03-31')),
                    'receipt_number' => 'RCP-2026-03-001'
                ],
                [
                    'renter_id' => $renterRecordId,
                    'property_id' => $propertyIds[0],
                    'amount' => 1500.00,
                    'due_date' => date('Y-m-d', strtotime('2026-04-01')),
                    'paid_date' => null,
                    'method' => null,
                    'status' => 'pending',
                    'period_from' => date('Y-m-d', strtotime('2026-04-01')),
                    'period_to' => date('Y-m-d', strtotime('2026-04-30')),
                    'receipt_number' => null
                ]
            ];

            foreach ($payments as $payment) {
                $pdo->prepare("
                    INSERT IGNORE INTO payments
                    (renter_id, property_id, amount, due_date, paid_date, method, status, period_from, period_to, receipt_number, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ")->execute([
                    $payment['renter_id'],
                    $payment['property_id'],
                    $payment['amount'],
                    $payment['due_date'],
                    $payment['paid_date'],
                    $payment['method'],
                    $payment['status'],
                    $payment['period_from'],
                    $payment['period_to'],
                    $payment['receipt_number'],
                    $now
                ]);
            }
        }
    }

    // Seed sample maintenance requests (multiple categories for reports chart)
    if (!empty($propertyIds)) {
        $maintenanceRequests = [
            [
                'property_id' => $propertyIds[0],
                'title' => 'Leaky kitchen faucet',
                'description' => 'The kitchen faucet is dripping water continuously. Needs inspection and repair or replacement.',
                'category' => 'plumbing',
                'priority' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
                'estimated_cost' => 150.00,
                'actual_cost' => null
            ],
            [
                'property_id' => $propertyIds[0],
                'title' => 'Bathroom pipe burst',
                'description' => 'Bathroom pipe has a small leak near the joint. Water damage risk if not fixed soon.',
                'category' => 'plumbing',
                'priority' => 'high',
                'status' => 'in_progress',
                'assigned_to' => 'Premium Plumbing Co.',
                'estimated_cost' => 450.00,
                'actual_cost' => 380.00
            ],
            [
                'property_id' => $propertyIds[1],
                'title' => 'Living room outlet not working',
                'description' => 'Two outlets in the living room stopped working. Breaker reset did not help.',
                'category' => 'electrical',
                'priority' => 'high',
                'status' => 'completed',
                'assigned_to' => 'Electrical Experts LLC',
                'estimated_cost' => 200.00,
                'actual_cost' => 175.00
            ],
            [
                'property_id' => $propertyIds[2],
                'title' => 'Flickering lights in hallway',
                'description' => 'Hallway lights flicker intermittently. May be a wiring issue.',
                'category' => 'electrical',
                'priority' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
                'estimated_cost' => 120.00,
                'actual_cost' => null
            ],
            [
                'property_id' => $propertyIds[0],
                'title' => 'AC unit not cooling properly',
                'description' => 'Central AC is running but not cooling the apartment below 78°F. Needs inspection.',
                'category' => 'hvac',
                'priority' => 'high',
                'status' => 'in_progress',
                'assigned_to' => 'HVAC Care Services',
                'estimated_cost' => 600.00,
                'actual_cost' => null
            ],
            [
                'property_id' => $propertyIds[1],
                'title' => 'Heater making loud noise',
                'description' => 'The heating unit makes a banging noise when starting up. Possible blower motor issue.',
                'category' => 'hvac',
                'priority' => 'medium',
                'status' => 'completed',
                'assigned_to' => 'HVAC Care Services',
                'estimated_cost' => 350.00,
                'actual_cost' => 420.00
            ],
            [
                'property_id' => $propertyIds[2],
                'title' => 'Furnace filter replacement',
                'description' => 'Annual furnace filter replacement and system check needed.',
                'category' => 'hvac',
                'priority' => 'low',
                'status' => 'completed',
                'assigned_to' => 'HVAC Care Services',
                'estimated_cost' => 80.00,
                'actual_cost' => 95.00
            ],
            [
                'property_id' => $propertyIds[0],
                'title' => 'Dishwasher not draining',
                'description' => 'Dishwasher leaves standing water after cycle. Drain may be clogged.',
                'category' => 'appliances',
                'priority' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
                'estimated_cost' => 180.00,
                'actual_cost' => null
            ],
            [
                'property_id' => $propertyIds[1],
                'title' => 'Refrigerator making noise',
                'description' => 'Refrigerator compressor runs loudly. Food still cold but noise is disruptive.',
                'category' => 'appliances',
                'priority' => 'low',
                'status' => 'completed',
                'assigned_to' => 'ApplianceFix Pro',
                'estimated_cost' => 250.00,
                'actual_cost' => 200.00
            ],
            [
                'property_id' => $propertyIds[2],
                'title' => 'Crack in bedroom wall',
                'description' => 'A visible crack has appeared on the bedroom wall near the window. May be settling.',
                'category' => 'structural',
                'priority' => 'medium',
                'status' => 'open',
                'assigned_to' => null,
                'estimated_cost' => 300.00,
                'actual_cost' => null
            ],
            [
                'property_id' => $propertyIds[0],
                'title' => 'Ant infestation in kitchen',
                'description' => 'Small ants found in kitchen near the sink area. Need pest control treatment.',
                'category' => 'pest_control',
                'priority' => 'medium',
                'status' => 'completed',
                'assigned_to' => 'BugFree Pest Control',
                'estimated_cost' => 150.00,
                'actual_cost' => 130.00
            ],
            [
                'property_id' => $propertyIds[1],
                'title' => 'Mouse sighting in basement',
                'description' => 'Tenant reported seeing a mouse in the basement storage area.',
                'category' => 'pest_control',
                'priority' => 'high',
                'status' => 'in_progress',
                'assigned_to' => 'BugFree Pest Control',
                'estimated_cost' => 200.00,
                'actual_cost' => null
            ],
        ];

        foreach ($maintenanceRequests as $req) {
            $pdo->prepare("
                INSERT IGNORE INTO maintenance_requests
                (property_id, renter_id, title, description, category, priority, status, assigned_to, estimated_cost, actual_cost, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ")->execute([
                $req['property_id'],
                $renterId ? ($renterRecordId ?? null) : null,
                $req['title'],
                $req['description'],
                $req['category'],
                $req['priority'],
                $req['status'],
                $req['assigned_to'],
                $req['estimated_cost'],
                $req['actual_cost'],
                date('Y-m-d H:i:s', strtotime('-' . rand(1, 90) . ' days')),
                $now
            ]);
        }
    }

    // Seed sample applications
    if (!empty($propertyIds)) {
        $applications = [
            [
                'property_id' => $propertyIds[1],
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'email' => 'michael.johnson@example.com',
                'phone' => '555-2345',
                'employment' => 'Software Engineer at TechCorp',
                'monthly_income' => 6500.00,
                'credit_score' => 750,
                'desired_move_in' => date('Y-m-d', strtotime('+30 days')),
                'lease_term' => 12,
                'source' => 'website',
                'status' => 'submitted'
            ],
            [
                'property_id' => $propertyIds[2],
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah.williams@example.com',
                'phone' => '555-3456',
                'employment' => 'Marketing Manager at GlobalBrand',
                'monthly_income' => 5800.00,
                'credit_score' => 720,
                'desired_move_in' => date('Y-m-d', strtotime('+45 days')),
                'lease_term' => 24,
                'source' => 'referral',
                'status' => 'under_review'
            ]
        ];

        foreach ($applications as $app) {
            $pdo->prepare("
                INSERT IGNORE INTO applications
                (property_id, first_name, last_name, email, phone, employment, monthly_income, credit_score, desired_move_in, lease_term, source, status, submitted_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ")->execute([
                $app['property_id'],
                $app['first_name'],
                $app['last_name'],
                $app['email'],
                $app['phone'],
                $app['employment'],
                $app['monthly_income'],
                $app['credit_score'],
                $app['desired_move_in'],
                $app['lease_term'],
                $app['source'],
                $app['status'],
                $now
            ]);
        }
    }

    // Seed default user settings for test renter
    if ($renterId) {
        $defaultUserSettings = [
            ['email_notifications', '1'],
            ['sms_notifications', '0'],
            ['payment_reminders', '1'],
            ['maintenance_updates', '1'],
            ['newsletter', '0'],
            ['marketing', '0'],
            ['show_profile', '1'],
            ['show_phone', '0'],
            ['show_email', '0'],
            ['allow_data_collection', '0'],
            ['language', 'en'],
            ['timezone', 'America/Denver'],
            ['date_format', 'MM/DD/YYYY']
        ];

        foreach ($defaultUserSettings as $us) {
            $pdo->prepare("
                INSERT INTO user_settings (user_id, setting_key, setting_value, updated_at)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = VALUES(updated_at)
            ")->execute([$renterId, $us[0], $us[1], $now]);
        }

        // Seed a sample support request
        $pdo->prepare("
            INSERT IGNORE INTO support_requests (user_id, subject, category, message, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ")->execute([
            $renterId,
            'Question about lease renewal',
            'Lease & Documents',
            'I would like to know the process for renewing my lease. My current lease ends in January 2027. Can you please guide me on the next steps?',
            'in_progress',
            date('Y-m-d H:i:s', strtotime('-3 days')),
            $now
        ]);
    }

    // Seed notifications for test renter
    if ($renterId) {
        $notifications = [
            [
                'type' => 'payment',
                'icon' => 'bell',
                'title' => 'Rent Reminder',
                'message' => 'April rent is due in 5 days. Amount: $1,500.00',
                'is_read' => 0,
                'link' => '/renter/portal?tab=payments',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'type' => 'maintenance',
                'icon' => 'wrench',
                'title' => 'Maintenance Update',
                'message' => 'Your request for kitchen faucet has been scheduled',
                'is_read' => 0,
                'link' => '/renter/portal?tab=maintenance',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'type' => 'message',
                'icon' => 'envelope',
                'title' => 'New Message',
                'message' => 'You have a new message from property management',
                'is_read' => 0,
                'link' => '/renter/portal?tab=messages',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'type' => 'payment',
                'icon' => 'check-circle',
                'title' => 'Payment Confirmed',
                'message' => 'Your March rent payment of $1,500.00 has been received. Receipt: RCP-2026-03-001',
                'is_read' => 1,
                'link' => '/renter/portal?tab=payments',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            [
                'type' => 'info',
                'icon' => 'info-circle',
                'title' => 'Welcome to Portal',
                'message' => 'Welcome to the SOTELO Management Renter Portal. Explore your dashboard to manage payments and maintenance requests.',
                'is_read' => 1,
                'link' => '/renter/portal',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
            ]
        ];

        foreach ($notifications as $notif) {
            $pdo->prepare("
                INSERT IGNORE INTO notifications (user_id, type, icon, title, message, is_read, link, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ")->execute([
                $renterId,
                $notif['type'],
                $notif['icon'],
                $notif['title'],
                $notif['message'],
                $notif['is_read'],
                $notif['link'],
                $notif['created_at']
            ]);
        }
    }

    // Seed default documents for test renter (property 1)
    if ($renterId && !empty($propertyIds) && isset($renterRecordId)) {
        $defaultDocs = [
            ['Lease Agreement', 'lease', 'lease_agreement.pdf', 'uploads/documents/lease_agreement.pdf', 'application/pdf'],
            ['Property Rules', 'rules', 'property_rules.pdf', 'uploads/documents/property_rules.pdf', 'application/pdf'],
            ['Move-in Inspection Report', 'inspection', 'move_in_inspection.pdf', 'uploads/documents/move_in_inspection.pdf', 'application/pdf'],
        ];

        foreach ($defaultDocs as $doc) {
            $pdo->prepare("
                INSERT IGNORE INTO documents
                (renter_id, property_id, user_id, title, type, file_name, file_path, file_size, mime_type, uploaded_by, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ")->execute([
                $renterRecordId,
                $propertyIds[0],
                $renterId,
                $doc[0],
                $doc[1],
                $doc[2],
                $doc[3],
                0,
                $doc[4],
                'admin',
                date('Y-m-d H:i:s', strtotime('-30 days')),
                $now
            ]);
        }
    }

    // Seed documents for all properties (admin-uploaded)
    if (!empty($propertyIds) && $adminId) {
        $propertyDocs = [
            // Property 1 - Maple Residency (already has renter docs above, add more)
            [$propertyIds[0], 'Property Insurance', 'insurance', 'maple_insurance_2024.pdf', 'uploads/documents/property_1/maple_insurance_2024.pdf', 245000, 'application/pdf'],
            [$propertyIds[0], 'Tax Assessment 2024', 'tax', 'maple_tax_2024.pdf', 'uploads/documents/property_1/maple_tax_2024.pdf', 180000, 'application/pdf'],
            // Property 2 - Oak Apartments
            [$propertyIds[1], 'Lease Agreement', 'lease', 'oak_lease_agreement.pdf', 'uploads/documents/property_2/oak_lease_agreement.pdf', 320000, 'application/pdf'],
            [$propertyIds[1], 'Inspection Report', 'inspection', 'oak_inspection_2024.pdf', 'uploads/documents/property_2/oak_inspection_2024.pdf', 156000, 'application/pdf'],
            [$propertyIds[1], 'Property Insurance', 'insurance', 'oak_insurance_2024.pdf', 'uploads/documents/property_2/oak_insurance_2024.pdf', 210000, 'application/pdf'],
            // Property 3 - Pine Condos
            [$propertyIds[2], 'Lease Agreement', 'lease', 'pine_lease_agreement.pdf', 'uploads/documents/property_3/pine_lease_agreement.pdf', 290000, 'application/pdf'],
            [$propertyIds[2], 'Inspection Report', 'inspection', 'pine_inspection_2024.pdf', 'uploads/documents/property_3/pine_inspection_2024.pdf', 175000, 'application/pdf'],
            [$propertyIds[2], 'Property Insurance', 'insurance', 'pine_insurance_2024.pdf', 'uploads/documents/property_3/pine_insurance_2024.pdf', 198000, 'application/pdf'],
            [$propertyIds[2], 'Maintenance Record', 'maintenance', 'pine_maintenance_log.pdf', 'uploads/documents/property_3/pine_maintenance_log.pdf', 89000, 'application/pdf'],
        ];

        foreach ($propertyDocs as $doc) {
            $pdo->prepare("
                INSERT IGNORE INTO documents
                (renter_id, property_id, user_id, title, type, file_name, file_path, file_size, mime_type, uploaded_by, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ")->execute([
                null,
                $doc[0],
                $adminId,
                $doc[1],
                $doc[2],
                $doc[3],
                $doc[4],
                $doc[5],
                $doc[6],
                'admin',
                date('Y-m-d H:i:s', strtotime('-' . rand(5, 60) . ' days')),
                $now
            ]);
        }
    }

    // Seed default settings
    $settings = [
        ['company_name', 'Sotelo Management'],
        ['company_email', 'info@sotelomanagement.com'],
        ['company_phone', '1-800-SOTELO-1'],
        ['late_fee', '5.00']
    ];

    foreach ($settings as $setting) {
        $pdo->prepare("
            INSERT INTO settings (key_name, value, updated_at)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = VALUES(updated_at)
        ")->execute([$setting[0], $setting[1], $now]);
    }

    echo "<div style='font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5;'>";
    echo "<h2 style='color: #27ae60;'>Database Initialization Complete!</h2>";
    echo "<div style='background: white; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>Admin Credentials</h3>";
    echo "<p><strong>Username:</strong> admin</p>";
    echo "<p><strong>Email:</strong> admin@sotelomanagement.com</p>";
    echo "<p><strong>Password:</strong> password</p>";
    echo "</div>";
    echo "<div style='background: white; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>Sample Renter Credentials</h3>";
    echo "<p><strong>Username:</strong> test</p>";
    echo "<p><strong>Email:</strong> test@sotelomanagement.com</p>";
    echo "<p><strong>Password:</strong> password</p>";
    echo "</div>";
    echo "<div style='background: white; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>Sample Data Created</h3>";
    echo "<ul>";
    echo "<li>3 Properties (Maple Residency, Oak Apartments, Pine Condos)</li>";
    echo "<li>1 Renter record linked to Maple Residency</li>";
    echo "<li>2 Sample payments (1 paid, 1 pending)</li>";
    echo "<li>1 Maintenance request</li>";
    echo "<li>2 Applications (submitted and under review)</li>";
    echo "<li>Default company settings</li>";
    echo "<li>Default user preferences for test renter</li>";
    echo "<li>1 Sample support request</li>";
    echo "</ul>";
    echo "</div>";
    echo "<p style='color: #666;'>Redirecting to home page in 3 seconds...</p>";
    echo "</div>";
    echo "<script>
        setTimeout(function() {
            window.location.href = '/';
        }, 3000);
    </script>";

} catch (PDOException $e) {
    echo "<div style='font-family: Arial, sans-serif; padding: 20px; background: #ffebee;'>";
    echo "<h2 style='color: #c62828;'>Database Initialization Error!</h2>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p style='color: #666; margin-top: 20px;'><a href='/'>Return to home</a></p>";
    echo "</div>";
    exit;
}
