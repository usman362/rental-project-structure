<?php
$title = 'Settings';
$active = 'settings';
ob_start();
?>

<div class="content-header">
    <h1>Settings</h1>
</div>

<!-- Settings Tabs -->
<div style="display: flex; border-bottom: 1px solid #eee; margin-bottom: 2rem;">
    <button class="settings-tab active" onclick="switchTab(event, 'notifications')" style="padding: 12px 24px; background: none; border: none; font-weight: 500; color: #666; cursor: pointer; position: relative;">
        <i class="fas fa-bell"></i> Notifications
        <span style="position: absolute; bottom: -1px; left: 0; width: 100%; height: 3px; background-color: #2c5aa0; border-radius: 3px 3px 0 0; display: block;"></span>
    </button>
    <button class="settings-tab" onclick="switchTab(event, 'privacy')" style="padding: 12px 24px; background: none; border: none; font-weight: 500; color: #666; cursor: pointer; position: relative;">
        <i class="fas fa-lock"></i> Privacy
    </button>
    <button class="settings-tab" onclick="switchTab(event, 'language')" style="padding: 12px 24px; background: none; border: none; font-weight: 500; color: #666; cursor: pointer; position: relative;">
        <i class="fas fa-globe"></i> Language & Region
    </button>
    <button class="settings-tab" onclick="switchTab(event, 'data')" style="padding: 12px 24px; background: none; border: none; font-weight: 500; color: #666; cursor: pointer; position: relative;">
        <i class="fas fa-database"></i> Data Management
    </button>
</div>

