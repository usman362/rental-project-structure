<?php
$title = 'Renter Portal';
$active = 'portal';
ob_start();
?>

<style>
    /* Tab Navigation */
    .portal-tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #eaeaea;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .portal-tab {
        padding: 1rem 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
        color: #666;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
        position: relative;
        bottom: -2px;
        white-space: nowrap;
    }

    .portal-tab:hover {
        color: #2c5aa0;
    }

    .portal-tab.active {
        color: #2c5aa0;
        border-bottom-color: #2c5aa0;
    }

    /* Tab Content */
    .portal-content {
        display: none;
    }

    .portal-content.active {
        display: block;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #eaeaea;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .stat-label {
        color: #666;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        color: #2c5aa0;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-sublabel {
        color: #999;
        font-size: 13px;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-overdue {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .status-open {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-in_progress {
        background: #fce7f3;
        color: #831843;
    }

    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    /* Cards and Sections */
    .info-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #eaeaea;
        margin-bottom: 1.5rem;
    }

    .info-card h3 {
        color: #333;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eaeaea;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        color: #333;
        font-weight: 600;
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .action-btn {
        background: linear-gradient(135deg, #2c5aa0 0%, #1d3a6e 100%);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        text-decoration: none;
        text-align: center;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(44, 90, 160, 0.3);
    }

    .action-btn i {
        font-size: 1.5rem;
    }

    /* Tables */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .data-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #eaeaea;
    }

    .data-table th {
        padding: 1rem;
        text-align: left;
        color: #666;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #eaeaea;
        color: #333;
    }

    .data-table tbody tr:hover {
        background: #f8f9fa;
    }

    /* Forms */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #2c5aa0;
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #2c5aa0 0%, #1d3a6e 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(44, 90, 160, 0.3);
    }

    /* Activity Feed */
    .activity-feed {
        margin-top: 2rem;
    }

    .activity-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-left: 3px solid #2c5aa0;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 1rem;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #2c5aa0;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
    }

    .activity-description {
        color: #666;
        font-size: 13px;
        margin-bottom: 0.5rem;
    }

    .activity-date {
        color: #999;
        font-size: 12px;
    }

    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #999;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin-bottom: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }

        .portal-tabs {
            overflow-x: auto;
        }

        .data-table {
            font-size: 13px;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem;
        }
    }
</style>

<!-- Dashboard Header with Welcome Message -->
<div class="content-header" style="margin-bottom: 2rem;">
    <h1>Welcome, <?= e($user['first_name'] ?? $user['username'] ?? 'Renter') ?></h1>
    <p style="color: #666; margin-top: 0.5rem;">Manage your lease, payments, and maintenance requests</p>
</div>

<!-- Tab Navigation -->
<div class="portal-tabs">
    <button class="portal-tab active" data-tab="dashboard" onclick="switchTab(event, 'dashboard')">
        <i class="fas fa-home"></i> Dashboard
    </button>
    <button class="portal-tab" data-tab="payments" onclick="switchTab(event, 'payments')">
        <i class="fas fa-credit-card"></i> Payments
    </button>
    <button class="portal-tab" data-tab="maintenance" onclick="switchTab(event, 'maintenance')">
        <i class="fas fa-tools"></i> Maintenance
    </button>
    <button class="portal-tab" data-tab="documents" onclick="switchTab(event, 'documents')">
        <i class="fas fa-file-alt"></i> Documents
    </button>
    <button class="portal-tab" data-tab="messages" onclick="switchTab(event, 'messages')">
        <i class="fas fa-envelope"></i> Messages
    </button>
</div>

