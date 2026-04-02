<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Models/UserSetting.php';

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

        // Load user preferences/settings from database
        $settings = UserSetting::allForUser((int)$user['id']);

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
        if (!CSRF::verify()) {
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

        // Save settings to database
        UserSetting::saveForUser((int)$user['id'], $settings);

        // Flash success message
        flash('success', 'Settings updated successfully');
        $this->back();
    }
}
