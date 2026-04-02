<?php
$title = 'My Profile';
$active = 'profile';
ob_start();
?>

<style>
    /* Dashboard Header */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .dashboard-header h1 {
        color: #2c5aa0;
        font-size: 1.8rem;
        font-weight: 600;
    }

    /* Profile Header */
    .profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .profile-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2c5aa0, #3a6bc5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 36px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .profile-info h2 {
        color: #333;
        margin-bottom: 5px;
    }

    .profile-info p {
        color: #666;
        margin-bottom: 10px;
    }

    .profile-status {
        display: inline-block;
        background-color: rgba(46, 204, 113, 0.1);
        color: #27ae60;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
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

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 1.5rem;
    }

    .form-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }

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

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: border 0.3s, box-shadow 0.3s;
        font-family: 'Inter', system-ui, sans-serif;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #2c5aa0;
        outline: none;
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }

    .form-group input[readonly] {
        background-color: #f5f5f5;
        color: #666;
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

    .btn-secondary {
        background-color: #f8f9fa;
        color: #2c5aa0;
        border: 1px solid #ddd;
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

    .btn-secondary:hover {
        background-color: #2c5aa0;
        color: white;
        border-color: #2c5aa0;
    }

    /* Notification/Toggle Items */
    .notification-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }

    .notification-info h4 {
        color: #333;
        margin-bottom: 5px;
    }

    .notification-info p {
        color: #666;
        font-size: 14px;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
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

    /* Danger Zone */
    .danger-zone {
        background-color: #fff5f5;
        border: 2px solid #ffebee;
        border-radius: 12px;
        padding: 2rem;
    }

    .danger-zone h2 {
        margin-bottom: 1rem;
        color: #e74c3c;
    }

    .danger-zone p {
        color: #666;
        margin-bottom: 1.5rem;
    }

    .btn-danger {
        background-color: #f8f9fa;
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

    .btn-danger:hover {
        background-color: #e74c3c;
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .form-row {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <h1>My Profile</h1>
    <button form="profileForm" type="submit" class="btn-primary">
        <i class="fas fa-save"></i> Save Changes
    </button>
</div>

<!-- Profile Header -->
<div class="profile-header">
    <div class="profile-avatar-large"><?php
        $initials = strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? 'U', 0, 1));
        echo e($initials);
    ?></div>
    <div class="profile-info">
        <h2><?= e($user['first_name'] ?? '') ?> <?= e($user['last_name'] ?? '') ?></h2>
        <p>Tenant since <?= $renter && $renter['move_in_date'] ? date('F j, Y', strtotime($renter['move_in_date'])) : 'N/A' ?></p>
        <span class="profile-status">
            <?= $renter && $renter['status'] === 'active' ? 'Active Lease' : 'Inactive' ?>
        </span>
    </div>
</div>

<!-- Personal Information -->
<form id="profileForm" method="POST" action="<?= route('renter.profile') ?>">
    <?= csrf_field() ?>

    <div class="form-section">
        <h2>Personal Information</h2>

        <div class="form-row">
            <div class="form-group">
                <label for="firstName">First Name *</label>
                <input type="text" id="firstName" name="first_name" value="<?= e($user['first_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name *</label>
                <input type="text" id="lastName" name="last_name" value="<?= e($user['last_name'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="<?= e($user['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="<?= e($user['dob'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="emergencyContact">Emergency Contact</label>
                <input type="text" id="emergencyContact" name="emergency_contact" value="<?= e($renter['emergency_contact'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="emergencyPhone">Emergency Phone</label>
                <input type="tel" id="emergencyPhone" name="emergency_phone" value="<?= e($user['emergency_phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="relationship">Relationship</label>
                <input type="text" id="relationship" name="relationship" value="<?= e($user['relationship'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="occupation">Occupation</label>
            <input type="text" id="occupation" name="occupation" value="<?= e($user['occupation'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="bio">Bio/Notes</label>
            <textarea id="bio" name="bio" rows="3" placeholder="Tell us a little about yourself..."><?= e($renter['notes'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- Address Information -->
    <div class="form-section">
        <h2>Address Information</h2>

        <div class="form-row">
            <div class="form-group">
                <label for="currentAddress">Current Address</label>
                <input type="text" id="currentAddress" name="current_address" value="<?= e($renter['address'] ?? '') ?>" readonly>
            </div>
            <div class="form-group">
                <label for="unitNumber">Unit Number</label>
                <input type="text" id="unitNumber" name="unit_number" value="<?= e($property['unit'] ?? '') ?>" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="permanentAddress">Permanent Address</label>
                <input type="text" id="permanentAddress" name="permanent_address" value="<?= e($user['permanent_address'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="moveInDate">Move-in Date</label>
                <input type="text" id="moveInDate" value="<?= $renter && $renter['move_in_date'] ? date('m/d/Y', strtotime($renter['move_in_date'])) : '' ?>" readonly>
            </div>
        </div>
    </div>

    <!-- Account Security -->
    <div class="form-section">
        <h2>Account Security</h2>

        <div class="form-row">
            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" name="current_password" placeholder="Leave blank if not changing password">
            </div>
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="new_password" placeholder="Leave blank if not changing password">
            </div>
        </div>

        <div class="form-group">
            <label for="confirmPassword">Confirm New Password</label>
            <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm new password">
        </div>

        <div class="form-group">
            <label>Two-Factor Authentication</label>
            <div class="notification-item" style="border-bottom: none; padding: 1rem 0;">
                <div class="notification-info">
                    <h4>Enable 2FA</h4>
                    <p>Add an extra layer of security to your account</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="two_fa_enabled">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
    </div>
</form>

<!-- Danger Zone -->
<div class="danger-zone">
    <h2>Danger Zone</h2>
    <p>Once you delete your account, there is no going back. Please be certain.</p>
    <button type="button" class="btn-danger" onclick="confirmDeleteAccount()">
        <i class="fas fa-trash-alt"></i> Delete Account
    </button>
</div>

<script>
function confirmDeleteAccount() {
    Swal.fire({
        title: 'Delete Account?',
        html: '<p>This action <strong>cannot be undone</strong>. All your data will be permanently removed.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete My Account',
        cancelButtonText: 'Cancel'
    }).then(function(result) {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Type "DELETE" to confirm',
                input: 'text',
                inputPlaceholder: 'Type DELETE here',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                confirmButtonText: 'Confirm Deletion',
                inputValidator: function(value) {
                    if (value !== 'DELETE') {
                        return 'Please type DELETE to confirm';
                    }
                }
            }).then(function(confirmResult) {
                if (confirmResult.isConfirmed) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Request Submitted',
                        text: 'Account deletion request has been submitted. You will receive a confirmation email.',
                        confirmButtonColor: '#2c5aa0'
                    });
                }
            });
        }
    });
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/renter.php';
?>
