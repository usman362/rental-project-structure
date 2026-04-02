<?php
$title = 'Renter Portal';
$active = 'portal';
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

    .quick-actions {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        background-color: #f0f7ff;
        color: #2c5aa0;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        text-decoration: none;
    }

    .action-btn:hover {
        background-color: #2c5aa0;
        color: white;
    }

    /* Property Card */
    .property-card {
        background: linear-gradient(135deg, #2c5aa0 0%, #1d3a6e 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .property-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .property-address {
        font-size: 1.2rem;
        font-weight: 600;
        color: white;
    }

    .property-status {
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
    }

    .property-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 1rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: white;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #eee;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-icon.rent {
        background-color: rgba(44, 90, 160, 0.1);
        color: #2c5aa0;
    }

    .stat-icon.maintenance {
        background-color: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }

    .stat-icon.document {
        background-color: rgba(241, 196, 15, 0.1);
        color: #f1c40f;
    }

    .stat-icon.message {
        background-color: rgba(155, 89, 182, 0.1);
        color: #9b59b6;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        line-height: 1;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }

    /* Section Header */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-header h2 {
        color: #2c5aa0;
        font-size: 1.3rem;
        font-weight: 600;
    }

    /* Payment Section */
    .payment-section {
        background-color: #f9f9f9;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .payment-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border-left: 4px solid #2c5aa0;
        margin-bottom: 1rem;
    }

    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .payment-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-paid {
        background-color: rgba(46, 204, 113, 0.1);
        color: #27ae60;
    }

    .status-due {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .status-pending {
        background-color: rgba(241, 196, 15, 0.1);
        color: #f39c12;
    }

    .status-overdue {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .payment-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .payment-date {
        font-size: 13px;
        color: #666;
    }

    /* Maintenance Requests */
    .requests-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 1.5rem;
    }

    .request-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #eee;
        transition: all 0.3s;
    }

    .request-card:hover {
        border-color: #2c5aa0;
        box-shadow: 0 5px 15px rgba(44, 90, 160, 0.1);
    }

    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .request-title {
        font-weight: 600;
        color: #333;
    }

    .request-date {
        font-size: 12px;
        color: #666;
    }

    .request-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-open {
        background-color: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }

    .status-in-progress, .status-in_progress {
        background-color: rgba(241, 196, 15, 0.1);
        color: #f39c12;
    }

    .status-completed {
        background-color: rgba(46, 204, 113, 0.1);
        color: #27ae60;
    }

    /* Tabs */
    .tabs {
        display: flex;
        border-bottom: 1px solid #eee;
        margin-bottom: 2rem;
        overflow-x: auto;
    }

    .tab {
        padding: 12px 24px;
        background: none;
        border: none;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        position: relative;
        white-space: nowrap;
        flex-shrink: 0;
        font-size: 14px;
        font-family: inherit;
    }

    .tab:hover {
        color: #2c5aa0;
    }

    .tab.active {
        color: #2c5aa0;
    }

    .tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #2c5aa0;
        border-radius: 3px 3px 0 0;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
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
        transition: all 0.3s;
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
        font-size: 14px;
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

    /* Forms */
    .form-section {
        background-color: #f9f9f9;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
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
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #2c5aa0;
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }

    .form-group textarea {
        resize: vertical;
    }

    .file-upload {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload:hover {
        border-color: #2c5aa0;
        background-color: #f0f7ff;
    }

    .file-upload i {
        font-size: 2rem;
        color: #2c5aa0;
        margin-bottom: 1rem;
    }

    /* Documents Section */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 1.5rem;
    }

    .document-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #eee;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
    }

    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #2c5aa0;
    }

    .document-icon {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        background-color: rgba(44, 90, 160, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 24px;
        color: #2c5aa0;
    }

    .document-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .document-date {
        font-size: 12px;
        color: #666;
    }

    /* Messages Section */
    .messages-list {
        margin-top: 1.5rem;
    }

    .message-item {
        display: flex;
        gap: 15px;
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s;
    }

    .message-item:hover {
        background-color: #f9f9f9;
    }

    .message-item.unread {
        background-color: #f0f7ff;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2c5aa0, #3a6bc5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        flex-shrink: 0;
        font-size: 14px;
    }

    .message-content {
        flex: 1;
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .message-sender {
        font-weight: 600;
        color: #333;
    }

    .message-time {
        font-size: 12px;
        color: #666;
    }

    .message-preview {
        color: #666;
        font-size: 14px;
        line-height: 1.4;
    }

    /* Empty State */
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
        .dashboard-header {
            flex-direction: column;
            gap: 1rem;
        }

        .quick-actions {
            flex-wrap: wrap;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .property-details {
            grid-template-columns: 1fr 1fr;
        }

        .tabs {
            overflow-x: auto;
        }

        .tab {
            font-size: 13px;
            padding: 10px 16px;
        }

        .documents-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <h1>Welcome back, <?= e($user['first_name'] ?? $user['username'] ?? 'Renter') ?></h1>
    <div class="quick-actions">
        <button class="action-btn" onclick="switchToTab('payments')">
            <i class="fas fa-credit-card"></i> Pay Rent
        </button>
        <button class="action-btn" onclick="switchToTab('maintenance')">
            <i class="fas fa-tools"></i> Request Maintenance
        </button>
        <button class="action-btn" onclick="switchToTab('messages')">
            <i class="fas fa-envelope"></i> Message Manager
        </button>
    </div>
</div>

<!-- Property Information -->
<?php if ($renter): ?>
<div class="property-card">
    <div class="property-header">
        <div class="property-address"><?= e($renter['address'] ?? $renter['property_name'] ?? 'N/A') ?><?php if (!empty($renter['city'])): ?>, <?= e($renter['city']) ?><?php endif; ?><?php if (!empty($renter['state'])): ?>, <?= e($renter['state']) ?><?php endif; ?></div>
        <div class="property-status">Active Lease</div>
    </div>
    <div class="property-details">
        <div class="detail-item">
            <div class="detail-label">Lease Period</div>
            <div class="detail-value"><?php
                $start = !empty($renter['move_in_date']) ? (new DateTime($renter['move_in_date']))->format('M j, Y') : 'N/A';
                $end = !empty($renter['lease_end']) ? (new DateTime($renter['lease_end']))->format('M j, Y') : 'N/A';
                echo e($start) . ' - ' . e($end);
            ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Monthly Rent</div>
            <div class="detail-value">$<?= number_format((float)($renter['monthly_rent'] ?? 0), 2) ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Next Payment Due</div>
            <div class="detail-value"><?php
                if ($paymentStats['nextDue']) {
                    echo e((new DateTime($paymentStats['nextDue']))->format('M j, Y'));
                } else {
                    echo 'No upcoming';
                }
            ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Security Deposit</div>
            <div class="detail-value">$<?= number_format((float)($renter['security_deposit'] ?? 0), 2) ?></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabs Navigation -->
<div class="tabs" id="mainTabs">
    <button class="tab active" data-tab="dashboard">Dashboard</button>
    <button class="tab" data-tab="payments">Payments</button>
    <button class="tab" data-tab="maintenance">Maintenance</button>
    <button class="tab" data-tab="documents">Documents</button>
    <button class="tab" data-tab="messages">Messages</button>
</div>

<!-- ============ DASHBOARD TAB ============ -->
<div class="tab-content active" id="dashboardTab">
    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon rent">
                    <i class="fas fa-home"></i>
                </div>
                <?php
                // Find latest payment status
                $latestPayment = !empty($payments) ? $payments[0] : null;
                $rentStatus = $latestPayment ? ($latestPayment['status'] ?? 'pending') : 'pending';
                ?>
                <div class="payment-status status-<?= e($rentStatus) ?>"><?= e(ucfirst($rentStatus)) ?></div>
            </div>
            <div class="stat-value">$<?= number_format((float)($renter['monthly_rent'] ?? 0), 0) ?></div>
            <div class="stat-label"><?php
                if ($latestPayment && !empty($latestPayment['due_date'])) {
                    $d = new DateTime($latestPayment['due_date']);
                    echo e($d->format('F')) . ' Rent';
                } else {
                    echo 'Monthly Rent';
                }
            ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon maintenance">
                    <i class="fas fa-tools"></i>
                </div>
                <?php
                $activeCount = 0;
                foreach ($maintenanceRequests as $req) {
                    if (($req['status'] ?? '') !== 'completed' && ($req['status'] ?? '') !== 'cancelled') {
                        $activeCount++;
                    }
                }
                ?>
                <?php if ($activeCount > 0): ?>
                <div class="request-status status-open">Open</div>
                <?php endif; ?>
            </div>
            <div class="stat-value"><?= $activeCount ?></div>
            <div class="stat-label">Active Requests</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon document">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="stat-value"><?= count($payments) > 0 ? count($payments) + 4 : 4 ?></div>
            <div class="stat-label">Documents</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon message">
                    <i class="fas fa-envelope"></i>
                </div>
                <?php if (!empty($recentActivity)): ?>
                <span class="badge"><?= min(count($recentActivity), 5) ?></span>
                <?php endif; ?>
            </div>
            <div class="stat-value"><?= count($recentActivity) ?></div>
            <div class="stat-label">Unread Messages</div>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="payment-section">
        <div class="section-header">
            <h2>Payment Status</h2>
            <button class="btn-secondary" onclick="switchToTab('payments')">
                <i class="fas fa-history"></i> View All
            </button>
        </div>

        <?php
        // Find the next pending/upcoming payment
        $nextPendingPayment = null;
        foreach ($payments as $p) {
            if (($p['status'] ?? '') === 'pending') {
                $nextPendingPayment = $p;
                break;
            }
        }
        // If no pending, show latest paid
        if (!$nextPendingPayment && !empty($payments)) {
            $nextPendingPayment = $payments[0];
        }
        ?>
        <?php if ($nextPendingPayment): ?>
        <div class="payment-card">
            <div class="payment-header">
                <div>
                    <h3><?php
                        $pDate = !empty($nextPendingPayment['due_date']) ? new DateTime($nextPendingPayment['due_date']) : null;
                        echo $pDate ? e($pDate->format('F Y')) . ' Rent' : 'Rent Payment';
                    ?></h3>
                    <div class="payment-date"><?php
                        $pStatus = $nextPendingPayment['status'] ?? 'pending';
                        if ($pStatus === 'paid' && !empty($nextPendingPayment['paid_date'])) {
                            echo 'Paid: ' . e((new DateTime($nextPendingPayment['paid_date']))->format('F j, Y'));
                        } elseif ($pDate) {
                            echo 'Due: ' . e($pDate->format('F j, Y'));
                        }
                    ?></div>
                </div>
                <?php
                $statusClass = $pStatus;
                $statusLabel = ucfirst($pStatus);
                if ($pStatus === 'pending') {
                    $dueDate = $nextPendingPayment['due_date'] ?? '';
                    $today = date('Y-m-d');
                    if ($dueDate < $today) {
                        $statusClass = 'overdue';
                        $statusLabel = 'Overdue';
                    } else {
                        $diff = (new DateTime($today))->diff(new DateTime($dueDate));
                        $statusClass = 'due';
                        $statusLabel = 'Due in ' . $diff->days . ' days';
                    }
                }
                ?>
                <div class="payment-status status-<?= e($statusClass) ?>"><?= e($statusLabel) ?></div>
            </div>
            <div class="payment-details">
                <div class="detail-item">
                    <div class="detail-label">Amount <?= $pStatus === 'paid' ? '' : 'Due' ?></div>
                    <div class="detail-value">$<?= number_format((float)($nextPendingPayment['amount'] ?? 0), 2) ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value"><?= e(ucfirst(str_replace('_', ' ', $nextPendingPayment['method'] ?? 'N/A'))) ?></div>
                </div>
                <?php if ($pStatus === 'paid' && !empty($nextPendingPayment['receipt_number'])): ?>
                <div class="detail-item">
                    <div class="detail-label">Receipt</div>
                    <button class="btn-secondary" style="padding: 5px 10px; font-size: 14px;" onclick="downloadDocument('receipt')">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($pStatus !== 'paid'): ?>
            <button class="btn-primary" style="margin-top: 1rem;" onclick="switchToTab('payments')">
                <i class="fas fa-credit-card"></i> Pay Now
            </button>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="empty-state" style="padding: 1.5rem;">
            <i class="fas fa-check-circle"></i>
            <p>No pending payments</p>
            <small>All payments are up to date</small>
        </div>
        <?php endif; ?>
    </div>

    <!-- Recent Maintenance Requests -->
    <div class="section-header">
        <h2>Recent Maintenance Requests</h2>
        <button class="btn-secondary" onclick="switchToTab('maintenance')">
            <i class="fas fa-plus"></i> New Request
        </button>
    </div>

    <?php
    $recentRequests = array_slice($maintenanceRequests, 0, 2);
    ?>
    <?php if (!empty($recentRequests)): ?>
    <div class="requests-grid">
        <?php foreach ($recentRequests as $request): ?>
        <div class="request-card">
            <div class="request-header">
                <div class="request-title"><?= e($request['title'] ?? 'Maintenance Request') ?></div>
                <div class="request-status status-<?= e(str_replace('_', '-', $request['status'] ?? 'open')) ?>"><?= e(ucfirst(str_replace('_', ' ', $request['status'] ?? 'open'))) ?></div>
            </div>
            <div class="request-date">Submitted: <?php
                if (!empty($request['created_at'])) {
                    echo e((new DateTime($request['created_at']))->format('M j, Y'));
                }
            ?></div>
            <p style="margin-top: 10px; color: #666; font-size: 14px;">
                <?= e(mb_strimwidth($request['description'] ?? '', 0, 100, '...')) ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state" style="padding: 1.5rem;">
        <i class="fas fa-check-circle"></i>
        <p>No maintenance requests</p>
        <small>Everything is running smoothly</small>
    </div>
    <?php endif; ?>
</div>

<!-- ============ PAYMENTS TAB ============ -->
<div class="tab-content" id="paymentsTab">
    <div class="section-header">
        <h2>Payment History</h2>
        <button class="btn-primary" onclick="setupAutoPay()">
            <i class="fas fa-cog"></i> Setup Auto-Pay
        </button>
    </div>

    <?php if (!empty($payments)): ?>
        <?php foreach ($payments as $payment): ?>
        <div class="payment-card">
            <div class="payment-header">
                <div>
                    <h3><?php
                        $pDate = !empty($payment['due_date']) ? new DateTime($payment['due_date']) : null;
                        echo $pDate ? e($pDate->format('F Y')) . ' Rent' : 'Rent Payment';
                    ?></h3>
                    <div class="payment-date"><?php
                        $pStatus = $payment['status'] ?? 'pending';
                        if ($pStatus === 'paid' && !empty($payment['paid_date'])) {
                            echo 'Paid: ' . e((new DateTime($payment['paid_date']))->format('F j, Y'));
                        } elseif ($pDate) {
                            echo 'Due: ' . e($pDate->format('F j, Y'));
                        }
                    ?></div>
                </div>
                <div class="payment-status status-<?= e($pStatus) ?>"><?= e(ucfirst($pStatus)) ?></div>
            </div>
            <div class="payment-details">
                <div class="detail-item">
                    <div class="detail-label">Amount</div>
                    <div class="detail-value">$<?= number_format((float)($payment['amount'] ?? 0), 2) ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value"><?= e(ucfirst(str_replace('_', ' ', $payment['method'] ?? 'N/A'))) ?></div>
                </div>
                <?php if ($pStatus === 'paid' && !empty($payment['receipt_number'])): ?>
                <div class="detail-item">
                    <div class="detail-label">Receipt</div>
                    <button class="btn-secondary" style="padding: 5px 10px; font-size: 14px;" onclick="downloadDocument('receipt')">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($pStatus !== 'paid'): ?>
            <form method="POST" action="/renter/payments" style="margin-top: 1rem;">
                <?= csrf_field() ?>
                <input type="hidden" name="payment_id" value="<?= e($payment['id'] ?? '') ?>">
                <input type="hidden" name="amount" value="<?= e($payment['amount'] ?? 0) ?>">
                <div class="form-group" style="margin-bottom: 0.5rem; max-width: 300px;">
                    <select name="method" required style="padding: 8px 12px;">
                        <option value="">Select payment method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="check">Check</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 14px;">
                    <i class="fas fa-credit-card"></i> Pay Now
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-file-invoice"></i>
        <p>No payment history</p>
        <small>Your payments will appear here</small>
    </div>
    <?php endif; ?>
</div>

<!-- ============ MAINTENANCE TAB ============ -->
<div class="tab-content" id="maintenanceTab">
    <div class="form-section">
        <h2 style="margin-bottom: 1.5rem; color: #2c5aa0;">New Maintenance Request</h2>

        <form method="POST" action="/renter/maintenance" id="maintenanceForm">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="issueType">Type of Issue *</label>
                <select id="issueType" name="category" required>
                    <option value="">Select issue type</option>
                    <option value="plumbing">Plumbing</option>
                    <option value="electrical">Electrical</option>
                    <option value="appliances">Appliance</option>
                    <option value="heating_cooling">Heating/Cooling</option>
                    <option value="flooring">Structural</option>
                    <option value="doors_windows">Doors/Windows</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="issueLocation">Location in Property *</label>
                <select id="issueLocation" name="location">
                    <option value="">Select location</option>
                    <option value="kitchen">Kitchen</option>
                    <option value="bathroom">Bathroom</option>
                    <option value="living_room">Living Room</option>
                    <option value="bedroom">Bedroom</option>
                    <option value="exterior">Exterior</option>
                    <option value="common_area">Common Area</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" placeholder="Brief description of the issue" required value="<?= e(old('title')) ?>">
            </div>

            <div class="form-group">
                <label for="issueDescription">Description *</label>
                <textarea id="issueDescription" name="description" rows="4" required placeholder="Please describe the issue in detail..."><?= e(old('description')) ?></textarea>
            </div>

            <div class="form-group">
                <label>Upload Photos (Optional)</label>
                <div class="file-upload" id="fileUploadArea">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Drag & drop photos or click to browse</p>
                    <small>Max file size: 5MB each</small>
                </div>
            </div>

            <div class="form-group">
                <label for="urgency">Urgency Level *</label>
                <select id="urgency" name="priority" required>
                    <option value="">Select urgency</option>
                    <option value="emergency">Emergency - Immediate attention required</option>
                    <option value="high">Urgent - Within 24 hours</option>
                    <option value="medium">Routine - Within 3-5 days</option>
                    <option value="low">Non-Urgent - When convenient</option>
                </select>
            </div>

            <div class="form-group">
                <label for="accessInstructions">Access Instructions</label>
                <textarea id="accessInstructions" name="access_instructions" rows="2" placeholder="Any special instructions for accessing the property..."><?= e(old('access_instructions')) ?></textarea>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-paper-plane"></i> Submit Request
            </button>
        </form>
    </div>

    <!-- Existing Requests -->
    <?php if (!empty($maintenanceRequests)): ?>
    <div class="section-header">
        <h2>Your Requests</h2>
    </div>
    <div class="requests-grid">
        <?php foreach ($maintenanceRequests as $request): ?>
        <div class="request-card">
            <div class="request-header">
                <div class="request-title"><?= e($request['title'] ?? 'Maintenance Request') ?></div>
                <div class="request-status status-<?= e(str_replace('_', '-', $request['status'] ?? 'open')) ?>"><?= e(ucfirst(str_replace('_', ' ', $request['status'] ?? 'open'))) ?></div>
            </div>
            <div class="request-date">Submitted: <?php
                if (!empty($request['created_at'])) {
                    echo e((new DateTime($request['created_at']))->format('M j, Y'));
                }
            ?></div>
            <p style="margin-top: 10px; color: #666; font-size: 14px;">
                <?= e($request['description'] ?? '') ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ============ DOCUMENTS TAB ============ -->
<div class="tab-content" id="documentsTab">
    <div class="section-header">
        <h2>Important Documents</h2>
        <button class="btn-secondary" onclick="document.getElementById('uploadDocModal').style.display='flex'">
            <i class="fas fa-upload"></i> Upload Document
        </button>
    </div>

    <!-- Upload Document Modal -->
    <div id="uploadDocModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:12px; padding:2rem; max-width:500px; width:90%; position:relative;">
            <button onclick="document.getElementById('uploadDocModal').style.display='none'" style="position:absolute; top:10px; right:15px; background:none; border:none; font-size:1.5rem; cursor:pointer; color:#999;">&times;</button>
            <h3 style="margin-bottom:1.5rem; color:#2c5aa0;">Upload Document</h3>
            <form method="POST" action="/renter/documents" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="docTitle">Document Title *</label>
                    <input type="text" id="docTitle" name="doc_title" placeholder="e.g. Insurance Certificate" required>
                </div>
                <div class="form-group">
                    <label for="docType">Document Type</label>
                    <select id="docType" name="doc_type">
                        <option value="other">Other</option>
                        <option value="lease">Lease Related</option>
                        <option value="insurance">Insurance</option>
                        <option value="identification">Identification</option>
                        <option value="receipt">Receipt</option>
                        <option value="inspection">Inspection</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="docFile">Select File * <small style="color:#999;">(PDF, JPG, PNG, DOC — Max 10MB)</small></label>
                    <input type="file" id="docFile" name="document" accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx" required style="padding:8px;">
                </div>
                <div style="display:flex; gap:10px; margin-top:1rem;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                    <button type="button" class="btn-secondary" onclick="document.getElementById('uploadDocModal').style.display='none'">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($documents)): ?>
    <div class="documents-grid">
        <?php
        // Icon mapping based on document type
        $docIcons = [
            'lease' => 'fa-file-contract',
            'rules' => 'fa-clipboard-list',
            'receipt' => 'fa-receipt',
            'inspection' => 'fa-clipboard-check',
            'insurance' => 'fa-shield-alt',
            'identification' => 'fa-id-card',
            'other' => 'fa-file-alt'
        ];
        ?>
        <?php foreach ($documents as $doc): ?>
        <?php
            $docIcon = $docIcons[$doc['type'] ?? 'other'] ?? 'fa-file-alt';
            $docDate = !empty($doc['created_at']) ? (new DateTime($doc['created_at']))->format('M j, Y') : 'N/A';
            $fileSize = '';
            if (!empty($doc['file_size']) && (int)$doc['file_size'] > 0) {
                $bytes = (int)$doc['file_size'];
                if ($bytes >= 1048576) {
                    $fileSize = number_format($bytes / 1048576, 1) . ' MB';
                } else {
                    $fileSize = number_format($bytes / 1024, 0) . ' KB';
                }
            }
        ?>
        <div class="document-card" onclick="window.location.href='/renter/documents/download?id=<?= (int)$doc['id'] ?>'">
            <div class="document-icon">
                <i class="fas <?= e($docIcon) ?>"></i>
            </div>
            <div class="document-name"><?= e($doc['title'] ?? $doc['file_name'] ?? 'Document') ?></div>
            <div class="document-date">
                <?= e($docDate) ?>
                <?php if ($fileSize): ?>
                    <span style="margin-left:8px; color:#999;"><?= e($fileSize) ?></span>
                <?php endif; ?>
            </div>
            <div style="margin-top:5px; font-size:12px; color:#999;">
                <?= e(ucfirst($doc['uploaded_by'] ?? 'admin')) ?> &middot; <?= e($doc['file_name'] ?? '') ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <p>No documents yet</p>
        <small>Upload documents or wait for management to share them with you</small>
    </div>
    <?php endif; ?>

    <!-- Latest Rent Receipt (auto-generated from payments) -->
    <?php
    $latestPaidPayment = null;
    foreach ($payments as $p) {
        if (($p['status'] ?? '') === 'paid') {
            $latestPaidPayment = $p;
            break;
        }
    }
    if ($latestPaidPayment): ?>
    <div class="section-header" style="margin-top: 2rem;">
        <h2>Payment Receipts</h2>
    </div>
    <div class="documents-grid">
        <?php foreach ($payments as $p): ?>
            <?php if (($p['status'] ?? '') === 'paid' && !empty($p['receipt_number'])): ?>
            <div class="document-card" onclick="downloadDocument('receipt', '<?= e($p['receipt_number'] ?? '') ?>')">
                <div class="document-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="document-name"><?php
                    $pDate = !empty($p['paid_date']) ? new DateTime($p['paid_date']) : null;
                    echo $pDate ? e($pDate->format('F Y')) . ' Receipt' : 'Payment Receipt';
                ?></div>
                <div class="document-date">Paid: <?php
                    echo $pDate ? e($pDate->format('M j, Y')) : 'N/A';
                ?> &middot; $<?= number_format((float)($p['amount'] ?? 0), 2) ?></div>
                <div style="margin-top:5px; font-size:12px; color:#999;">
                    Receipt #<?= e($p['receipt_number']) ?>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ============ MESSAGES TAB ============ -->
<div class="tab-content" id="messagesTab">
    <div class="section-header">
        <h2>Messages</h2>
        <button class="btn-primary" onclick="newMessage()">
            <i class="fas fa-plus"></i> New Message
        </button>
    </div>

    <div class="messages-list">
        <?php if (!empty($recentActivity)): ?>
            <?php foreach ($recentActivity as $index => $activity): ?>
                <?php
                $senderName = 'SOTELO Management';
                $senderInitials = 'SM';

                // If it's a maintenance request by renter, show renter initials
                if ($activity['type'] === 'maintenance') {
                    $fn = $user['first_name'] ?? '';
                    $ln = $user['last_name'] ?? '';
                    if ($fn && $ln) {
                        $senderName = e($fn . ' ' . $ln);
                        $senderInitials = strtoupper(substr($fn, 0, 1) . substr($ln, 0, 1));
                    }
                }

                // Format date
                $activityDate = !empty($activity['date']) ? new DateTime($activity['date']) : null;
                $formattedDate = '';
                if ($activityDate) {
                    $now = new DateTime();
                    $diff = $now->diff($activityDate);
                    if ($diff->days === 0) {
                        $formattedDate = 'Today, ' . $activityDate->format('g:i A');
                    } elseif ($diff->days === 1) {
                        $formattedDate = 'Yesterday';
                    } else {
                        $formattedDate = $activityDate->format('M j, Y');
                    }
                }
                ?>
                <div class="message-item<?= $index === 0 ? ' unread' : '' ?>">
                    <div class="message-avatar"><?= e($senderInitials) ?></div>
                    <div class="message-content">
                        <div class="message-header">
                            <div class="message-sender"><?= e($senderName) ?></div>
                            <div class="message-time"><?= e($formattedDate) ?></div>
                        </div>
                        <div class="message-preview">
                            <?= e($activity['description']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-envelope-open"></i>
                <p>No messages yet</p>
                <small>Notifications about payments and maintenance will appear here</small>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Tab switching
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active from all tabs
            tabs.forEach(function(t) { t.classList.remove('active'); });
            tabContents.forEach(function(tc) { tc.classList.remove('active'); });

            // Activate clicked tab
            this.classList.add('active');
            var targetEl = document.getElementById(targetTab + 'Tab');
            if (targetEl) {
                targetEl.classList.add('active');
            }
        });
    });

    // Auto-switch tab from URL ?tab= parameter
    var urlParams = new URLSearchParams(window.location.search);
    var tabParam = urlParams.get('tab');
    if (tabParam) {
        var tabToActivate = document.querySelector('.tab[data-tab="' + tabParam + '"]');
        if (tabToActivate) {
            tabToActivate.click();
        }
    }

    // File upload area
    var fileUpload = document.getElementById('fileUploadArea');
    if (fileUpload) {
        fileUpload.addEventListener('click', function() {
            var input = document.createElement('input');
            input.type = 'file';
            input.multiple = true;
            input.accept = 'image/*,.pdf';
            input.onchange = function() {
                Swal.fire({icon:'success', title:'Files Selected', text: input.files.length + ' file(s) selected for upload', confirmButtonColor:'#2c5aa0'});
            };
            input.click();
        });
    }
});

