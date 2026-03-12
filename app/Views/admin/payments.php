<?php
$title = 'Payments';
$active = 'payments';
ob_start();

// Ensure all variables are available
$payments = $payments ?? [];
$summary = $summary ?? ['total_collected' => 0, 'pending' => 0, 'overdue' => 0];
$renters = $renters ?? [];

// Calculate collection rate
$totalExpected = 0;
foreach ($payments as $payment) {
    $totalExpected += $payment['amount'];
}
$collectionRate = $totalExpected > 0 ? (int) (($summary['total_collected'] / $totalExpected) * 100) : 0;
?>

<div class="content-header">
    <h1>Payment Management</h1>
    <div class="content-actions">
        <button class="btn btn-secondary" onclick="exportPayments()">
            <i class="fas fa-download"></i> Export
        </button>
        <button class="btn btn-success" onclick="recordPayment()">
            <i class="fas fa-plus"></i> Record Payment
        </button>
    </div>
</div>

<!-- Payment Summary Cards -->
<div class="payment-summary">
    <div class="summary-item" style="border-left: 4px solid #10b981;">
        <div class="summary-value" id="totalCollected"><?= '$' . number_format($summary['total_collected'], 2) ?></div>
        <div class="summary-label">Total Collected</div>
    </div>
    <div class="summary-item" style="border-left: 4px solid #f59e0b;">
        <div class="summary-value" id="pendingPayments"><?= '$' . number_format($summary['pending'], 2) ?></div>
        <div class="summary-label">Pending Payments</div>
    </div>
    <div class="summary-item" style="border-left: 4px solid #ef4444;">
        <div class="summary-value" id="overduePayments"><?= '$' . number_format($summary['overdue'], 2) ?></div>
        <div class="summary-label">Overdue Payments</div>
    </div>
    <div class="summary-item" style="border-left: 4px solid #3b82f6;">
        <div class="summary-value" id="collectionRate"><?= $collectionRate ?>%</div>
        <div class="summary-label">Collection Rate</div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" id="filterForm">
        <div class="filter-row">
            <div class="filter-group">
                <label>Search Payments</label>
                <input type="text" name="search" placeholder="Search by renter or property..." value="<?= e($_GET['search'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="paid" <?= ($_GET['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="overdue" <?= ($_GET['status'] ?? '') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                    <option value="failed" <?= ($_GET['status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Payment Method</label>
                <select name="method">
                    <option value="">All Methods</option>
                    <option value="bank_transfer" <?= ($_GET['method'] ?? '') === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                    <option value="credit_card" <?= ($_GET['method'] ?? '') === 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                    <option value="cash" <?= ($_GET['method'] ?? '') === 'cash' ? 'selected' : '' ?>>Cash</option>
                    <option value="check" <?= ($_GET['method'] ?? '') === 'check' ? 'selected' : '' ?>>Check</option>
                    <option value="mobile_pay" <?= ($_GET['method'] ?? '') === 'mobile_pay' ? 'selected' : '' ?>>Mobile Pay</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Date Range</label>
                <input type="date" name="date_from" value="<?= e($_GET['date_from'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>To</label>
                <input type="date" name="date_to" value="<?= e($_GET['date_to'] ?? '') ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="<?= route('admin.payments') ?>" class="btn" style="margin-left: 1rem;">
            Reset
        </a>
    </form>
</div>

