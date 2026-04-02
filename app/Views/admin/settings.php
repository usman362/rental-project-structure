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
                    <div>
                        <label for="company_website">Website</label>
                        <input type="url" id="company_website" name="company_website" value="<?= e($settings['company_website']) ?>" placeholder="https://www.example.com" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
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
                <h3 style="color: #2c5aa0;"><i class="fas fa-users"></i> User Management</h3>

                <!-- User Cards -->
                <div style="display: flex; flex-direction: column; gap: 1rem; margin: 1.5rem 0;">
                    <?php foreach ($users as $u): ?>
                    <?php
                        $initials = strtoupper(substr($u['first_name'] ?? 'U', 0, 1));
                        $roleBadge = match($u['role']) {
                            'admin' => ['Administrator', '#dbeafe', '#1e40af'],
                            'renter' => ['Renter', '#d1fae5', '#065f46'],
                            default => [ucfirst($u['role']), '#fed7aa', '#9a3412']
                        };
                    ?>
                    <div style="display: flex; align-items: center; padding: 1.25rem; border: 1px solid #eaeaea; border-radius: 10px; background: #fff;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: #2c5aa0; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px; margin-right: 1rem; flex-shrink: 0;">
                            <?= $initials ?>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 15px;"><?= e(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?></div>
                            <div style="color: #666; font-size: 13px;"><?= e($u['email']) ?></div>
                            <span style="display: inline-block; margin-top: 4px; padding: 2px 10px; background: <?= $roleBadge[1] ?>; color: <?= $roleBadge[2] ?>; border-radius: 20px; font-size: 11px; font-weight: 600;"><?= $roleBadge[0] ?></span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <button type="button" class="btn btn-small" onclick="editUser(<?= $u['id'] ?>, '<?= e($u['first_name'] ?? '') ?>', '<?= e($u['last_name'] ?? '') ?>', '<?= e($u['email']) ?>', '<?= e($u['role']) ?>')" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-small" onclick="resetPassword(<?= $u['id'] ?>, '<?= e(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?>')" title="Reset Password">
                                <i class="fas fa-key"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Add New User Form -->
                <h4 style="margin-top: 2rem;">Add New User</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 1rem 0;">
                    <div>
                        <label>Full Name *</label>
                        <input type="text" id="new_user_name" placeholder="Enter full name" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label>Email *</label>
                        <input type="email" id="new_user_email" placeholder="Enter email address" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label>Role *</label>
                        <select id="new_user_role" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Select Role</option>
                            <option value="admin">Administrator</option>
                            <option value="renter">Renter</option>
                        </select>
                    </div>
                    <div>
                        <label>Initial Password *</label>
                        <input type="password" id="new_user_password" placeholder="Enter initial password" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>
                <button type="button" class="btn btn-primary" style="background: #10b981; border-color: #10b981; margin-top: 0.5rem;" onclick="addNewUser()">
                    <i class="fas fa-user-plus"></i> Add User
                </button>

                <!-- Role Permissions -->
                <hr style="margin: 2rem 0; border: none; border-top: 1px solid #eaeaea;">
                <h4>Role Permissions</h4>
                <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 0.75rem 1rem; text-align: center; color: #666; font-weight: 600;">Permission</th>
                            <th style="padding: 0.75rem 1rem; text-align: center; color: #666; font-weight: 600;">Admin</th>
                            <th style="padding: 0.75rem 1rem; text-align: center; color: #666; font-weight: 600;">Renter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $permissions = [
                            'View Dashboard' => [true, true],
                            'Manage Properties' => [true, false],
                            'Process Payments' => [true, false],
                            'Manage Maintenance' => [true, false],
                            'System Settings' => [true, false],
                            'View Own Data' => [true, true],
                            'Submit Requests' => [true, true],
                        ];
                        foreach ($permissions as $perm => $roles): ?>
                        <tr style="border-bottom: 1px solid #eaeaea;">
                            <td style="padding: 0.75rem 1rem; text-align: center;"><?= $perm ?></td>
                            <td style="padding: 0.75rem 1rem; text-align: center; color: <?= $roles[0] ? '#10b981' : '#ef4444' ?>;">
                                <?= $roles[0] ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>' ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center; color: <?= $roles[1] ? '#10b981' : '#ef4444' ?>;">
                                <?= $roles[1] ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Danger Zone -->
                <div style="margin-top: 2rem; padding: 1.5rem; border: 2px solid #fecaca; border-radius: 10px; background: #fef2f2;">
                    <h4 style="color: #dc2626; margin: 0 0 1rem;"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h4>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <button type="button" class="btn" style="background: #ef4444; color: #fff; border: none;" onclick="resetDefaults()">
                            <i class="fas fa-undo"></i> Reset to Default Settings
                        </button>
                        <button type="button" class="btn" style="background: #ef4444; color: #fff; border: none;" onclick="purgeOldData()">
                            <i class="fas fa-trash"></i> Purge Old Data
                        </button>
                        <a href="<?= route('admin.settings.export-backup') ?>" class="btn" style="background: #ef4444; color: #fff; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-file-export"></i> Export All Data
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrations Tab -->
        <div id="integration" class="tab-content">
            <div class="settings-section">
                <h3 style="color: #2c5aa0;"><i class="fas fa-plug"></i> Integrations</h3>

                <?php
                $integrations = [
                    ['key' => 'integration_quickbooks', 'name' => 'QuickBooks Online', 'icon' => 'fas fa-file-invoice-dollar', 'color' => '#2c5aa0', 'desc' => 'Sync financial data with QuickBooks'],
                    ['key' => 'integration_google_analytics', 'name' => 'Google Analytics', 'icon' => 'fas fa-chart-line', 'color' => '#2c5aa0', 'desc' => 'Track website and application analytics'],
                    ['key' => 'integration_google_maps', 'name' => 'Google Maps', 'icon' => 'fas fa-map-marker-alt', 'color' => '#2c5aa0', 'desc' => 'Display property locations on maps'],
                    ['key' => 'integration_dropbox', 'name' => 'Dropbox', 'icon' => 'fab fa-dropbox', 'color' => '#2c5aa0', 'desc' => 'Backup documents and files'],
                    ['key' => 'integration_twilio', 'name' => 'Twilio', 'icon' => 'fas fa-sms', 'color' => '#2c5aa0', 'desc' => 'Send SMS notifications'],
                    ['key' => 'integration_google_calendar', 'name' => 'Google Calendar', 'icon' => 'fas fa-calendar-alt', 'color' => '#2c5aa0', 'desc' => 'Sync maintenance schedules'],
                ];
                ?>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 1.5rem;">
                    <?php foreach ($integrations as $intg):
                        $connected = ($settings[$intg['key']] ?? '0') === '1';
                    ?>
                    <div style="padding: 1.5rem; border: 2px solid <?= $connected ? '#d1fae5' : '#eaeaea' ?>; border-radius: 10px; background: <?= $connected ? '#f0fdf4' : '#fff' ?>; text-align: center;">
                        <div style="width: 56px; height: 56px; border-radius: 12px; background: #2c5aa0; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 24px;">
                            <i class="<?= $intg['icon'] ?>"></i>
                        </div>
                        <h4 style="margin: 0 0 0.25rem; color: #1a1a1a;"><?= $intg['name'] ?></h4>
                        <div style="font-size: 13px; font-weight: 600; color: <?= $connected ? '#10b981' : '#ef4444' ?>; margin-bottom: 0.5rem;">
                            <?= $connected ? 'Connected' : 'Not Connected' ?>
                        </div>
                        <p style="color: #666; font-size: 13px; margin-bottom: 1rem;"><?= $intg['desc'] ?></p>
                        <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="<?= $intg['key'] ?>" value="1" <?= $connected ? 'checked' : '' ?>>
                            <span style="font-size: 13px;"><?= $connected ? 'Enabled' : 'Enable' ?></span>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Backup & Restore Tab -->
        <div id="backup" class="tab-content">
            <div class="settings-section">
                <h3 style="color: #2c5aa0;"><i class="fas fa-database"></i> Backup & Restore</h3>

                <!-- Stat Cards -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin: 1.5rem 0;">
                    <div style="padding: 1.5rem; border: 1px solid #eaeaea; border-radius: 10px; text-align: center;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #2c5aa0; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 20px;">
                            <i class="fas fa-database"></i>
                        </div>
                        <div style="color: #666; font-size: 13px;">Database Size</div>
                        <div style="font-size: 28px; font-weight: 700; color: #1a1a1a;" id="dbSizeDisplay">--</div>
                        <div style="color: #10b981; font-size: 12px;">Active</div>
                    </div>
                    <div style="padding: 1.5rem; border: 1px solid #eaeaea; border-radius: 10px; text-align: center;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 20px;">
                            <i class="fas fa-save"></i>
                        </div>
                        <div style="color: #666; font-size: 13px;">Last Backup</div>
                        <div style="font-size: 28px; font-weight: 700; color: #1a1a1a;">Manual</div>
                        <div style="color: #2c5aa0; font-size: 12px;">On Demand</div>
                    </div>
                    <div style="padding: 1.5rem; border: 1px solid #eaeaea; border-radius: 10px; text-align: center;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #f59e0b; color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 20px;">
                            <i class="fas fa-history"></i>
                        </div>
                        <div style="color: #666; font-size: 13px;">Retention Period</div>
                        <div style="font-size: 28px; font-weight: 700; color: #1a1a1a;"><?= e($settings['backup_retention_days']) ?></div>
                        <div style="color: #666; font-size: 12px;">Days</div>
                    </div>
                </div>

                <!-- Backup Settings -->
                <h4 style="margin-top: 2rem;">Backup Settings</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 1rem 0;">
                    <div>
                        <label for="backup_frequency">Backup Frequency</label>
                        <select id="backup_frequency" name="backup_frequency" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="daily" <?= $settings['backup_frequency'] === 'daily' ? 'selected' : '' ?>>Daily</option>
                            <option value="weekly" <?= $settings['backup_frequency'] === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                            <option value="monthly" <?= $settings['backup_frequency'] === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label for="backup_retention_days">Retention Period (days)</label>
                        <input type="number" id="backup_retention_days" name="backup_retention_days" value="<?= e($settings['backup_retention_days']) ?>" min="7" max="365" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>
                <div style="margin: 1rem 0;">
                    <div style="margin-bottom: 0.75rem;">
                        <label>
                            <input type="checkbox" name="backup_include_media" value="1" <?= $settings['backup_include_media'] === '1' ? 'checked' : '' ?>>
                            <span>Include media files (photos, documents)</span>
                        </label>
                    </div>
                    <div>
                        <label>
                            <input type="checkbox" name="backup_auto_delete" value="1" <?= $settings['backup_auto_delete'] === '1' ? 'checked' : '' ?>>
                            <span>Auto-delete old backups</span>
                        </label>
                    </div>
                </div>

                <!-- Backup Actions -->
                <hr style="margin: 2rem 0; border: none; border-top: 1px solid #eaeaea;">
                <h4>Backup Actions</h4>
                <div style="display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
                    <button type="button" class="btn btn-primary" style="background: #f59e0b; border-color: #f59e0b;" onclick="createManualBackup()">
                        <i class="fas fa-plus-circle"></i> Create Manual Backup
                    </button>
                    <a href="<?= route('admin.settings.export-backup') ?>" class="btn" style="border: 2px solid #2c5aa0; color: #2c5aa0; background: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-download"></i> Download Latest Backup
                    </a>
                    <button type="button" class="btn" style="background: #ef4444; color: #fff; border: none;" onclick="restoreBackup()">
                        <i class="fas fa-undo"></i> Restore from Backup
                    </button>
                </div>

                <!-- Recent Backups -->
                <hr style="margin: 2rem 0; border: none; border-top: 1px solid #eaeaea;">
                <h4>Recent Backups</h4>
                <div style="margin-top: 1rem;" id="backupList">
                    <div style="padding: 1rem; border: 1px solid #eaeaea; border-radius: 8px; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600;">backup_<?= date('Y_m_d') ?>_manual.csv</div>
                            <div style="color: #666; font-size: 13px;">Available on demand</div>
                        </div>
                        <a href="<?= route('admin.settings.export-backup') ?>" class="btn btn-small" style="text-decoration: none;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                    <div style="padding: 1rem; color: #666; font-size: 14px; text-align: center; border: 1px dashed #ddd; border-radius: 8px;">
                        <i class="fas fa-info-circle"></i> Click "Create Manual Backup" or "Download Latest Backup" to export all system data as CSV.
                    </div>
                </div>
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
const csrfToken = '<?= CSRF::generate() ?>';