function switchToTab(tabName) {
    var tabBtn = document.querySelector('.tab[data-tab="' + tabName + '"]');
    if (tabBtn) {
        tabBtn.click();
    }
}

function downloadDocument(docType, receiptNumber) {
    if (docType === 'receipt' && receiptNumber) {
        Swal.fire({
            icon: 'success',
            title: 'Receipt #' + receiptNumber,
            html: '<p>Your payment receipt is confirmed.</p><p style="color:#666;font-size:13px;margin-top:8px;">Receipt number: <strong>' + receiptNumber + '</strong></p>',
            confirmButtonColor: '#2c5aa0',
            confirmButtonText: 'OK'
        });
    } else {
        var docNames = {
            'lease': 'Lease Agreement',
            'rules': 'Property Rules',
            'receipt': 'Rent Receipt',
            'inspection': 'Move-in Inspection Report'
        };
        var docName = docNames[docType] || docType;

        Swal.fire({
            icon: 'success',
            title: 'Document Ready',
            html: '<p>Your <strong>' + docName + '</strong> document is being prepared for download.</p><p style="color:#666;font-size:13px;margin-top:8px;">Contact management if you need an updated copy.</p>',
            confirmButtonColor: '#2c5aa0',
            confirmButtonText: 'OK'
        });
    }
}