<!-- Payments Table -->
<div class="table-container">
    <div class="table-title">
        <span>Payments (<?= count($payments) ?>)</span>
    </div>
    <?php if (empty($payments)): ?>
        <div style="padding: 2rem; text-align: center; color: #999;">
            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p>No payments found.</p>
        </div>
    <?php else: ?>
        <div class="transaction-row header">
            <div>Renter / Property</div>
            <div>Amount</div>
            <div>Due Date</div>
            <div>Status</div>
            <div>Method</div>
            <div>Actions</div>
        </div>
        <?php foreach ($payments as $payment): ?>
            <?php
            $statusClass = $payment['status'] === 'paid' ? 'status-paid' :
                          ($payment['status'] === 'pending' ? 'status-pending' :
                          ($payment['status'] === 'overdue' ? 'status-overdue' : 'status-failed'));
            $statusText = ucfirst($payment['status']);

            $methodText = match($payment['method'] ?? '') {
                'bank_transfer' => 'Bank Transfer',
                'credit_card' => 'Credit Card',
                'cash' => 'Cash',
                'check' => 'Check',
                'mobile_pay' => 'Mobile Pay',
                default => 'N/A'
            };

            $renterName = $payment['first_name'] . ' ' . $payment['last_name'];
            $propertyDisplay = $payment['property_name'] ?? 'N/A';
            ?>
            <div class="transaction-row">
                <div>
                    <div style="font-weight: 600;"><?= e($renterName) ?></div>
                    <div style="font-size: 12px; color: #666;"><?= e($propertyDisplay) ?></div>
                </div>
                <div>
                    <strong style="color: #10b981;">$<?= number_format($payment['amount'], 2) ?></strong><br>
                    <small style="color: #666;"><?= e($methodText) ?></small>
                </div>
                <div><?= date('M d, Y', strtotime($payment['due_date'])) ?></div>
                <div>
                    <span class="payment-status <?= $statusClass ?>"><?= $statusText ?></span><br>
                    <small style="color: #666;">
                        <?php if ($payment['paid_date']): ?>
                            Paid: <?= date('M d, Y', strtotime($payment['paid_date'])) ?>
                        <?php else: ?>
                            Not paid yet
                        <?php endif; ?>
                    </small>
                </div>
                <div><?= e($methodText) ?></div>
                <div>
                    <div class="action-buttons">
                        <button class="btn-small btn-icon" onclick="viewReceipt(<?= (int) $payment['id'] ?>)" title="View Receipt">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-small btn-icon" onclick="editPayment(<?= (int) $payment['id'] ?>)" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-small btn-icon" onclick="sendReceipt(<?= (int) $payment['id'] ?>)" title="Send Receipt">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Record Payment Modal -->
