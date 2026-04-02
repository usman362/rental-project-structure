<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/Setting.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Core/CSRF.php';

class SettingController extends Controller
{
    /**
     * Display settings page with all configuration options
     */
    public function index(): void
    {
        // Load all settings from database
        $allSettings = Setting::all();

        // Organize settings by group
        $settings = [
            'company_name' => $allSettings['company_name'] ?? '',
            'company_email' => $allSettings['company_email'] ?? '',
            'company_phone' => $allSettings['company_phone'] ?? '',
            'company_website' => $allSettings['company_website'] ?? '',
            'company_address' => $allSettings['company_address'] ?? '',
            'company_city' => $allSettings['company_city'] ?? '',
            'company_state' => $allSettings['company_state'] ?? '',
            'company_zip' => $allSettings['company_zip'] ?? '',
            'late_fee_percent' => $allSettings['late_fee_percent'] ?? '5',
            'grace_period_days' => $allSettings['grace_period_days'] ?? '3',
            'payment_methods' => $allSettings['payment_methods'] ?? 'bank,card,check',
            'bank_name' => $allSettings['bank_name'] ?? '',
            'bank_account' => $allSettings['bank_account'] ?? '',
            'bank_routing' => $allSettings['bank_routing'] ?? '',
            'email_payment_reminders' => $allSettings['email_payment_reminders'] ?? '1',
            'email_maintenance_updates' => $allSettings['email_maintenance_updates'] ?? '1',
            'email_lease_expiry' => $allSettings['email_lease_expiry'] ?? '1',
            'email_new_applications' => $allSettings['email_new_applications'] ?? '1',
            'password_min_length' => $allSettings['password_min_length'] ?? '8',
            'session_timeout_minutes' => $allSettings['session_timeout_minutes'] ?? '30',
            'two_factor_enabled' => $allSettings['two_factor_enabled'] ?? '0',
            'max_login_attempts' => $allSettings['max_login_attempts'] ?? '5',
            // Integration settings
            'integration_quickbooks' => $allSettings['integration_quickbooks'] ?? '0',
            'integration_google_analytics' => $allSettings['integration_google_analytics'] ?? '0',
            'integration_google_maps' => $allSettings['integration_google_maps'] ?? '1',
            'integration_dropbox' => $allSettings['integration_dropbox'] ?? '0',
            'integration_twilio' => $allSettings['integration_twilio'] ?? '0',
            'integration_google_calendar' => $allSettings['integration_google_calendar'] ?? '0',
            // Backup settings
            'backup_frequency' => $allSettings['backup_frequency'] ?? 'daily',
            'backup_retention_days' => $allSettings['backup_retention_days'] ?? '90',
            'backup_include_media' => $allSettings['backup_include_media'] ?? '1',
            'backup_auto_delete' => $allSettings['backup_auto_delete'] ?? '0',
        ];

        // Get all users for User Management tab
        $users = User::all();

        // Get flash messages
        $successMessage = flash('success');
        $errorMessage = flash('error');

        // Pass to view
        $this->view('admin.settings', [
            'settings' => $settings,
            'users' => $users,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'title' => 'Settings',
            'active' => 'settings',
            'user' => auth()
        ]);
    }