function setupAutoPay() {
    Swal.fire({
        title: 'Setup Auto-Pay',
        html:
            '<div style="text-align:left;margin-bottom:10px;">' +
            '<label style="display:block;margin-bottom:5px;font-weight:600;color:#333;">Payment Method</label>' +
            '<select id="swal-autopay-method" class="swal2-select" style="width:100%;padding:8px 12px;border:1px solid #ddd;border-radius:6px;">' +
            '<option value="credit_card">Credit Card</option>' +
            '<option value="debit_card">Debit Card</option>' +
            '<option value="bank_transfer">Bank Transfer</option>' +
            '</select>' +
            '</div>' +
            '<div style="text-align:left;">' +
            '<label style="display:block;margin-bottom:5px;font-weight:600;color:#333;">Pay On</label>' +
            '<select id="swal-autopay-day" class="swal2-select" style="width:100%;padding:8px 12px;border:1px solid #ddd;border-radius:6px;">' +
            '<option value="1">1st of each month</option>' +
            '<option value="5">5th of each month</option>' +
            '<option value="15">15th of each month</option>' +
            '</select>' +
            '</div>',
        confirmButtonText: 'Enable Auto-Pay',
        confirmButtonColor: '#2c5aa0',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        preConfirm: function() {
            return {
                method: document.getElementById('swal-autopay-method').value,
                day: document.getElementById('swal-autopay-day').value
            };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Auto-Pay Enabled',
                text: 'Your rent will be automatically paid on the ' + result.value.day + getSuffix(result.value.day) + ' of each month via ' + result.value.method.replace('_', ' ') + '.',
                confirmButtonColor: '#2c5aa0'
            });
        }
    });
}