function showTab(event, tabName) {
    if (event && event.preventDefault) event.preventDefault();
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.style.borderBottomColor = 'transparent');
    const tab = document.getElementById(tabName);
    if (tab) tab.classList.add('active');
    if (event && event.target) event.target.style.borderBottomColor = '#2c5aa0';
}

function saveAllSettings() {
    document.getElementById('settingsForm').submit();
}

function resetForm() {
    Swal.fire({
        title: 'Discard Changes?',
        text: 'Are you sure you want to discard all unsaved changes?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Discard',
        cancelButtonText: 'Keep Editing'
    }).then((result) => {
        if (result.isConfirmed) location.reload();
    });
}

// ===== User Management Functions =====
function addNewUser() {
    const name = document.getElementById('new_user_name').value.trim();
    const email = document.getElementById('new_user_email').value.trim();
    const role = document.getElementById('new_user_role').value;
    const password = document.getElementById('new_user_password').value;

    if (!name || !email || !role || !password) {
        Swal.fire({ title: 'Missing Fields', text: 'Please fill in all required fields.', icon: 'warning', confirmButtonColor: '#2c5aa0' });
        return;
    }

    // Split name into first/last
    const parts = name.split(' ');
    const firstName = parts[0];
    const lastName = parts.slice(1).join(' ') || '';

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= route("admin.settings.add-user") ?>';
    const fields = { _token: csrfToken, first_name: firstName, last_name: lastName, email: email, role: role, password: password };
    for (const [k, v] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = k; input.value = v;
        form.appendChild(input);
    }
    document.body.appendChild(form);
    form.submit();
}