    /**
     * Handle settings update (POST request)
     */
    public function update(): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'Invalid security token. Please try again.');
            $this->redirect(route('admin.settings'));
            return;
        }

        try {
            // Company Profile Settings
            $companyFields = ['company_name', 'company_email', 'company_phone', 'company_website',
                              'company_address', 'company_city', 'company_state', 'company_zip'];
            foreach ($companyFields as $field) {
                if (isset($_POST[$field])) {
                    Setting::set($field, (string) $_POST[$field]);
                }
            }

            // Payment Settings
            $paymentFields = ['late_fee_percent', 'grace_period_days', 'payment_methods',
                              'bank_name', 'bank_account', 'bank_routing'];
            foreach ($paymentFields as $field) {
                if (isset($_POST[$field])) {
                    Setting::set($field, (string) $_POST[$field]);
                }
            }

            // Email & Notifications Settings (checkboxes)
            $emailCheckboxes = ['email_payment_reminders', 'email_maintenance_updates',
                                'email_lease_expiry', 'email_new_applications'];
            foreach ($emailCheckboxes as $field) {
                Setting::set($field, isset($_POST[$field]) ? '1' : '0');
            }

            // Security Settings
            $securityFields = ['password_min_length', 'session_timeout_minutes', 'max_login_attempts'];
            foreach ($securityFields as $field) {
                if (isset($_POST[$field])) {
                    Setting::set($field, (string) $_POST[$field]);
                }
            }
            Setting::set('two_factor_enabled', isset($_POST['two_factor_enabled']) ? '1' : '0');

            // Integration settings (checkboxes)
            $integrationFields = ['integration_quickbooks', 'integration_google_analytics',
                                  'integration_google_maps', 'integration_dropbox',
                                  'integration_twilio', 'integration_google_calendar'];
            foreach ($integrationFields as $field) {
                Setting::set($field, isset($_POST[$field]) ? '1' : '0');
            }

            // Backup settings
            if (isset($_POST['backup_frequency'])) {
                Setting::set('backup_frequency', (string) $_POST['backup_frequency']);
            }
            if (isset($_POST['backup_retention_days'])) {
                Setting::set('backup_retention_days', (string) $_POST['backup_retention_days']);
            }
            Setting::set('backup_include_media', isset($_POST['backup_include_media']) ? '1' : '0');
            Setting::set('backup_auto_delete', isset($_POST['backup_auto_delete']) ? '1' : '0');

            flash('success', 'Settings updated successfully!');
        } catch (Exception $e) {
            flash('error', 'Error updating settings: ' . $e->getMessage());
        }

        $this->redirect(route('admin.settings'));
    }

    /**
     * Add a new user
     */
    public function addUser(): void
    {
        if (!CSRF::verify()) {
            flash('error', 'Invalid security token.');
            $this->redirect(route('admin.settings'));
            return;
        }

        try {
            $firstName = trim((string) ($_POST['first_name'] ?? ''));
            $lastName = trim((string) ($_POST['last_name'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $role = (string) ($_POST['role'] ?? 'renter');
            $password = (string) ($_POST['password'] ?? '');

            if (empty($firstName) || empty($email) || empty($password)) {
                flash('error', 'Full name, email, and password are required.');
                $this->redirect(route('admin.settings'));
                return;
            }

            // Check if email already exists
            $existing = User::findByEmail($email);
            if ($existing) {
                flash('error', 'A user with this email already exists.');
                $this->redirect(route('admin.settings'));
                return;
            }

            // Generate username from email
            $username = explode('@', $email)[0];
            $existingUsername = User::findByUsername($username);
            if ($existingUsername) {
                $username .= rand(100, 999);
            }

            User::create([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => (string) ($_POST['phone'] ?? ''),
                'role' => $role
            ]);

            flash('success', "User '{$firstName} {$lastName}' has been added successfully.");
        } catch (Exception $e) {
            flash('error', 'Error adding user: ' . $e->getMessage());
        }

        $this->redirect(route('admin.settings'));
    }

    /**
     * Update an existing user
     */
    public function updateUser(): void
    {
        if (!CSRF::verify()) {
            flash('error', 'Invalid security token.');
            $this->redirect(route('admin.settings'));
            return;
        }

        try {
            $id = (int) ($_POST['user_id'] ?? 0);
            if ($id <= 0) {
                flash('error', 'Invalid user ID.');
                $this->redirect(route('admin.settings'));
                return;
            }

            $data = [];
            if (!empty($_POST['first_name'])) $data['first_name'] = trim((string) $_POST['first_name']);
            if (!empty($_POST['last_name'])) $data['last_name'] = trim((string) $_POST['last_name']);
            if (!empty($_POST['email'])) $data['email'] = trim((string) $_POST['email']);
            if (!empty($_POST['role'])) $data['role'] = (string) $_POST['role'];
            if (!empty($_POST['password'])) $data['password'] = (string) $_POST['password'];

            User::update($id, $data);
            flash('success', 'User updated successfully.');
        } catch (Exception $e) {
            flash('error', 'Error updating user: ' . $e->getMessage());
        }

        $this->redirect(route('admin.settings'));
    }

    /**
     * Delete a user
     */
    public function deleteUser(): void
    {
        if (!CSRF::verify()) {
            flash('error', 'Invalid security token.');
            $this->redirect(route('admin.settings'));
            return;
        }

        try {
            $id = (int) ($_POST['user_id'] ?? 0);
            $currentUser = auth();

            if ($id <= 0) {
                flash('error', 'Invalid user ID.');
                $this->redirect(route('admin.settings'));
                return;
            }

            // Prevent self-deletion
            if ($currentUser && (int) $currentUser['id'] === $id) {
                flash('error', 'You cannot delete your own account.');
                $this->redirect(route('admin.settings'));
                return;
            }

            User::delete($id);
            flash('success', 'User has been removed successfully.');
        } catch (Exception $e) {
            flash('error', 'Error deleting user: ' . $e->getMessage());
        }

        $this->redirect(route('admin.settings'));
    }

    /**
     * Export all data as CSV backup
     */
    public function exportBackup(): void
    {
        try {
            $pdo = Database::getInstance();
            $tables = ['users', 'properties', 'renters', 'payments', 'maintenance_requests',
                       'applications', 'notifications', 'documents', 'settings', 'user_settings'];

            $csvContent = '';

            foreach ($tables as $table) {
                $stmt = $pdo->query("SELECT * FROM {$table}");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $csvContent .= "--- TABLE: {$table} ---\n";

                if (!empty($rows)) {
                    // Headers
                    $csvContent .= implode(',', array_keys($rows[0])) . "\n";
                    // Data
                    foreach ($rows as $row) {
                        $escaped = array_map(function($val) {
                            if ($val === null) return '';
                            $val = str_replace('"', '""', (string) $val);
                            return '"' . $val . '"';
                        }, $row);
                        $csvContent .= implode(',', $escaped) . "\n";
                    }
                } else {
                    $csvContent .= "(empty)\n";
                }
                $csvContent .= "\n";
            }

            $filename = 'backup_' . date('Y_m_d_His') . '.csv';

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($csvContent));
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            echo $csvContent;
            exit;
        } catch (Exception $e) {
            flash('error', 'Error creating backup: ' . $e->getMessage());
            $this->redirect(route('admin.settings'));
        }
    }
}