<div class="modal-overlay" id="recordPaymentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Record New Payment</h2>
            <button class="close-modal" onclick="closeModal('recordPaymentModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="paymentForm" method="POST" action="<?= route('admin.payments') ?>/store">
                <?= csrf_field() ?>

                <div class="form-section">
                    <h4>Payment Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="renter_id">Renter *</label>
                            <select id="renter_id" name="renter_id" required>
                                <option value="">Select Renter</option>
                                <?php foreach ($renters as $renter): ?>
                                    <option value="<?= (int) $renter['id'] ?>">
                                        <?= e($renter['first_name'] . ' ' . $renter['last_name']) ?> - <?= e($renter['property_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount *</label>
                            <input type="number" id="amount" name="amount" required min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="due_date">Due Date *</label>
                            <input type="date" id="due_date" name="due_date" required>
                        </div>
                        <div class="form-group">
                            <label for="paid_date">Paid Date</label>
                            <input type="date" id="paid_date" name="paid_date">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="method">Payment Method *</label>
                            <select id="method" name="method" required>
                                <option value="">Select Method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="mobile_pay">Mobile Payment</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Payment Period</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="period_from">Period From</label>
                            <input type="date" id="period_from" name="period_from">
                        </div>
                        <div class="form-group">
                            <label for="period_to">Period To</label>
                            <input type="date" id="period_to" name="period_to">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="2" placeholder="Additional notes for this payment"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Record Payment
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal-overlay" id="editPaymentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Payment</h2>
            <button class="close-modal" onclick="closeModal('editPaymentModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editPaymentForm" method="POST" action="">
                <?= csrf_field() ?>

                <div class="form-section">
                    <h4>Payment Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_amount">Amount *</label>
                            <input type="number" id="edit_amount" name="amount" required min="0" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="edit_due_date">Due Date *</label>
                            <input type="date" id="edit_due_date" name="due_date" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_paid_date">Paid Date</label>
                            <input type="date" id="edit_paid_date" name="paid_date">
                        </div>
                        <div class="form-group">
                            <label for="edit_method">Payment Method *</label>
                            <select id="edit_method" name="method" required>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="mobile_pay">Mobile Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_status">Status</label>
                            <select id="edit_status" name="status">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Payment Period</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_period_from">Period From</label>
                            <input type="date" id="edit_period_from" name="period_from">
                        </div>
                        <div class="form-group">
                            <label for="edit_period_to">Period To</label>
                            <input type="date" id="edit_period_to" name="period_to">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_notes">Notes</label>
                        <textarea id="edit_notes" name="notes" rows="2"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Update Payment
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Pass payments data to JavaScript
const paymentsData = <?= json_encode(array_map(function($p) {
    return [
        'id' => $p['id'],
        'renter_id' => $p['renter_id'],
        'first_name' => $p['first_name'] ?? '',
        'last_name' => $p['last_name'] ?? '',
        'property_name' => $p['property_name'] ?? '',
        'amount' => $p['amount'],
        'due_date' => $p['due_date'],
        'paid_date' => $p['paid_date'],
        'method' => $p['method'],
        'status' => $p['status'],
        'period_from' => $p['period_from'],
        'period_to' => $p['period_to'],
        'notes' => $p['notes'] ?? ''
    ];
}, $payments)) ?>;

let currentPaymentId = null;

function recordPayment() {
    document.getElementById('recordPaymentModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function editPayment(id) {
    currentPaymentId = id;
    const payment = paymentsData.find(p => p.id === id);

    if (!payment) return;

    document.getElementById('edit_amount').value = payment.amount;
    document.getElementById('edit_due_date').value = payment.due_date;
    document.getElementById('edit_paid_date').value = payment.paid_date || '';
    document.getElementById('edit_method').value = payment.method || '';
    document.getElementById('edit_status').value = payment.status;
    document.getElementById('edit_period_from').value = payment.period_from || '';
    document.getElementById('edit_period_to').value = payment.period_to || '';
    document.getElementById('edit_notes').value = payment.notes || '';

    const form = document.getElementById('editPaymentForm');
    form.action = '<?= route("admin.payments") ?>/' + id;

    document.getElementById('editPaymentModal').classList.add('active');
}

function viewReceipt(id) {
    const payment = paymentsData.find(p => p.id === id);
    if (!payment) return;

    const modal = document.createElement('div');
    modal.className = 'modal-overlay active';
    modal.style.display = 'flex';
    modal.innerHTML = `
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Payment Receipt #${payment.id}</h2>
                <button class="close-modal" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="details-grid">
                    <div class="detail-item">
                        <label>Renter</label>
                        <span>${payment.first_name} ${payment.last_name}</span>
                    </div>
                    <div class="detail-item">
                        <label>Property</label>
                        <span>${payment.property_name}</span>
                    </div>
                    <div class="detail-item">
                        <label>Amount</label>
                        <span>$${parseFloat(payment.amount).toFixed(2)}</span>
                    </div>
                    <div class="detail-item">
                        <label>Due Date</label>
                        <span>${new Date(payment.due_date).toLocaleDateString()}</span>
                    </div>
                    <div class="detail-item">
                        <label>Paid Date</label>
                        <span>${payment.paid_date ? new Date(payment.paid_date).toLocaleDateString() : 'Not paid'}</span>
                    </div>
                    <div class="detail-item">
                        <label>Status</label>
                        <span style="text-transform: capitalize;">${payment.status}</span>
                    </div>
                    <div class="detail-item">
                        <label>Method</label>
                        <span>${payment.method ? payment.method.replace(/_/g, ' ') : 'N/A'}</span>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 2rem;">
                    <button class="btn btn-primary" onclick="alert('Printing...')">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.remove();
        }
    });
}

function sendReceipt(id) {
    const payment = paymentsData.find(p => p.id === id);
    if (payment) {
        alert(`Receipt email sent to ${payment.first_name} ${payment.last_name}`);
    }
}

function exportPayments() {
    alert('Exporting payments data...');
}

// Close modal when clicking outside
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