function editUser(id, firstName, lastName, email, role) {
    Swal.fire({
        title: 'Edit User',
        html: `
            <div style="text-align:left;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">First Name</label>
                    <input type="text" id="swal-fname" class="swal2-input" style="width:100%;margin:0;" value="${firstName}">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Last Name</label>
                    <input type="text" id="swal-lname" class="swal2-input" style="width:100%;margin:0;" value="${lastName}">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Email</label>
                    <input type="email" id="swal-email" class="swal2-input" style="width:100%;margin:0;" value="${email}">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Role</label>
                    <select id="swal-role" class="swal2-select" style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:4px;">
                        <option value="admin" ${role==='admin'?'selected':''}>Administrator</option>
                        <option value="renter" ${role==='renter'?'selected':''}>Renter</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        confirmButtonText: '<i class="fas fa-save"></i> Save Changes',
        preConfirm: () => {
            return {
                first_name: document.getElementById('swal-fname').value,
                last_name: document.getElementById('swal-lname').value,
                email: document.getElementById('swal-email').value,
                role: document.getElementById('swal-role').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= route("admin.settings.update-user") ?>';
            const fields = { _token: csrfToken, user_id: id, ...result.value };
            for (const [k, v] of Object.entries(fields)) {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = k; input.value = v;
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function resetPassword(id, name) {
    Swal.fire({
        title: 'Reset Password',
        html: `<p>Set a new password for <strong>${name}</strong></p>
               <input type="password" id="swal-newpass" class="swal2-input" placeholder="New password" style="width:100%;margin:0.5rem 0;">`,
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        confirmButtonText: '<i class="fas fa-key"></i> Reset Password',
        preConfirm: () => {
            const pass = document.getElementById('swal-newpass').value;
            if (!pass || pass.length < 6) {
                Swal.showValidationMessage('Password must be at least 6 characters');
                return false;
            }
            return pass;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= route("admin.settings.update-user") ?>';
            const fields = { _token: csrfToken, user_id: id, password: result.value };
            for (const [k, v] of Object.entries(fields)) {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = k; input.value = v;
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function resetDefaults() {
    Swal.fire({
        title: 'Reset to Default Settings?',
        html: '<p style="color:#e74c3c;">This will reset all system settings to their default values. User accounts and data will NOT be affected.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        confirmButtonText: '<i class="fas fa-undo"></i> Reset All',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Settings Reset!', text: 'All settings have been reset to defaults. Please reload the page.', icon: 'success', confirmButtonColor: '#2c5aa0' }).then(() => location.reload());
        }
    });
}

function purgeOldData() {
    Swal.fire({
        title: 'Purge Old Data?',
        html: '<p style="color:#e74c3c;">This will remove notifications older than 90 days and completed maintenance requests older than 1 year. This cannot be undone.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        confirmButtonText: '<i class="fas fa-trash"></i> Purge Data',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Data Purged!', text: 'Old data has been cleaned up successfully.', icon: 'success', confirmButtonColor: '#2c5aa0' });
        }
    });
}

// ===== Backup Functions =====
function createManualBackup() {
    Swal.fire({
        title: 'Create Manual Backup?',
        text: 'This will export all system data as a CSV file for download.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        confirmButtonText: '<i class="fas fa-database"></i> Create & Download',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= route("admin.settings.export-backup") ?>';
        }
    });
}

function restoreBackup() {
    Swal.fire({
        title: 'Restore from Backup',
        html: '<p>To restore from a backup, please contact your system administrator with the backup file.</p><p style="color:#666;font-size:14px;">For security reasons, restore operations require manual verification.</p>',
        icon: 'info',
        confirmButtonColor: '#2c5aa0',
        confirmButtonText: 'Understood'
    });
}

// Style active tab button on load
document.addEventListener('DOMContentLoaded', function() {
    const firstButton = document.querySelector('.tab-btn.active');
    if (firstButton) firstButton.style.borderBottomColor = '#2c5aa0';
});
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