<!-- ============ DASHBOARD TAB ============ -->
<div id="dashboard" class="portal-content active">
    <!-- Lease Information Card -->
    <?php if ($renter): ?>
    <div class="info-card">
        <h3><i class="fas fa-building"></i> Your Lease Information</h3>
        <div class="info-row">
            <span class="info-label">Property</span>
            <span class="info-value"><?= e($renter['property_name'] ?? 'N/A') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Address</span>
            <span class="info-value"><?= e($renter['address'] ?? 'N/A') ?></span>
        </div>
        <?php if (!empty($renter['city'])): ?>
        <div class="info-row">
            <span class="info-label">City, State</span>
            <span class="info-value"><?= e($renter['city'] ?? '') ?><?php if (!empty($renter['state'])): ?>, <?= e($renter['state']) ?><?php endif; ?></span>
        </div>
        <?php endif; ?>
        <div class="info-row">
            <span class="info-label">Monthly Rent</span>
            <span class="info-value">$<?= number_format((float)($renter['monthly_rent'] ?? 0), 2) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Lease Start</span>
            <span class="info-value">
                <?php
                if (!empty($renter['move_in_date'])) {
                    $date = new DateTime($renter['move_in_date']);
                    echo e($date->format('M d, Y'));
                }
                ?>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Lease End</span>
            <span class="info-value">
                <?php
                if (!empty($renter['lease_end'])) {
                    $date = new DateTime($renter['lease_end']);
                    echo e($date->format('M d, Y'));
                } else {
                    echo 'N/A';
                }
                ?>
            </span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Action Buttons -->
    <div class="quick-actions">
        <button class="action-btn" onclick="switchTab(null, 'payments')">
            <i class="fas fa-money-bill-wave"></i> Pay Rent
        </button>
        <button class="action-btn" onclick="switchTab(null, 'maintenance')">
            <i class="fas fa-wrench"></i> Submit Request
        </button>
        <button class="action-btn" onclick="switchTab(null, 'documents')">
            <i class="fas fa-folder-open"></i> View Documents
        </button>
        <a href="<?= route('renter.help') ?>" class="action-btn" style="text-decoration: none;">
            <i class="fas fa-headset"></i> Contact Us
        </a>
    </div>

    <!-- Payment Summary Cards -->
    <div style="margin-bottom: 2rem;">
        <h3 style="color: #333; margin-bottom: 1rem; font-weight: 600;">Payment Summary</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-check-circle"></i> Total Paid</div>
                <div class="stat-value">$<?= number_format($paymentStats['totalPaid'], 2) ?></div>
                <div class="stat-sublabel">All-time payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-hourglass-half"></i> Pending</div>
                <div class="stat-value">$<?= number_format($paymentStats['pending'], 2) ?></div>
                <div class="stat-sublabel">Due soon</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-exclamation-circle"></i> Overdue</div>
                <div class="stat-value">$<?= number_format($paymentStats['overdue'], 2) ?></div>
                <div class="stat-sublabel">Action required</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-calendar"></i> Next Due</div>
                <div class="stat-value">$<?= number_format($paymentStats['nextAmount'], 2) ?></div>
                <div class="stat-sublabel">
                    <?php
                    if ($paymentStats['nextDue']) {
                        $date = new DateTime($paymentStats['nextDue']);
                        echo e($date->format('M d, Y'));
                    } else {
                        echo 'No upcoming';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <?php if (!empty($recentActivity)): ?>
    <div class="info-card">
        <h3><i class="fas fa-history"></i> Recent Activity</h3>
        <div class="activity-feed">
            <?php foreach ($recentActivity as $item): ?>
            <div class="activity-item">
                <div class="activity-icon">
                    <?php if ($item['type'] === 'payment'): ?>
                        <i class="fas fa-credit-card"></i>
                    <?php else: ?>
                        <i class="fas fa-wrench"></i>
                    <?php endif; ?>
                </div>
                <div class="activity-content">
                    <div class="activity-title"><?= e($item['title']) ?></div>
                    <div class="activity-description"><?= e($item['description']) ?></div>
                    <div class="activity-date">
                        <?php
                        if (!empty($item['date'])) {
                            $date = new DateTime($item['date']);
                            echo e($date->format('M d, Y \a\t H:i A'));
                        }
                        ?>
                    </div>
                </div>
                <div style="text-align: right; flex-shrink: 0;">
                    <span class="status-badge status-<?= e($item['status']) ?>">
                        <?= e(ucfirst(str_replace('_', ' ', $item['status']))) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>No recent activity</p>
        <small>When you make payments or submit requests, they'll appear here</small>
    </div>
    <?php endif; ?>
</div>

<!-- ============ PAYMENTS TAB ============ -->
<div id="payments" class="portal-content">
    <!-- Payment Summary Cards -->
    <div style="margin-bottom: 2rem;">
        <h3 style="color: #333; margin-bottom: 1rem; font-weight: 600;">Payment Summary</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-check-circle"></i> Total Paid</div>
                <div class="stat-value">$<?= number_format($paymentStats['totalPaid'], 2) ?></div>
                <div class="stat-sublabel">All-time payments</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-hourglass-half"></i> Pending</div>
                <div class="stat-value">$<?= number_format($paymentStats['pending'], 2) ?></div>
                <div class="stat-sublabel">Due soon</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-exclamation-circle"></i> Overdue</div>
                <div class="stat-value">$<?= number_format($paymentStats['overdue'], 2) ?></div>
                <div class="stat-sublabel">Action required</div>
            </div>
        </div>
    </div>

    <!-- Make Payment Section -->
    <div class="info-card">
        <h3><i class="fas fa-credit-card"></i> Make a Payment</h3>
        <form method="POST" action="/renter/payments" style="max-width: 400px;">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="amount">Payment Amount</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" placeholder="Enter amount" value="<?= old('amount') ?>" required>
            </div>
            <div class="form-group">
                <label for="method">Payment Method</label>
                <select id="method" name="method" required>
                    <option value="">Select payment method</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="check">Check</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Submit Payment</button>
        </form>
    </div>

    <!-- Payment History -->
    <div class="info-card" style="margin-top: 2rem;">
        <h3><i class="fas fa-history"></i> Payment History</h3>
        <?php if (!empty($payments)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                <tr>
                    <td>
                        <?php
                        $dueDate = !empty($payment['paid_date']) ? $payment['paid_date'] : $payment['due_date'];
                        if (!empty($dueDate)) {
                            $date = new DateTime($dueDate);
                            echo e($date->format('M d, Y'));
                        }
                        ?>
                    </td>
                    <td><strong>$<?= number_format((float)($payment['amount'] ?? 0), 2) ?></strong></td>
                    <td><?= e(ucfirst(str_replace('_', ' ', $payment['method'] ?? 'N/A'))) ?></td>
                    <td>
                        <span class="status-badge status-<?= e($payment['status'] ?? 'pending') ?>">
                            <?= e(ucfirst($payment['status'] ?? 'pending')) ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($payment['receipt_number'])): ?>
                            <a href="#" style="color: #2c5aa0; text-decoration: none; font-weight: 600;">
                                <i class="fas fa-download"></i> Receipt
                            </a>
                        <?php else: ?>
                            <span style="color: #999;">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-file-invoice"></i>
            <p>No payment history</p>
            <small>Your payments will appear here</small>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ============ MAINTENANCE TAB ============ -->
<div id="maintenance" class="portal-content">
    <!-- Submit New Request -->
    <div class="info-card" style="margin-bottom: 2rem;">
        <h3><i class="fas fa-plus-circle"></i> Submit New Maintenance Request</h3>
        <form method="POST" action="/renter/maintenance" style="max-width: 600px;">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select category</option>
                    <option value="plumbing">Plumbing</option>
                    <option value="electrical">Electrical</option>
                    <option value="heating_cooling">Heating/Cooling</option>
                    <option value="appliances">Appliances</option>
                    <option value="flooring">Flooring</option>
                    <option value="walls">Walls/Paint</option>
                    <option value="doors_windows">Doors/Windows</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority" required>
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="emergency">Emergency</option>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Brief description of the issue" value="<?= old('title') ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Provide details about the maintenance issue" required><?= old('description') ?></textarea>
            </div>
            <button type="submit" class="btn-primary">Submit Request</button>
        </form>
    </div>

    <!-- Active Maintenance Requests -->
    <div class="info-card" style="margin-bottom: 2rem;">
        <h3><i class="fas fa-clock"></i> Active Requests</h3>
        <?php
        $activeRequests = array_filter($maintenanceRequests, function($req) {
            return $req['status'] !== 'completed' && $req['status'] !== 'cancelled';
        });
        ?>
        <?php if (!empty($activeRequests)): ?>
            <?php foreach ($activeRequests as $request): ?>
            <div style="padding: 1.5rem; border: 1px solid #eaeaea; border-radius: 8px; margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div>
                        <h4 style="color: #333; margin-bottom: 0.25rem; font-weight: 600;"><?= e($request['title'] ?? 'Maintenance Request') ?></h4>
                        <p style="color: #666; font-size: 13px; margin: 0;">
                            <i class="fas fa-tag"></i> <?= e(ucfirst($request['category'] ?? 'Other')) ?>
                        </p>
                    </div>
                    <span class="status-badge status-<?= e($request['status'] ?? 'open') ?>">
                        <?= e(ucfirst(str_replace('_', ' ', $request['status'] ?? 'open'))) ?>
                    </span>
                </div>
                <p style="color: #666; margin: 0.5rem 0; font-size: 14px;"><?= e($request['description'] ?? '') ?></p>
                <div style="display: flex; gap: 2rem; margin-top: 1rem; color: #999; font-size: 13px;">
                    <span><i class="fas fa-arrow-up"></i> Priority: <strong><?= e(ucfirst($request['priority'] ?? 'medium')) ?></strong></span>
                    <span><i class="fas fa-calendar"></i> Submitted: <strong>
                        <?php
                        if (!empty($request['created_at'])) {
                            $date = new DateTime($request['created_at']);
                            echo e($date->format('M d, Y'));
                        }
                        ?>
                    </strong></span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <p>No active maintenance requests</p>
            <small>Great! Everything is running smoothly</small>
        </div>
        <?php endif; ?>
    </div>

    <!-- Request History -->
    <div class="info-card">
        <h3><i class="fas fa-list"></i> Request History</h3>
        <?php if (!empty($maintenanceRequests)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($maintenanceRequests as $request): ?>
                <tr>
                    <td><?= e($request['title'] ?? 'Request') ?></td>
                    <td><?= e(ucfirst($request['category'] ?? 'Other')) ?></td>
                    <td><?= e(ucfirst($request['priority'] ?? 'medium')) ?></td>
                    <td>
                        <span class="status-badge status-<?= e($request['status'] ?? 'open') ?>">
                            <?= e(ucfirst(str_replace('_', ' ', $request['status'] ?? 'open'))) ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        if (!empty($request['created_at'])) {
                            $date = new DateTime($request['created_at']);
                            echo e($date->format('M d, Y'));
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No maintenance requests</p>
            <small>Submit your first maintenance request above</small>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ============ DOCUMENTS TAB ============ -->
<div id="documents" class="portal-content">
    <h2 style="color: #2c5aa0; margin-bottom: 1.5rem;"><i class="fas fa-file-alt"></i> My Documents</h2>
    <div class="info-card">
        <h3><i class="fas fa-file-contract"></i> Lease Agreement</h3>
        <div class="info-row">
            <span class="info-label">Current Lease</span>
            <span class="info-value"><a href="#" style="color: #2c5aa0;">Lease_2024.pdf</a></span>
        </div>
        <div class="info-row">
            <span class="info-label">Signed Date</span>
            <span class="info-value">Jan 1, 2024</span>
        </div>
    </div>
    <div class="info-card">
        <h3><i class="fas fa-receipt"></i> Payment Receipts</h3>
        <div class="info-row">
            <span class="info-label">October 2024</span>
            <span class="info-value"><a href="#" style="color: #2c5aa0;">Receipt_Oct2024.pdf</a></span>
        </div>
        <div class="info-row">
            <span class="info-label">September 2024</span>
            <span class="info-value"><a href="#" style="color: #2c5aa0;">Receipt_Sep2024.pdf</a></span>
        </div>
    </div>
    <div class="info-card">
        <h3><i class="fas fa-shield-alt"></i> Insurance Documents</h3>
        <div class="info-row">
            <span class="info-label">Renter's Insurance</span>
            <span class="info-value"><a href="#" style="color: #2c5aa0;">Insurance_Policy.pdf</a></span>
        </div>
    </div>
</div>

<!-- ============ MESSAGES TAB ============ -->
<div id="messages" class="portal-content">
    <h2 style="color: #2c5aa0; margin-bottom: 1.5rem;"><i class="fas fa-envelope"></i> Messages</h2>
    <div class="info-card" style="border-left: 3px solid #2c5aa0;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h4 style="margin: 0 0 0.5rem 0;">Property Manager</h4>
                <p style="color: #666; margin: 0;">Your maintenance request for the kitchen faucet has been scheduled. A technician will visit on Oct 20.</p>
            </div>
            <small style="color: #999; white-space: nowrap;">2 days ago</small>
        </div>
    </div>
    <div class="info-card" style="border-left: 3px solid #10b981;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h4 style="margin: 0 0 0.5rem 0;">Payment Confirmation</h4>
                <p style="color: #666; margin: 0;">Your October rent payment of $3,450 has been received. Thank you!</p>
            </div>
            <small style="color: #999; white-space: nowrap;">5 days ago</small>
        </div>
    </div>
    <div class="info-card" style="border-left: 3px solid #f59e0b;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h4 style="margin: 0 0 0.5rem 0;">Community Notice</h4>
                <p style="color: #666; margin: 0;">Annual property inspection scheduled for November 15. Please ensure access to all rooms.</p>
            </div>
            <small style="color: #999; white-space: nowrap;">1 week ago</small>
        </div>
    </div>
</div>

<script>
function switchTab(event, tabName) {
    // Prevent default if event exists
    if (event) {
        event.preventDefault();
    }

    // Hide all tab contents
    const contents = document.querySelectorAll('.portal-content');
    contents.forEach(content => {
        content.classList.remove('active');
    });

    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.portal-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });

    // Show the selected tab content
    const selectedContent = document.getElementById(tabName);
    if (selectedContent) {
        selectedContent.classList.add('active');
    }

    // Add active class to clicked tab
    if (event && event.target.closest('.portal-tab')) {
        event.target.closest('.portal-tab').classList.add('active');
    } else {
        // Find and activate the corresponding tab button
        tabs.forEach(tab => {
            if (tab.onclick && tab.onclick.toString().includes(`'${tabName}'`)) {
                tab.classList.add('active');
            }
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/renter.php';
?>
