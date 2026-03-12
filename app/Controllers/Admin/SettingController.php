<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/Setting.php';
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
            'max_login_attempts' => $allSettings['max_login_attempts'] ?? '5'
        ];

        // Get flash messages
        $successMessage = flash('success');
        $errorMessage = flash('error');

        // Pass to view
        $this->view('admin.settings', [
            'settings' => $settings,
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
            if (isset($_POST['company_name'])) {
                Setting::set('company_name', (string) $_POST['company_name']);
            }
            if (isset($_POST['company_email'])) {
                Setting::set('company_email', (string) $_POST['company_email']);
            }
            if (isset($_POST['company_phone'])) {
                Setting::set('company_phone', (string) $_POST['company_phone']);
            }
            if (isset($_POST['company_address'])) {
                Setting::set('company_address', (string) $_POST['company_address']);
            }
            if (isset($_POST['company_city'])) {
                Setting::set('company_city', (string) $_POST['company_city']);
            }
            if (isset($_POST['company_state'])) {
                Setting::set('company_state', (string) $_POST['company_state']);
            }
            if (isset($_POST['company_zip'])) {
                Setting::set('company_zip', (string) $_POST['company_zip']);
            }

            // Payment Settings
            if (isset($_POST['late_fee_percent'])) {
                Setting::set('late_fee_percent', (string) $_POST['late_fee_percent']);
            }
            if (isset($_POST['grace_period_days'])) {
                Setting::set('grace_period_days', (string) $_POST['grace_period_days']);
            }
            if (isset($_POST['payment_methods'])) {
                Setting::set('payment_methods', (string) $_POST['payment_methods']);
            }
            if (isset($_POST['bank_name'])) {
                Setting::set('bank_name', (string) $_POST['bank_name']);
            }
            if (isset($_POST['bank_account'])) {
                Setting::set('bank_account', (string) $_POST['bank_account']);
            }
            if (isset($_POST['bank_routing'])) {
                Setting::set('bank_routing', (string) $_POST['bank_routing']);
            }

            // Email & Notifications Settings
            Setting::set('email_payment_reminders', isset($_POST['email_payment_reminders']) ? '1' : '0');
            Setting::set('email_maintenance_updates', isset($_POST['email_maintenance_updates']) ? '1' : '0');
            Setting::set('email_lease_expiry', isset($_POST['email_lease_expiry']) ? '1' : '0');
            Setting::set('email_new_applications', isset($_POST['email_new_applications']) ? '1' : '0');

            // Security Settings
            if (isset($_POST['password_min_length'])) {
                Setting::set('password_min_length', (string) $_POST['password_min_length']);
            }
            if (isset($_POST['session_timeout_minutes'])) {
                Setting::set('session_timeout_minutes', (string) $_POST['session_timeout_minutes']);
            }
            Setting::set('two_factor_enabled', isset($_POST['two_factor_enabled']) ? '1' : '0');
            if (isset($_POST['max_login_attempts'])) {
                Setting::set('max_login_attempts', (string) $_POST['max_login_attempts']);
            }

            flash('success', 'Settings updated successfully!');
        } catch (Exception $e) {
            flash('error', 'Error updating settings: ' . $e->getMessage());
        }

        $this->redirect(route('admin.settings'));
    }
}
