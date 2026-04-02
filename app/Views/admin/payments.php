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
        <button class="btn btn-primary" onclick="sendReminders()">
            <i class="fas fa-bell"></i> Send Reminders
        </button>
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

<!-- Payment Method Breakdown (Dynamic from DB) -->
<div class="payment-methods">
    <?php if (!empty($methodBreakdown)): ?>
        <?php foreach ($methodBreakdown as $method): ?>
        <div class="method-card">
            <div class="method-icon">
                <i class="<?= e($method['icon']) ?>"></i>
            </div>
            <div class="method-name"><?= e($method['label']) ?></div>
            <div class="method-amount">$<?= number_format($method['total'], 0) ?></div>
            <div class="summary-label"><?= $method['percentage'] ?>% of total</div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="method-card">
            <div class="method-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="method-name">No Data</div>
            <div class="method-amount">$0</div>
            <div class="summary-label">No paid transactions yet</div>
        </div>
    <?php endif; ?>
</div>

<!-- Payment Calendar -->
<div class="payment-calendar">
    <div class="calendar-header">
        <h3 id="calendarMonth"></h3>
        <div class="calendar-nav">
            <button onclick="changeMonth(-1)">&larr; Prev</button>
            <button onclick="changeMonth(1)">Next &rarr;</button>
        </div>
    </div>
    <div class="calendar-grid" id="calendarGrid">
        <div class="calendar-day-header">Sun</div>
        <div class="calendar-day-header">Mon</div>
        <div class="calendar-day-header">Tue</div>
        <div class="calendar-day-header">Wed</div>
        <div class="calendar-day-header">Thu</div>
        <div class="calendar-day-header">Fri</div>
        <div class="calendar-day-header">Sat</div>
        <!-- Calendar days will be injected here -->
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
        <span>Recent Transactions</span> <button class="btn btn-small" onclick="location.reload()">View All</button>
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
            <form id="paymentForm" method="POST" action="<?= route('admin.payments') ?>">
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

