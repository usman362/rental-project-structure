<?php
$title = 'My Profile';
$active = 'profile';
ob_start();
?>

<div class="content-header">
    <h1>My Profile</h1>
    <div class="content-actions">
        <button form="profileForm" type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</div>

<!-- Profile Header -->
<div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
    <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #2c5aa0, #3a6bc5); display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: 600; flex-shrink: 0;">
        <?php
            $initials = strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? 'U', 0, 1));
            echo e($initials);
        ?>
    </div>
    <div>
        <h2 style="color: #333; margin-bottom: 5px;"><?= e($user['first_name'] ?? '') ?> <?= e($user['last_name'] ?? '') ?></h2>
        <p style="color: #666; margin-bottom: 10px;">
            Tenant since <?= $renter && $renter['move_in_date'] ? date('F j, Y', strtotime($renter['move_in_date'])) : 'N/A' ?>
        </p>
        <span style="display: inline-block; background-color: rgba(46, 204, 113, 0.1); color: #27ae60; padding: 4px 12px; border-radius: 20px; font-size: 14px; font-weight: 500;">
            <?= $renter && $renter['status'] === 'active' ? 'Active Lease' : 'Inactive' ?>
        </span>
    </div>
</div>

<!-- Personal Information -->
<form id="profileForm" method="POST" action="<?= route('renter.profile') ?>">
    <?= csrf_field() ?>

    <div style="background-color: #f9f9f9; border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1.5rem; color: #2c5aa0;">Personal Information</h2>

        <div style="display: flex; gap: 20px; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">First Name *</label>
                <input type="text" name="first_name" value="<?= e($user['first_name'] ?? '') ?>" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Last Name *</label>
                <input type="text" name="last_name" value="<?= e($user['last_name'] ?? '') ?>" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Email Address *</label>
                <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Phone Number *</label>
                <input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Date of Birth</label>
                <input type="date" name="dob" value="<?= e($user['dob'] ?? '') ?>" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Emergency Contact</label>
                <input type="text" name="emergency_contact" value="<?= e($renter['emergency_contact'] ?? '') ?>" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Emergency Phone</label>
                <input type="tel" name="emergency_phone" value="<?= e($user['emergency_phone'] ?? '') ?>" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Relationship</label>
                <input type="text" name="relationship" value="<?= e($user['relationship'] ?? '') ?>" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Occupation</label>
            <input type="text" name="occupation" value="<?= e($user['occupation'] ?? '') ?>" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Bio/Notes</label>
            <textarea name="bio" rows="3" placeholder="Tell us a little about yourself..." style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s; font-family: inherit;"><?= e($renter['notes'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- Address Information -->
    <div style="background-color: #f9f9f9; border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1.5rem; color: #2c5aa0;">Address Information</h2>

        <div style="display: flex; gap: 20px; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Current Address</label>
                <input type="text" name="current_address" value="<?= e($renter['address'] ?? '') ?>" readonly style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; background-color: #f5f5f5; color: #666;" />
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Unit Number</label>
                <input type="text" name="unit_number" value="<?= e($renter['unit_number'] ?? '') ?>" readonly style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; background-color: #f5f5f5; color: #666;" />
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Permanent Address</label>
                <input type="text" name="permanent_address" value="<?= e($user['permanent_address'] ?? '') ?>" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Move-in Date</label>
                <input type="date" name="move_in_date" value="<?= e($renter['move_in_date'] ?? '') ?>" readonly style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; background-color: #f5f5f5; color: #666;" />
            </div>
        </div>
    </div>

    <!-- Account Security -->
    <div style="background-color: #f9f9f9; border-radius: 12px; padding: 2rem; margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1.5rem; color: #2c5aa0;">Account Security</h2>

        <div style="display: flex; gap: 20px; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Current Password</label>
                <input type="password" name="current_password" placeholder="Leave blank if not changing password" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">New Password</label>
                <input type="password" name="new_password" placeholder="Leave blank if not changing password" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s, box-shadow 0.3s;" />
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 1rem; color: #444; font-weight: 500; font-size: 14px;">Two-Factor Authentication</label>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #eee;">
                <div>
                    <h4 style="color: #333; margin-bottom: 5px;">Enable 2FA</h4>
                    <p style="color: #666; font-size: 14px;">Add an extra layer of security to your account</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 30px;">
                    <input type="checkbox" name="two_fa_enabled" style="opacity: 0; width: 0; height: 0;" />
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;" onclick="toggleSwitch(this)"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div style="background-color: #fff5f5; border: 2px solid #ffebee; border-radius: 12px; padding: 2rem;">
        <h2 style="margin-bottom: 1rem; color: #e74c3c;">Danger Zone</h2>
        <p style="color: #666; margin-bottom: 1.5rem;">Once you delete your account, there is no going back. Please be certain.</p>
        <button type="button" class="btn btn-secondary" style="border-color: #e74c3c; color: #e74c3c;" onclick="confirmDelete()">
            <i class="fas fa-trash-alt"></i> Delete Account
        </button>
    </div>
</form>

<script>
function toggleSwitch(element) {
    const slider = element;
    const input = slider.previousElementSibling;
    if (input.checked) {
        input.checked = false;
        slider.style.backgroundColor = '#ccc';
    } else {
        input.checked = true;
        slider.style.backgroundColor = '#2c5aa0';
    }
}

function confirmDelete() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        const confirmDelete = prompt('Type "DELETE" to confirm account deletion:');
        if (confirmDelete === 'DELETE') {
            alert('Account deletion request submitted. You will receive a confirmation email.');
        } else {
            alert('Account deletion cancelled.');
        }
    }
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/renter.php';
?>