function getSuffix(day) {
    if (day == '1') return 'st';
    if (day == '5') return 'th';
    if (day == '15') return 'th';
    return 'th';
}

function newMessage() {
    Swal.fire({
        title: 'New Message',
        html:
            '<div style="text-align:left;margin-bottom:10px;">' +
            '<label style="display:block;margin-bottom:5px;font-weight:600;color:#333;">To</label>' +
            '<input id="swal-msg-to" class="swal2-input" value="SOTELO Management" readonly style="width:100%;margin:0;background:#f5f5f5;">' +
            '</div>' +
            '<div style="text-align:left;margin-bottom:10px;">' +
            '<label style="display:block;margin-bottom:5px;font-weight:600;color:#333;">Subject</label>' +
            '<input id="swal-msg-subject" class="swal2-input" placeholder="Enter subject..." style="width:100%;margin:0;">' +
            '</div>' +
            '<div style="text-align:left;">' +
            '<label style="display:block;margin-bottom:5px;font-weight:600;color:#333;">Message</label>' +
            '<textarea id="swal-msg-body" class="swal2-textarea" placeholder="Type your message..." style="width:100%;margin:0;min-height:100px;"></textarea>' +
            '</div>',
        confirmButtonText: '<i class="fas fa-paper-plane"></i> Send Message',
        confirmButtonColor: '#2c5aa0',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        preConfirm: function() {
            var subject = document.getElementById('swal-msg-subject').value.trim();
            var body = document.getElementById('swal-msg-body').value.trim();
            if (!subject || !body) {
                Swal.showValidationMessage('Please fill in both subject and message');
                return false;
            }
            return { subject: subject, body: body };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Message Sent',
                text: 'Your message has been sent to SOTELO Management. You will receive a response within 24-48 hours.',
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
