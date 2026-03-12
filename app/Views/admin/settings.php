<?php
$title = 'System Settings';
$active = 'settings';
ob_start();
?>

<div class="content-header">
    <h1>System Settings</h1>
    <div class="content-actions">
        <button class="btn btn-primary" onclick="saveAllSettings()">
            <i class="fas fa-save"></i> Save All Settings
        </button>
    </div>
</div>

<?php if ($successMessage): ?>
    <div style="padding: 1rem; margin-bottom: 1rem; background: #d1fae5; color: #065f46; border-radius: 8px; border-left: 4px solid #10b981;">
        <strong>Success:</strong> <?= e($successMessage) ?>
    </div>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <div style="padding: 1rem; margin-bottom: 1rem; background: #fee2e2; color: #7f1d1d; border-radius: 8px; border-left: 4px solid #ef4444;">
        <strong>Error:</strong> <?= e($errorMessage) ?>
    </div>
<?php endif; ?>

<!-- Settings Form -->
<form id="settingsForm" method="POST" action="<?= route('admin.settings') ?>">
    <?= csrf_field() ?>

    <!-- Settings Tabs -->
    <div class="settings-tabs">
        <div class="tab-navigation" style="border-bottom: 2px solid #eaeaea; margin-bottom: 2rem; display: flex; gap: 0; flex-wrap: wrap;">
            <button type="button" class="tab-btn active" onclick="showTab(event, 'company')" style="padding: 1rem; border: none; background: none; cursor: pointer; color: #666; font-weight: 500; border-bottom: 3px solid transparent; margin-bottom: -2px;">
                <i class="fas fa-building"></i> Company Profile
            </button>
            <button type="button" class="tab-btn" onclick="showTab(event, 'payment')" style="padding: 1rem; border: none; background: none; cursor: pointer; color: #666; font-weight: 500; border-bottom: 3px solid transparent; margin-bottom: -2px;">
                <i class="fas fa-credit-card"></i> Payment Settings
            </button>
            <button type="button" class="tab-btn" onclick="showTab(event, 'email')" style="padding: 1rem; border: none; background: none; cursor: pointer; color: #666; font-weight: 500; border-bottom: 3px solid transparent; margin-bottom: -2px;">
                <i class="fas fa-envelope"></i> Email & Notifications
            </button>
            <button type="button" class="tab-btn" onclick="showTab(event, 'security')" style="padding: 1rem; border: none; background: none; cursor: pointer; color: #666; font-weight: 500; border-bottom: 3px solid transparent; margin-bottom: -2px;">
                <i class="fas fa-shield-alt"></i> Security
            </button>
            <button type="button" class="tab-btn" onclick="showTab(event, 'users')" style="padding: 1rem; border: none; background: none; cursor: pointer; color: #666; font-weight: 500; border-bottom: 3px solid transparent; margin-bottom: -2px;">
                <i class="fas fa-users"></i> User Management
            </button>
            <button type="button" class="tab-btn" onclick="showTab(event, 'integration')" style="padding: 1rem; border: none; background: none; cursor: pointer; color: #666; font-weight: 500; border-bottom: 3px solid transparent; margin-bottom: -2px;">
                <i class="fas fa-plug"></i> Integrations
            </button>
            <button type="button" class="tab-btn" onclick="showTab(event, 'backup')" style="padding: 1rem; border: none; background: none; cursor: pointer; color: #666; font-weight: 500; border-bottom: 3px solid transparent; margin-bottom: -2px;">
                <i class="fas fa-database"></i> Backup & Restore
            </button>
        </div>

        <!-- Company Profile Tab -->
        <div id="company" class="tab-content active">
            <div class="settings-section">
                <h3><i class="fas fa-building"></i> Company Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 1.5rem 0;">
                    <div>
                        <label for="company_name">Company Name *</label>
                        <input type="text" id="company_name" name="company_name" value="<?= e($settings['company_name']) ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="company_email">Company Email</label>
                        <input type="email" id="company_email" name="company_email" value="<?= e($settings['company_email']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="company_phone">Phone Number</label>
                        <input type="tel" id="company_phone" name="company_phone" value="<?= e($settings['company_phone']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Address Information</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 1.5rem 0;">
                    <div>
                        <label for="company_address">Street Address</label>
                        <input type="text" id="company_address" name="company_address" value="<?= e($settings['company_address']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="company_city">City</label>
                        <input type="text" id="company_city" name="company_city" value="<?= e($settings['company_city']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="company_state">State</label>
                        <input type="text" id="company_state" name="company_state" value="<?= e($settings['company_state']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="company_zip">ZIP Code</label>
                        <input type="text" id="company_zip" name="company_zip" value="<?= e($settings['company_zip']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings Tab -->
        <div id="payment" class="tab-content">
            <div class="settings-section">
                <h3><i class="fas fa-credit-card"></i> Payment Configuration</h3>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Payment Terms & Fees</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 1.5rem 0;">
                    <div>
                        <label for="late_fee_percent">Late Fee Percentage (%)</label>
                        <input type="number" id="late_fee_percent" name="late_fee_percent" value="<?= e($settings['late_fee_percent']) ?>" min="0" max="100" step="0.1" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="grace_period_days">Grace Period (days)</label>
                        <input type="number" id="grace_period_days" name="grace_period_days" value="<?= e($settings['grace_period_days']) ?>" min="0" max="30" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <div>
                    <label for="payment_methods">Accepted Payment Methods</label>
                    <select id="payment_methods" name="payment_methods" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="bank,card,check" <?= $settings['payment_methods'] === 'bank,card,check' ? 'selected' : '' ?>>All Methods</option>
                        <option value="bank,card" <?= $settings['payment_methods'] === 'bank,card' ? 'selected' : '' ?>>Bank Transfer & Card</option>
                        <option value="card" <?= $settings['payment_methods'] === 'card' ? 'selected' : '' ?>>Card Only</option>
                    </select>
                </div>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Bank Information</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 1.5rem 0;">
                    <div>
                        <label for="bank_name">Bank Name</label>
                        <input type="text" id="bank_name" name="bank_name" value="<?= e($settings['bank_name']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="bank_routing">Routing Number</label>
                        <input type="text" id="bank_routing" name="bank_routing" value="<?= e($settings['bank_routing']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="bank_account">Account Number</label>
                        <input type="text" id="bank_account" name="bank_account" value="<?= e($settings['bank_account']) ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Email & Notifications Tab -->
        <div id="email" class="tab-content">
            <div class="settings-section">
                <h3><i class="fas fa-envelope"></i> Email & Notification Settings</h3>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Notification Preferences</h4>
                <div style="margin: 1.5rem 0;">
                    <div style="margin-bottom: 1rem;">
                        <label>
                            <input type="checkbox" name="email_payment_reminders" value="1" <?= $settings['email_payment_reminders'] ? 'checked' : '' ?>>
                            <span>Email payment reminders to renters</span>
                        </label>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>
                            <input type="checkbox" name="email_maintenance_updates" value="1" <?= $settings['email_maintenance_updates'] ? 'checked' : '' ?>>
                            <span>Email maintenance request updates</span>
                        </label>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>
                            <input type="checkbox" name="email_lease_expiry" value="1" <?= $settings['email_lease_expiry'] ? 'checked' : '' ?>>
                            <span>Email lease expiry notifications</span>
                        </label>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>
                            <input type="checkbox" name="email_new_applications" value="1" <?= $settings['email_new_applications'] ? 'checked' : '' ?>>
                            <span>Email new application alerts</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Tab -->
        <div id="security" class="tab-content">
            <div class="settings-section">
                <h3><i class="fas fa-shield-alt"></i> Security Settings</h3>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Password Policy</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 1.5rem 0;">
                    <div>
                        <label for="password_min_length">Minimum Password Length</label>
                        <input type="number" id="password_min_length" name="password_min_length" value="<?= e($settings['password_min_length']) ?>" min="6" max="32" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="session_timeout_minutes">Session Timeout (minutes)</label>
                        <input type="number" id="session_timeout_minutes" name="session_timeout_minutes" value="<?= e($settings['session_timeout_minutes']) ?>" min="5" max="480" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="max_login_attempts">Max Failed Login Attempts</label>
                        <input type="number" id="max_login_attempts" name="max_login_attempts" value="<?= e($settings['max_login_attempts']) ?>" min="1" max="10" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Additional Security</h4>
                <div style="margin: 1.5rem 0;">
                    <div style="margin-bottom: 1rem;">
                        <label>
                            <input type="checkbox" name="two_factor_enabled" value="1" <?= $settings['two_factor_enabled'] ? 'checked' : '' ?>>
                            <span>Enable Two-Factor Authentication</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Management Tab -->
        <div id="users" class="tab-content">
            <div class="settings-section">
                <h3><i class="fas fa-users"></i> User Management</h3>
                <p style="color: #666; margin-bottom: 1.5rem;">Manage system users and their roles</p>

                <div style="margin-bottom: 2rem;">
                    <button type="button" class="btn btn-primary" onclick="addNewUser()">
                        <i class="fas fa-user-plus"></i> Add New User
                    </button>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">User</th>
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Email</th>
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Role</th>
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Status</th>
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Last Login</th>
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #eaeaea;">
                            <td style="padding: 1rem;">John Admin</td>
                            <td style="padding: 1rem;">john@sotelomanagement.com</td>
                            <td style="padding: 1rem;"><span style="padding: 0.25rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 20px; font-size: 12px; font-weight: 600;">Admin</span></td>
                            <td style="padding: 1rem;"><span style="padding: 0.25rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span></td>
                            <td style="padding: 1rem; color: #666;">Today at 9:30 AM</td>
                            <td style="padding: 1rem;">
                                <button type="button" class="btn btn-small" onclick="editUser(1)">Edit</button>
                                <button type="button" class="btn btn-small" style="margin-left: 0.5rem;" onclick="removeUser(1)">Remove</button>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #eaeaea;">
                            <td style="padding: 1rem;">Jane Manager</td>
                            <td style="padding: 1rem;">jane@sotelomanagement.com</td>
                            <td style="padding: 1rem;"><span style="padding: 0.25rem 0.75rem; background: #fed7aa; color: #9a3412; border-radius: 20px; font-size: 12px; font-weight: 600;">Manager</span></td>
                            <td style="padding: 1rem;"><span style="padding: 0.25rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span></td>
                            <td style="padding: 1rem; color: #666;">Yesterday at 2:15 PM</td>
                            <td style="padding: 1rem;">
                                <button type="button" class="btn btn-small" onclick="editUser(2)">Edit</button>
                                <button type="button" class="btn btn-small" style="margin-left: 0.5rem;" onclick="removeUser(2)">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Integrations Tab -->
        <div id="integration" class="tab-content">
            <div class="settings-section">
                <h3><i class="fas fa-plug"></i> Integrations</h3>
                <p style="color: #666; margin-bottom: 1.5rem;">Connect third-party services to enhance functionality</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Stripe Integration -->
                    <div style="padding: 1.5rem; border: 1px solid #ddd; border-radius: 8px; background: #f9fafb;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="font-size: 2rem; color: #635bff;">
                                <i class="fab fa-stripe"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; color: #2c5aa0;">Stripe</h4>
                                <p style="margin: 0; font-size: 12px; color: #666;">Payment Processing</p>
                            </div>
                        </div>
                        <p style="color: #666; font-size: 14px; margin-bottom: 1rem;">Accept credit cards and digital payments securely.</p>
                        <button type="button" class="btn btn-secondary" onclick="configureIntegration('stripe')">Configure</button>
                    </div>

                    <!-- QuickBooks Integration -->
                    <div style="padding: 1.5rem; border: 1px solid #ddd; border-radius: 8px; background: #f9fafb;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="font-size: 2rem; color: #4285f4;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; color: #2c5aa0;">QuickBooks</h4>
                                <p style="margin: 0; font-size: 12px; color: #666;">Accounting Software</p>
                            </div>
                        </div>
                        <p style="color: #666; font-size: 14px; margin-bottom: 1rem;">Sync financial data with QuickBooks Online.</p>
                        <button type="button" class="btn btn-secondary" onclick="configureIntegration('quickbooks')">Connect</button>
                    </div>

                    <!-- Google Workspace Integration -->
                    <div style="padding: 1.5rem; border: 1px solid #ddd; border-radius: 8px; background: #f9fafb;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="font-size: 2rem; color: #ea4335;">
                                <i class="fab fa-google"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; color: #2c5aa0;">Google Workspace</h4>
                                <p style="margin: 0; font-size: 12px; color: #666;">Email & Calendar</p>
                            </div>
                        </div>
                        <p style="color: #666; font-size: 14px; margin-bottom: 1rem;">Integrate Gmail, Calendar, and Drive access.</p>
                        <button type="button" class="btn btn-secondary" onclick="configureIntegration('google')">Connect</button>
                    </div>

                    <!-- Slack Integration -->
                    <div style="padding: 1.5rem; border: 1px solid #ddd; border-radius: 8px; background: #f9fafb;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="font-size: 2rem; color: #36c5f0;">
                                <i class="fab fa-slack"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; color: #2c5aa0;">Slack</h4>
                                <p style="margin: 0; font-size: 12px; color: #666;">Team Communication</p>
                            </div>
                        </div>
                        <p style="color: #666; font-size: 14px; margin-bottom: 1rem;">Get notifications and alerts in your Slack workspace.</p>
                        <button type="button" class="btn btn-secondary" onclick="configureIntegration('slack')">Connect</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup & Restore Tab -->
        <div id="backup" class="tab-content">
            <div class="settings-section">
                <h3><i class="fas fa-database"></i> Backup & Restore</h3>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Manual Backup</h4>
                <div style="padding: 1.5rem; background: #f8fafc; border-radius: 8px; margin: 1.5rem 0;">
                    <p style="color: #666; margin-bottom: 1rem;">Create an immediate backup of your system data.</p>
                    <button type="button" class="btn btn-primary" onclick="createBackup()">
                        <i class="fas fa-download"></i> Create Backup Now
                    </button>
                </div>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Automatic Backup</h4>
                <div style="margin: 1.5rem 0;">
                    <label>
                        <input type="checkbox" id="auto_backup" checked>
                        <span>Enable automatic daily backups</span>
                    </label>
                    <select id="backup_time" style="margin-left: 2rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="00">Midnight (00:00)</option>
                        <option value="01">1:00 AM</option>
                        <option value="02">2:00 AM</option>
                        <option value="03">3:00 AM</option>
                        <option value="04">4:00 AM</option>
                    </select>
                </div>

                <h4 style="margin-top: 2rem; color: #2c5aa0;">Backup History</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Date</th>
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Size</th>
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Type</th>
                            <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #eaeaea;">
                            <td style="padding: 1rem;">Mar 12, 2026 - 2:15 AM</td>
                            <td style="padding: 1rem;">245 MB</td>
                            <td style="padding: 1rem;"><span style="padding: 0.25rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 20px; font-size: 12px; font-weight: 600;">Automatic</span></td>
                            <td style="padding: 1rem;">
                                <button type="button" class="btn btn-small" onclick="downloadBackup('2026-03-12')">Download</button>
                                <button type="button" class="btn btn-small" style="margin-left: 0.5rem;" onclick="restoreBackup('2026-03-12')">Restore</button>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #eaeaea;">
                            <td style="padding: 1rem;">Mar 11, 2026 - 2:18 AM</td>
                            <td style="padding: 1rem;">243 MB</td>
                            <td style="padding: 1rem;"><span style="padding: 0.25rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 20px; font-size: 12px; font-weight: 600;">Automatic</span></td>
                            <td style="padding: 1rem;">
                                <button type="button" class="btn btn-small" onclick="downloadBackup('2026-03-11')">Download</button>
                                <button type="button" class="btn btn-small" style="margin-left: 0.5rem;" onclick="restoreBackup('2026-03-11')">Restore</button>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #eaeaea;">
                            <td style="padding: 1rem;">Mar 10, 2026 - 2:12 AM</td>
                            <td style="padding: 1rem;">241 MB</td>
                            <td style="padding: 1rem;"><span style="padding: 0.25rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 20px; font-size: 12px; font-weight: 600;">Automatic</span></td>
                            <td style="padding: 1rem;">
                                <button type="button" class="btn btn-small" onclick="downloadBackup('2026-03-10')">Download</button>
                                <button type="button" class="btn btn-small" style="margin-left: 0.5rem;" onclick="restoreBackup('2026-03-10')">Restore</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Submit -->
    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eaeaea; display: flex; gap: 1rem;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Settings
        </button>
        <button type="button" class="btn" onclick="resetForm()">
            Cancel
        </button>
    </div>
