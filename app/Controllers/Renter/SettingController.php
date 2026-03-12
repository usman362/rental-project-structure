<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Models/Setting.php';

class SettingController extends Controller
{
    /**
     * Display the settings page
     */
    public function index(): void
    {
        // Get authenticated user
        $user = auth();
        if (!$user) {
            $this->redirect(route('auth.login'));
            return;
        }

        // Load user preferences/settings from session or defaults
        $settings = [
            'email_notifications' => $_SESSION['settings']['email_notifications'] ?? true,
            'sms_notifications' => $_SESSION['settings']['sms_notifications'] ?? false,
            'payment_reminders' => $_SESSION['settings']['payment_reminders'] ?? true,
            'maintenance_updates' => $_SESSION['settings']['maintenance_updates'] ?? true,
            'newsletter' => $_SESSION['settings']['newsletter'] ?? false,
            'marketing' => $_SESSION['settings']['marketing'] ?? false,
            'show_profile' => $_SESSION['settings']['show_profile'] ?? true,
            'show_phone' => $_SESSION['settings']['show_phone'] ?? false,
            'show_email' => $_SESSION['settings']['show_email'] ?? false,
            'allow_data_collection' => $_SESSION['settings']['allow_data_collection'] ?? false,
            'language' => $_SESSION['settings']['language'] ?? 'en',
            'timezone' => $_SESSION['settings']['timezone'] ?? 'America/Denver',
            'date_format' => $_SESSION['settings']['date_format'] ?? 'MM/DD/YYYY'
        ];

        // Get flash messages
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        // Pass data to view
        $this->view('renter.settings', [
            'user' => $user,
            'settings' => $settings,
            'flash' => $flash,
            'title' => 'Settings',
            'active' => 'settings'
        ]);
    }

    /**
     * Update the settings
     */
    public function update(): void
    {
        // Verify CSRF token
        $csrf = $_POST['_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
            flash('error', 'Invalid CSRF token');
            $this->back();
            return;
        }

        // Get authenticated user
        $user = auth();
        if (!$user) {
            flash('error', 'Unauthorized');
            $this->redirect(route('auth.login'));
            return;
        }

        // Collect notification preferences
        $settings = [
            'email_notifications' => isset($_POST['email_notifications']),
            'sms_notifications' => isset($_POST['sms_notifications']),
            'payment_reminders' => isset($_POST['payment_reminders']),
            'maintenance_updates' => isset($_POST['maintenance_updates']),
            'newsletter' => isset($_POST['newsletter']),
            'marketing' => isset($_POST['marketing']),
            'show_profile' => isset($_POST['show_profile']),
            'show_phone' => isset($_POST['show_phone']),
            'show_email' => isset($_POST['show_email']),
            'allow_data_collection' => isset($_POST['allow_data_collection']),
            'language' => trim($_POST['language'] ?? 'en'),
            'timezone' => trim($_POST['timezone'] ?? 'America/Denver'),
            'date_format' => trim($_POST['date_format'] ?? 'MM/DD/YYYY')
        ];

        // Validate language
        $allowedLanguages = ['en', 'es', 'fr', 'de'];
        if (!in_array($settings['language'], $allowedLanguages)) {
            $settings['language'] = 'en';
        }

        // Validate timezone
        $allowedTimezones = timezone_identifiers_list();
        if (!in_array($settings['timezone'], $allowedTimezones)) {
            $settings['timezone'] = 'America/Denver';
        }

        // Validate date format
        $allowedFormats = ['MM/DD/YYYY', 'DD/MM/YYYY', 'YYYY-MM-DD'];
        if (!in_array($settings['date_format'], $allowedFormats)) {
            $settings['date_format'] = 'MM/DD/YYYY';
        }

        // Store settings in session (in production, save to database)
        $_SESSION['settings'] = $settings;

        // Flash success message
        flash('success', 'Settings updated successfully');
        $this->back();
    }
}
