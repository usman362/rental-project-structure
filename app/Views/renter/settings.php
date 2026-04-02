<?php
$title = 'Settings';
$active = 'settings';
ob_start();
?>

<style>
    /* Settings Header */
    .settings-header h1 {
        color: #2c5aa0;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    /* Settings Tabs */
    .settings-tabs {
        display: flex;
        border-bottom: 1px solid #eee;
        margin-bottom: 2rem;
    }

    .settings-tab {
        padding: 12px 24px;
        background: none;
        border: none;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        position: relative;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .settings-tab:hover {
        color: #2c5aa0;
    }

    .settings-tab.active {
        color: #2c5aa0;
    }

    .settings-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #2c5aa0;
        border-radius: 3px 3px 0 0;
    }

    /* Form Sections */
    .form-section {
        background-color: #f9f9f9;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-section h2 {
        margin-bottom: 1.5rem;
        color: #2c5aa0;
    }

    /* Toggle Item Row */
    .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 0;
        border-bottom: 1px solid #eee;
    }

    .setting-item:last-child {
        border-bottom: none;
    }

    .setting-item h4 {
        color: #333;
        margin-bottom: 5px;
        font-size: 15px;
    }

    .setting-item p {
        color: #666;
        font-size: 14px;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: #2c5aa0;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(30px);
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #444;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group select,
    .form-group input {
        width: 100%;
        max-width: 300px;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: border 0.3s, box-shadow 0.3s;
        font-family: 'Inter', system-ui, sans-serif;
    }

    .form-group select:focus,
    .form-group input:focus {
        border-color: #2c5aa0;
        outline: none;
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }

    /* Radio Group */
    .radio-group {
        display: flex;
        gap: 2rem;
        margin-top: 0.5rem;
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 15px;
        color: #444;
    }

    .radio-label input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #2c5aa0;
        cursor: pointer;
    }

    /* Buttons */
    .btn-primary {
        background-color: #2c5aa0;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background-color: #1d4a8a;
    }

    .btn-danger-outline {
        background-color: transparent;
        color: #e74c3c;
        border: 1px solid #e74c3c;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-danger-outline:hover {
        background-color: #e74c3c;
        color: white;
    }

    /* Settings Content */
    .settings-content {
        display: none;
    }

    .settings-content.active {
        display: block;
    }

    /* Danger Box */
    .danger-box {
        background-color: #fff5f5;
        border: 1px solid #ffebee;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .danger-box h3 {
        margin-bottom: 1rem;
        color: #e74c3c;
    }

    .danger-box p {
        color: #666;
        margin-bottom: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .settings-tabs {
            flex-wrap: wrap;
        }

        .settings-tab {
            flex: 1;
            min-width: 120px;
            text-align: center;
            justify-content: center;
        }

        .setting-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .radio-group {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<!-- Settings Header -->
<div class="settings-header">
    <h1>Settings</h1>
</div>

<!-- Settings Tabs -->
<div class="settings-tabs">
    <button class="settings-tab active" data-tab="notifications">
        <i class="fas fa-bell"></i> Notifications
    </button>
    <button class="settings-tab" data-tab="privacy">
        <i class="fas fa-lock"></i> Privacy
    </button>
    <button class="settings-tab" data-tab="language">
        <i class="fas fa-globe"></i> Language & Region
    </button>
    <button class="settings-tab" data-tab="data">
        <i class="fas fa-database"></i> Data Management
    </button>
</div>

<form id="settingsForm" method="POST" action="<?= route('renter.settings') ?>">
    <?= csrf_field() ?>

    <!-- Notifications Tab -->
    <div id="notifications" class="settings-content active">
        <div class="form-section">
            <h2>Notification Preferences</h2>

            <div class="setting-item">
                <div>
                    <h4>Email Notifications</h4>
                    <p>Receive important updates via email</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="email_notifications" <?= !empty($settings['email_notifications']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div>
                    <h4>SMS Notifications</h4>
                    <p>Get text message alerts for urgent matters</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="sms_notifications" <?= !empty($settings['sms_notifications']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div>
                    <h4>Payment Reminders</h4>
                    <p>Remind me when rent payments are due</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="payment_reminders" <?= !empty($settings['payment_reminders']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div>
                    <h4>Maintenance Updates</h4>
                    <p>Notify me about maintenance requests and status</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="maintenance_updates" <?= !empty($settings['maintenance_updates']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div>
                    <h4>Newsletter</h4>
                    <p>Subscribe to our monthly newsletter</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="newsletter" <?= !empty($settings['newsletter']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div>
                    <h4>Marketing Communications</h4>
                    <p>Receive promotions and special offers</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="marketing" <?= !empty($settings['marketing']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Privacy Tab -->
    <div id="privacy" class="settings-content">
        <div class="form-section">
            <h2>Privacy Settings</h2>

            <div class="setting-item">
                <div>
                    <h4>Show Profile to Management</h4>
                    <p>Allow property manager to see your profile</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_profile" <?= !empty($settings['show_profile']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div>
                    <h4>Show Phone Number</h4>
                    <p>Display your phone number in your profile</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_phone" <?= !empty($settings['show_phone']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div>
                    <h4>Show Email Address</h4>
                    <p>Display your email address in your profile</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_email" <?= !empty($settings['show_email']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div>
                    <h4>Allow Data Collection</h4>
                    <p>Help us improve by allowing usage analytics</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="allow_data_collection" <?= !empty($settings['allow_data_collection']) ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Language & Region Tab -->
    <div id="language" class="settings-content">
        <div class="form-section">
            <h2>Language & Region</h2>

            <div class="form-group">
                <label for="language-select">Language</label>
                <select id="language-select" name="language">
                    <option value="en" <?= ($settings['language'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
                    <option value="es" <?= ($settings['language'] ?? '') === 'es' ? 'selected' : '' ?>>Spanish</option>
                    <option value="fr" <?= ($settings['language'] ?? '') === 'fr' ? 'selected' : '' ?>>French</option>
                    <option value="de" <?= ($settings['language'] ?? '') === 'de' ? 'selected' : '' ?>>German</option>
                </select>
            </div>

            <div class="form-group">
                <label for="timezone-select">Timezone</label>
                <select id="timezone-select" name="timezone">
                    <option value="America/Denver" <?= ($settings['timezone'] ?? '') === 'America/Denver' ? 'selected' : '' ?>>Mountain Time</option>
                    <option value="America/Chicago" <?= ($settings['timezone'] ?? '') === 'America/Chicago' ? 'selected' : '' ?>>Central Time</option>
                    <option value="America/New_York" <?= ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>Eastern Time</option>
                    <option value="America/Los_Angeles" <?= ($settings['timezone'] ?? '') === 'America/Los_Angeles' ? 'selected' : '' ?>>Pacific Time</option>
                    <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date Format</label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="date_format" value="MM/DD/YYYY" <?= ($settings['date_format'] ?? 'MM/DD/YYYY') === 'MM/DD/YYYY' ? 'checked' : '' ?>>
                        <span>MM/DD/YYYY</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="date_format" value="DD/MM/YYYY" <?= ($settings['date_format'] ?? '') === 'DD/MM/YYYY' ? 'checked' : '' ?>>
                        <span>DD/MM/YYYY</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="date_format" value="YYYY-MM-DD" <?= ($settings['date_format'] ?? '') === 'YYYY-MM-DD' ? 'checked' : '' ?>>
                        <span>YYYY-MM-DD</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Management Tab -->
    <div id="data" class="settings-content">
        <div class="form-section">
            <h2>Data Management</h2>

            <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #ddd;">
                <h3 style="margin-bottom: 1rem; color: #333;">Download Your Data</h3>
                <p style="color: #666; margin-bottom: 1rem;">Download a copy of all your personal data in a portable format.</p>
                <div class="form-group">
                    <label>Export Format</label>
                    <select>
                        <option>JSON</option>
                        <option>CSV</option>
                        <option>PDF</option>
                    </select>
                </div>
                <button type="button" class="btn-primary" onclick="downloadData()">
                    <i class="fas fa-download"></i> Download My Data
                </button>
            </div>

            <div class="danger-box">
                <h3>Request Data Deletion</h3>
                <p>Request a permanent deletion of your account and all associated data. This action cannot be undone.</p>
                <button type="button" class="btn-danger-outline" onclick="requestDeletion()">
                    <i class="fas fa-trash-alt"></i> Request Data Deletion
                </button>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="margin-top: 2rem;">
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </div>
</form>

<script>
// Tab switching
document.addEventListener('DOMContentLoaded', function() {
    var tabs = document.querySelectorAll('.settings-tab');
    var contents = document.querySelectorAll('.settings-content');

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            var targetTab = this.getAttribute('data-tab');

            // Remove active from all
            tabs.forEach(function(t) { t.classList.remove('active'); });
            contents.forEach(function(c) { c.classList.remove('active'); });

            // Activate clicked
            this.classList.add('active');
            var target = document.getElementById(targetTab);
            if (target) target.classList.add('active');
        });
    });
});

function downloadData() {
    Swal.fire({
        title: 'Preparing Export...',
        html: '<i class="fas fa-download fa-3x" style="color:#2c5aa0; margin-bottom:1rem;"></i><br>Your data export is being prepared.',
        didOpen: function() { Swal.showLoading(); },
        allowOutsideClick: false,
        timer: 2000,
        timerProgressBar: true
    }).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Export Ready!',
            text: 'You will receive a download link via email shortly.',
            confirmButtonColor: '#2c5aa0'
        });
    });
}

function requestDeletion() {
    Swal.fire({
        title: 'Request Data Deletion',
        html: '<p style="color:#666;">This will permanently delete your account and all associated data. <strong>This action cannot be undone.</strong></p>',
        icon: 'warning',
        input: 'text',
        inputPlaceholder: 'Type DELETE MY DATA to confirm',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash-alt"></i> Delete My Data',
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        inputValidator: function(value) {
            if (value !== 'DELETE MY DATA') {
                return 'Please type "DELETE MY DATA" exactly to confirm';
            }
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Request Submitted',
                text: 'Data deletion request submitted. We will process it within 30 days and confirm via email.',
                confirmButtonColor: '#2c5aa0'
            });
        }
    });
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/renter.php';
?>