</form>

<script>
function showTab(event, tabName) {
    if (event && event.preventDefault) {
        event.preventDefault();
    }

    // Hide all tabs
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));

    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.style.borderBottomColor = 'transparent');

    // Show selected tab
    const tab = document.getElementById(tabName);
    if (tab) {
        tab.classList.add('active');
    }

    // Mark button as active
    if (event && event.target) {
        event.target.style.borderBottomColor = '#2c5aa0';
    }
}

function saveAllSettings() {
    document.getElementById('settingsForm').submit();
}

function resetForm() {
    if (confirm('Are you sure you want to discard your changes?')) {
        location.reload();
    }
}

function addNewUser() {
    alert('Opening add user dialog...\n\nIn a real application, this would open a form to create a new user.');
}

function editUser(id) {
    alert(`Editing user #${id}...`);
}

function removeUser(id) {
    if (confirm('Are you sure you want to remove this user?')) {
        alert(`User #${id} removed.`);
    }
}

function configureIntegration(service) {
    alert(`Configuring ${service} integration...\n\nIn a real application, this would open the configuration dialog for ${service}.`);
}

function createBackup() {
    alert('Creating backup...\n\nIn a real application, this would create a full system backup and prepare it for download.');
}

function downloadBackup(date) {
    alert(`Downloading backup from ${date}...\n\nIn a real application, this would download the backup file.`);
}

function restoreBackup(date) {
    if (confirm(`Are you sure you want to restore from ${date}? This will overwrite current data.`)) {
        alert(`Restoring backup from ${date}...\n\nIn a real application, this would restore the system from the backup.`);
    }
}

// Style active tab button on load
document.addEventListener('DOMContentLoaded', function() {
    const firstButton = document.querySelector('.tab-btn.active');
    if (firstButton) {
        firstButton.style.borderBottomColor = '#2c5aa0';
    }
});
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