<form id="settingsForm" method="POST" action="<?= route('renter.settings') ?>">
    <?= csrf_field() ?>

    <!-- Notifications Tab -->
    <div id="notifications" class="settings-content active">
        <div style="background-color: #f9f9f9; border-radius: 12px; padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; color: #2c5aa0;">Notification Preferences</h2>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Email Notifications</h4>
                    <p style="color: #666; font-size: 14px;">Receive important updates via email</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="email_notifications" <?= isset($settings['email_notifications']) && $settings['email_notifications'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['email_notifications']) && $settings['email_notifications'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">SMS Notifications</h4>
                    <p style="color: #666; font-size: 14px;">Get text message alerts for urgent matters</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="sms_notifications" <?= isset($settings['sms_notifications']) && $settings['sms_notifications'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['sms_notifications']) && $settings['sms_notifications'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Payment Reminders</h4>
                    <p style="color: #666; font-size: 14px;">Remind me when rent payments are due</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="payment_reminders" <?= isset($settings['payment_reminders']) && $settings['payment_reminders'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['payment_reminders']) && $settings['payment_reminders'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Maintenance Updates</h4>
                    <p style="color: #666; font-size: 14px;">Notify me about maintenance requests and status</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="maintenance_updates" <?= isset($settings['maintenance_updates']) && $settings['maintenance_updates'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['maintenance_updates']) && $settings['maintenance_updates'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Newsletter</h4>
                    <p style="color: #666; font-size: 14px;">Subscribe to our monthly newsletter</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="newsletter" <?= isset($settings['newsletter']) && $settings['newsletter'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['newsletter']) && $settings['newsletter'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Marketing Communications</h4>
                    <p style="color: #666; font-size: 14px;">Receive promotions and special offers</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="marketing" <?= isset($settings['marketing']) && $settings['marketing'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['marketing']) && $settings['marketing'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Privacy Tab -->
    <div id="privacy" class="settings-content" style="display: none;">
        <div style="background-color: #f9f9f9; border-radius: 12px; padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; color: #2c5aa0;">Privacy Settings</h2>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Show Profile to Management</h4>
                    <p style="color: #666; font-size: 14px;">Allow property manager to see your profile</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="show_profile" <?= isset($settings['show_profile']) && $settings['show_profile'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['show_profile']) && $settings['show_profile'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Show Phone Number</h4>
                    <p style="color: #666; font-size: 14px;">Display your phone number in your profile</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="show_phone" <?= isset($settings['show_phone']) && $settings['show_phone'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['show_phone']) && $settings['show_phone'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Show Email Address</h4>
                    <p style="color: #666; font-size: 14px;">Display your email address in your profile</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="show_email" <?= isset($settings['show_email']) && $settings['show_email'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['show_email']) && $settings['show_email'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Allow Data Collection</h4>
                    <p style="color: #666; font-size: 14px;">Help us improve by allowing usage analytics</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="allow_data_collection" <?= isset($settings['allow_data_collection']) && $settings['allow_data_collection'] ? 'checked' : '' ?> style="opacity: 0; width: 0; height: 0;" onchange="updateToggle(this)" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?= isset($settings['allow_data_collection']) && $settings['allow_data_collection'] ? '#2c5aa0' : '#ccc' ?>; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Language & Region Tab -->
    <div id="language" class="settings-content" style="display: none;">
        <div style="background-color: #f9f9f9; border-radius: 12px; padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; color: #2c5aa0;">Language & Region</h2>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Language</label>
                <select name="language" style="width: 100%; max-width: 300px; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
                    <option value="en" <?= $settings['language'] === 'en' ? 'selected' : '' ?>>English</option>
                    <option value="es" <?= $settings['language'] === 'es' ? 'selected' : '' ?>>Spanish</option>
                    <option value="fr" <?= $settings['language'] === 'fr' ? 'selected' : '' ?>>French</option>
                    <option value="de" <?= $settings['language'] === 'de' ? 'selected' : '' ?>>German</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Timezone</label>
                <select name="timezone" style="width: 100%; max-width: 300px; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
                    <option value="America/Denver" <?= $settings['timezone'] === 'America/Denver' ? 'selected' : '' ?>>Mountain Time</option>
                    <option value="America/Chicago" <?= $settings['timezone'] === 'America/Chicago' ? 'selected' : '' ?>>Central Time</option>
                    <option value="America/New_York" <?= $settings['timezone'] === 'America/New_York' ? 'selected' : '' ?>>Eastern Time</option>
                    <option value="America/Los_Angeles" <?= $settings['timezone'] === 'America/Los_Angeles' ? 'selected' : '' ?>>Pacific Time</option>
                    <option value="UTC" <?= $settings['timezone'] === 'UTC' ? 'selected' : '' ?>>UTC</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Date Format</label>
                <div style="display: flex; gap: 2rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="radio" name="date_format" value="MM/DD/YYYY" <?= $settings['date_format'] === 'MM/DD/YYYY' ? 'checked' : '' ?> />
                        <span>MM/DD/YYYY</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="radio" name="date_format" value="DD/MM/YYYY" <?= $settings['date_format'] === 'DD/MM/YYYY' ? 'checked' : '' ?> />
                        <span>DD/MM/YYYY</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="radio" name="date_format" value="YYYY-MM-DD" <?= $settings['date_format'] === 'YYYY-MM-DD' ? 'checked' : '' ?> />
                        <span>YYYY-MM-DD</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Management Tab -->
    <div id="data" class="settings-content" style="display: none;">
        <div style="background-color: #f9f9f9; border-radius: 12px; padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; color: #2c5aa0;">Data Management</h2>

            <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #ddd;">
                <h3 style="margin-bottom: 1rem; color: #333;">Download Your Data</h3>
                <p style="color: #666; margin-bottom: 1rem;">Download a copy of all your personal data in a portable format.</p>
                <label style="display: block; margin-bottom: 1rem; color: #444; font-weight: 500; font-size: 14px;">Export Format</label>
                <select style="width: 100%; max-width: 300px; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; margin-bottom: 1rem;">
                    <option>JSON</option>
                    <option>CSV</option>
                    <option>PDF</option>
                </select>
                <button type="button" class="btn btn-primary" onclick="downloadData()" style="background-color: #2c5aa0; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer;">
                    <i class="fas fa-download"></i> Download My Data
                </button>
            </div>

            <div style="background-color: #fff5f5; border: 1px solid #ffebee; border-radius: 8px; padding: 1.5rem;">
                <h3 style="margin-bottom: 1rem; color: #e74c3c;">Request Data Deletion</h3>
                <p style="color: #666; margin-bottom: 1rem;">Request a permanent deletion of your account and all associated data. This action cannot be undone.</p>
                <button type="button" class="btn btn-secondary" style="border-color: #e74c3c; color: #e74c3c; background-color: transparent; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer;" onclick="requestDeletion()">
                    <i class="fas fa-trash-alt"></i> Request Data Deletion
                </button>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="margin-top: 2rem;">
        <button type="submit" class="btn btn-primary" style="background-color: #2c5aa0; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer;">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </div>
</form>

<script>
function switchTab(event, tabName) {
    event.preventDefault();

    // Hide all content divs
    const contents = document.querySelectorAll('.settings-content');
    contents.forEach(content => content.style.display = 'none');

    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.settings-tab');
    tabs.forEach(tab => {
        tab.style.color = '#666';
        const activeIndicator = tab.querySelector('span');
        if (activeIndicator) {
            activeIndicator.style.display = 'none';
        }
    });

    // Show selected tab content
    document.getElementById(tabName).style.display = 'block';

    // Mark current tab as active
    event.target.closest('.settings-tab').style.color = '#2c5aa0';
    const indicator = event.target.closest('.settings-tab').querySelector('span');
    if (indicator) {
        indicator.style.display = 'block';
    }
}

function updateToggle(checkbox) {
    const slider = checkbox.nextElementSibling;
    if (checkbox.checked) {
        slider.style.backgroundColor = '#2c5aa0';
    } else {
        slider.style.backgroundColor = '#ccc';
    }
}

function downloadData() {
    alert('Your data export is being prepared. You will receive a download link via email shortly.');
}

function requestDeletion() {
    if (confirm('Are you sure you want to request data deletion? This action cannot be undone.')) {
        const confirmDelete = prompt('Type "DELETE MY DATA" to confirm:');
        if (confirmDelete === 'DELETE MY DATA') {
            alert('Data deletion request submitted. We will process it within 30 days and confirm via email.');
        } else {
            alert('Data deletion request cancelled.');
        }
    }
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/renter.php';
?>