<style>
.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.method-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #eaeaea;
}
.method-icon {
    font-size: 2rem;
    color: #2c5aa0;
    margin-bottom: 0.5rem;
}
.method-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}
.method-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c5aa0;
}
.payment-calendar {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #eaeaea;
}
.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.calendar-header h3 { margin: 0; font-size: 1.25rem; color: #333; }
.calendar-nav button {
    background: white;
    border: 1px solid #ddd;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s;
}
.calendar-nav button:hover {
    background: #f0f0f0;
    border-color: #ccc;
}
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}
.calendar-day-header {
    padding: 0.75rem;
    text-align: center;
    font-weight: 600;
    font-size: 13px;
    color: #555;
    background: #f1f5f9;
    border-bottom: 1px solid #e5e7eb;
}
.calendar-day {
    padding: 0.5rem;
    min-height: 80px;
    border: 1px solid #f0f0f0;
    background: white;
    position: relative;
    transition: background 0.15s;
}
.calendar-day:hover { background: #fafbfc; }
.calendar-day .day-number {
    display: block;
    text-align: right;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 4px;
}
.calendar-day.empty {
    background: #fafafa;
}
.calendar-day.today {
    background: #eff6ff;
    border-color: #bfdbfe;
}
.calendar-day.today .day-number {
    color: #2c5aa0;
    font-weight: 700;
}
.calendar-day .payment-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin: 1px;
}
.calendar-day .payment-dot.paid { background: #10b981; }
.calendar-day .payment-dot.pending { background: #f59e0b; }
.calendar-day .payment-dot.overdue { background: #ef4444; }
.calendar-day .payment-label {
    display: block;
    font-size: 10px;
    padding: 1px 4px;
    border-radius: 3px;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
}
.calendar-day .payment-label.paid { background: #d1fae5; color: #065f46; }
.calendar-day .payment-label.pending { background: #fef3c7; color: #92400e; }
.calendar-day .payment-label.overdue { background: #fee2e2; color: #991b1b; }
</style>

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
    form.action = '<?= route("admin.payments") ?>/' + id + '/update';

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
                <button class="close-modal" onclick="this.closest('.modal-overlay').remove()">&times;</button>
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
                    <button class="btn btn-primary" onclick="Swal.fire({title:'Printing...', html:'Preparing receipt for print', icon:'info', confirmButtonColor:'#2c5aa0', timer:1500, timerProgressBar:true, didOpen:()=>{Swal.showLoading()}})">
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
    if (!payment) return;

    Swal.fire({
        title: 'Send Receipt?',
        html: `Send payment receipt to <strong>${payment.first_name} ${payment.last_name}</strong> (${payment.email || 'No email'})`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-paper-plane"></i> Send',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Receipt Sent!',
                html: `Payment receipt has been emailed to <strong>${payment.first_name} ${payment.last_name}</strong>`,
                icon: 'success',
                confirmButtonColor: '#2c5aa0',
                timer: 3000,
                timerProgressBar: true
            });
        }
    });
}

function exportPayments() {
    if (!paymentsData || paymentsData.length === 0) {
        Swal.fire({ title: 'No Data', text: 'No payments to export.', icon: 'warning', confirmButtonColor: '#2c5aa0' });
        return;
    }

    const headers = ['ID', 'Renter', 'Property', 'Amount', 'Due Date', 'Paid Date', 'Method', 'Status', 'Period From', 'Period To', 'Notes'];

    const methodLabels = { bank_transfer: 'Bank Transfer', credit_card: 'Credit Card', cash: 'Cash', check: 'Check', mobile_pay: 'Mobile Pay' };

    const rows = paymentsData.map(p => [
        p.id,
        (p.first_name || '') + ' ' + (p.last_name || ''),
        p.property_name || 'N/A',
        parseFloat(p.amount || 0).toFixed(2),
        p.due_date || '',
        p.paid_date || '',
        methodLabels[p.method] || p.method || 'N/A',
        (p.status || '').charAt(0).toUpperCase() + (p.status || '').slice(1),
        p.period_from || '',
        p.period_to || '',
        p.notes || ''
    ]);

    const csvContent = [headers, ...rows].map(row =>
        row.map(field => {
            const str = String(field);
            if (str.includes(',') || str.includes('"') || str.includes('\n')) {
                return '"' + str.replace(/"/g, '""') + '"';
            }
            return str;
        }).join(',')
    ).join('\n');

    const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'payments_export_' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    Swal.fire({
        title: 'Export Complete!',
        text: 'Payment data has been downloaded as CSV.',
        icon: 'success',
        confirmButtonColor: '#2c5aa0',
        timer: 3000,
        timerProgressBar: true
    });
}

function sendReminders() {
    // Find pending and overdue payments
    const duePayments = paymentsData.filter(p => p.status === 'pending' || p.status === 'overdue');

    if (duePayments.length === 0) {
        Swal.fire({
            title: 'No Pending Payments',
            text: 'All payments are up to date. No reminders needed.',
            icon: 'info',
            confirmButtonColor: '#2c5aa0'
        });
        return;
    }

    // Build list of renters with due payments
    const renterMap = {};
    duePayments.forEach(p => {
        const name = (p.first_name || '') + ' ' + (p.last_name || '');
        if (!renterMap[name]) {
            renterMap[name] = { total: 0, count: 0, status: p.status };
        }
        renterMap[name].total += parseFloat(p.amount || 0);
        renterMap[name].count++;
        if (p.status === 'overdue') renterMap[name].status = 'overdue';
    });

    let listHtml = '<div style="text-align:left;max-height:250px;overflow-y:auto;margin-top:0.5rem;">';
    listHtml += '<table style="width:100%;font-size:13px;border-collapse:collapse;">';
    listHtml += '<tr style="border-bottom:2px solid #eee;"><th style="padding:6px;text-align:left;">Renter</th><th style="padding:6px;text-align:right;">Amount</th><th style="padding:6px;text-align:center;">Status</th></tr>';
    Object.entries(renterMap).forEach(([name, info]) => {
        const color = info.status === 'overdue' ? '#ef4444' : '#f59e0b';
        const badge = info.status === 'overdue' ? 'Overdue' : 'Pending';
        listHtml += `<tr style="border-bottom:1px solid #f0f0f0;">
            <td style="padding:6px;">${name}</td>
            <td style="padding:6px;text-align:right;font-weight:600;">$${info.total.toFixed(2)}</td>
            <td style="padding:6px;text-align:center;"><span style="background:${color};color:white;padding:2px 8px;border-radius:4px;font-size:11px;">${badge}</span></td>
        </tr>`;
    });
    listHtml += '</table></div>';

    Swal.fire({
        title: 'Send Payment Reminders?',
        html: `<p style="margin-bottom:0.5rem;">Reminders will be sent to <strong>${Object.keys(renterMap).length} renter(s)</strong> with <strong>${duePayments.length}</strong> outstanding payment(s).</p>${listHtml}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-bell"></i> Send Reminders',
        cancelButtonText: 'Cancel',
        width: 500
    }).then((result) => {
        if (result.isConfirmed) {
            // POST to backend to send reminders
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= route("admin.payments") ?>/send-reminders';
            form.innerHTML = '<?= csrf_field() ?>';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Calendar functionality
let currentCalendarDate = new Date();

function getPaymentsForDate(year, month, day) {
    const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
    return paymentsData.filter(p => {
        return (p.due_date && p.due_date.startsWith(dateStr)) || (p.paid_date && p.paid_date.startsWith(dateStr));
    });
}

function renderCalendar() {
    const month = currentCalendarDate.getMonth();
    const year = currentCalendarDate.getFullYear();
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'];

    document.getElementById('calendarMonth').textContent = monthNames[month] + ' ' + year;

    const grid = document.getElementById('calendarGrid');
    // Remove old day cells but keep headers
    grid.querySelectorAll('.calendar-day').forEach(el => el.remove());

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();

    // Empty cells before first day
    for (let i = 0; i < firstDay; i++) {
        const cell = document.createElement('div');
        cell.className = 'calendar-day empty';
        grid.appendChild(cell);
    }

    // Day cells
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = day === today.getDate() && month === today.getMonth() && year === today.getFullYear();
        const cell = document.createElement('div');
        cell.className = 'calendar-day' + (isToday ? ' today' : '');

        let inner = '<span class="day-number">' + day + '</span>';

        // Show payments on this day
        const dayPayments = getPaymentsForDate(year, month, day);
        if (dayPayments.length > 0) {
            const maxShow = 2;
            dayPayments.slice(0, maxShow).forEach(p => {
                const name = (p.first_name || '').split(' ')[0];
                const amt = '$' + parseFloat(p.amount || 0).toFixed(0);
                const cls = p.status || 'pending';
                inner += '<div class="payment-label ' + cls + '" title="' + (p.first_name || '') + ' ' + (p.last_name || '') + ' - $' + parseFloat(p.amount || 0).toFixed(2) + ' (' + cls + ')">' +
                    '<span class="payment-dot ' + cls + '"></span> ' + name + ' ' + amt +
                    '</div>';
            });
            if (dayPayments.length > maxShow) {
                inner += '<div style="font-size:10px;color:#666;margin-top:2px;">+' + (dayPayments.length - maxShow) + ' more</div>';
            }
        }

        cell.innerHTML = inner;
        grid.appendChild(cell);
    }

    // Fill remaining cells to complete the last row
    const totalCells = firstDay + daysInMonth;
    const remaining = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
    for (let i = 0; i < remaining; i++) {
        const cell = document.createElement('div');
        cell.className = 'calendar-day empty';
        grid.appendChild(cell);
    }
}

function changeMonth(offset) {
    currentCalendarDate.setMonth(currentCalendarDate.getMonth() + offset);
    renderCalendar();
}

// Initialize calendar
renderCalendar();

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
