<?php
$title = 'Help Center';
$active = 'help';
ob_start();
?>

<div class="content-header">
    <h1>Help Center</h1>
    <p style="color: #666; margin-top: 0.5rem;">Find answers to your questions and submit support requests</p>
</div>

<!-- Help Categories Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 2rem; margin-bottom: 3rem;">
    <!-- Payments & Billing -->
    <div style="background: white; border-radius: 10px; padding: 1.5rem; border: 1px solid #eee; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
        <div style="width: 60px; height: 60px; border-radius: 10px; background-color: rgba(44, 90, 160, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 24px; color: #2c5aa0;">
            <i class="fas fa-credit-card"></i>
        </div>
        <h3 style="color: #333; margin-bottom: 10px;">Payments & Billing</h3>
        <p style="color: #666; font-size: 14px; line-height: 1.5;">Learn about rent payments, payment methods, billing, and invoices.</p>
    </div>

    <!-- Maintenance Requests -->
    <div style="background: white; border-radius: 10px; padding: 1.5rem; border: 1px solid #eee; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
        <div style="width: 60px; height: 60px; border-radius: 10px; background-color: rgba(44, 90, 160, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 24px; color: #2c5aa0;">
            <i class="fas fa-tools"></i>
        </div>
        <h3 style="color: #333; margin-bottom: 10px;">Maintenance Requests</h3>
        <p style="color: #666; font-size: 14px; line-height: 1.5;">Submit and track maintenance requests for your unit.</p>
    </div>

    <!-- Lease & Documents -->
    <div style="background: white; border-radius: 10px; padding: 1.5rem; border: 1px solid #eee; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
        <div style="width: 60px; height: 60px; border-radius: 10px; background-color: rgba(44, 90, 160, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 24px; color: #2c5aa0;">
            <i class="fas fa-file-alt"></i>
        </div>
        <h3 style="color: #333; margin-bottom: 10px;">Lease & Documents</h3>
        <p style="color: #666; font-size: 14px; line-height: 1.5;">Access your lease agreement and important documents.</p>
    </div>

    <!-- Account Settings -->
    <div style="background: white; border-radius: 10px; padding: 1.5rem; border: 1px solid #eee; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
        <div style="width: 60px; height: 60px; border-radius: 10px; background-color: rgba(44, 90, 160, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 24px; color: #2c5aa0;">
            <i class="fas fa-cog"></i>
        </div>
        <h3 style="color: #333; margin-bottom: 10px;">Account Settings</h3>
        <p style="color: #666; font-size: 14px; line-height: 1.5;">Manage your profile, password, and account preferences.</p>
    </div>
</div>

<!-- FAQ Section -->
<div style="margin-top: 3rem; margin-bottom: 3rem;">
    <h2 style="color: #2c5aa0; margin-bottom: 1.5rem;">Frequently Asked Questions</h2>

    <div style="background: white; border-radius: 12px; padding: 1rem;">
        <?php foreach ($faqs as $index => $faq): ?>
            <div style="border-bottom: 1px solid #eee; padding: 1.5rem 0;" class="faq-item" id="faq-<?= $index ?>">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; user-select: none;" onclick="toggleFaq(this)">
                    <h4 style="color: #333; font-weight: 500; margin: 0;"><?= e($faq['question']) ?></h4>
                    <i class="fas fa-chevron-down" style="color: #2c5aa0; transition: transform 0.3s;"></i>
                </div>
                <p style="color: #666; line-height: 1.6; margin-top: 1rem; display: none;">
                    <?= e($faq['answer']) ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Support Request Form -->
<div style="margin-top: 3rem; margin-bottom: 3rem;">
    <h2 style="color: #2c5aa0; margin-bottom: 1.5rem;">Submit a Support Request</h2>

    <form id="supportForm" method="POST" action="<?= route('renter.help') ?>" style="background-color: #f9f9f9; border-radius: 12px; padding: 2rem;">
        <?= csrf_field() ?>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Subject *</label>
            <input type="text" name="subject" placeholder="Brief description of your issue" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;" />
        </div>

        <div style="display: flex; gap: 20px; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Category *</label>
                <select name="category" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;">
                    <option value="">Select a category</option>
                    <option value="payments_billing">Payments & Billing</option>
                    <option value="maintenance">Maintenance Requests</option>
                    <option value="lease_documents">Lease & Documents</option>
                    <option value="account_settings">Account Settings</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: #444; font-weight: 500; font-size: 14px;">Message *</label>
            <textarea name="message" rows="5" placeholder="Please provide detailed information about your issue..." required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; font-family: inherit;"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="background-color: #2c5aa0; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer;">
            <i class="fas fa-paper-plane"></i> Submit Request
        </button>
    </form>
</div>

<!-- Recent Support Requests -->
<?php if (!empty($supportRequests)): ?>
    <div style="margin-top: 3rem; margin-bottom: 3rem;">
        <h2 style="color: #2c5aa0; margin-bottom: 1.5rem;">Recent Support Requests</h2>

        <div style="background: white; border-radius: 12px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #eaeaea; background-color: #f9f9f9;">
                        <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Subject</th>
                        <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Category</th>
                        <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Status</th>
                        <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($supportRequests as $request): ?>
                        <tr style="border-bottom: 1px solid #eaeaea;">
                            <td style="padding: 1rem; color: #333; font-weight: 500;"><?= e($request['subject']) ?></td>
                            <td style="padding: 1rem; color: #666;"><?= e($request['category']) ?></td>
                            <td style="padding: 1rem;">
                                <span style="padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 12px; font-weight: 600;
                                    <?php
                                        $statusColor = match($request['status'] ?? 'open') {
                                            'closed' => 'background: #d1fae5; color: #065f46;',
                                            'in_progress' => 'background: #dbeafe; color: #0c4a6e;',
                                            'open' => 'background: #fef3c7; color: #92400e;',
                                            default => 'background: #e5e7eb; color: #374151;'
                                        };
                                        echo $statusColor;
                                    ?>">
                                    <?= e(ucfirst(str_replace('_', ' ', $request['status']))) ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; color: #666; font-size: 14px;">
                                <?php
                                    if (isset($request['created_at'])) {
                                        $date = new DateTime($request['created_at']);
                                        echo e($date->format('M d, Y H:i'));
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Emergency Contact Section -->
<div style="background-color: #fff5f5; border: 2px solid #e74c3c; border-radius: 12px; padding: 1.5rem; margin-top: 3rem;">
    <div style="display: flex; align-items: center; gap: 10px; color: #e74c3c; margin-bottom: 1rem;">
        <i class="fas fa-exclamation-triangle" style="font-size: 20px;"></i>
        <h3 style="margin: 0; font-size: 18px;">Emergency Contact</h3>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <div style="font-size: 12px; color: #666;">Property Manager</div>
            <div style="font-weight: 600; color: #333;">(307) 228-4667</div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <div style="font-size: 12px; color: #666;">24/7 Emergency Line</div>
            <div style="font-weight: 600; color: #333;">(307) 228-4667</div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <div style="font-size: 12px; color: #666;">Email Support</div>
            <div style="font-weight: 600; color: #333;">support@sotelomanage.com</div>
        </div>
    </div>
</div>

<script>
function toggleFaq(element) {
    const parent = element.closest('.faq-item');
    const answer = parent.querySelector('p');
    const icon = element.querySelector('i');

    if (answer.style.display === 'none' || answer.style.display === '') {
        answer.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/renter.php';
?>
