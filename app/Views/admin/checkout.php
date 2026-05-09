<?php
$title = 'Payment Checkout';
$active = 'payments';
ob_start();

$renter = $renter ?? [];
$pendingPayments = $pendingPayments ?? [];
$paidPayments = $paidPayments ?? [];

$renterName = trim(($renter['first_name'] ?? '') . ' ' . ($renter['last_name'] ?? ''));
$renterId = (int)($renter['id'] ?? 0);
$propertyName = $renter['property_name'] ?? 'N/A';
$propertyAddress = trim(($renter['address'] ?? '') . ', ' . ($renter['city'] ?? '') . ' ' . ($renter['state'] ?? '') . ' ' . ($renter['zip'] ?? ''));
$monthlyRent = (float)($renter['monthly_rent'] ?? 0);
$initials = strtoupper(substr($renter['first_name'] ?? 'R', 0, 1) . substr($renter['last_name'] ?? '', 0, 1));
?>

<style>
.checkout-container {
    max-width: 960px;
    margin: 0 auto;
}
.checkout-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}
.checkout-header .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #2c5aa0;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    padding: 8px 14px;
    border-radius: 8px;
    transition: background 0.2s;
}
.checkout-header .back-btn:hover {
    background: #eef2ff;
}
.checkout-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    margin-bottom: 2rem;
    padding: 1rem 0;
}
.step {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #9ca3af;
    font-weight: 500;
}
.step.active {
    color: #2c5aa0;
}
.step.completed {
    color: #10b981;
}
.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    background: #e5e7eb;
    color: #6b7280;
}
.step.active .step-number {
    background: #2c5aa0;
    color: #fff;
}
.step.completed .step-number {
    background: #10b981;
    color: #fff;
}
.step-connector {
    width: 60px;
    height: 2px;
    background: #e5e7eb;
    margin: 0 12px;
}
.step-connector.active {
    background: #2c5aa0;
}
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.5rem;
    align-items: start;
}
.checkout-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    padding: 1.5rem;
}
.checkout-card h2 {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.checkout-card h2 i {
    color: #2c5aa0;
}
.renter-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}
.renter-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #2c5aa0;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
}
.renter-details h3 {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}
.renter-details p {
    font-size: 13px;
    color: #6b7280;
    margin: 2px 0 0;
}
.checkout-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.checkout-form .form-group {
    margin-bottom: 1rem;
}
.checkout-form .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}
.checkout-form .form-group input,
.checkout-form .form-group select,
.checkout-form .form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    color: #1f2937;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}
.checkout-form .form-group input:focus,
.checkout-form .form-group select:focus,
.checkout-form .form-group textarea:focus {
    outline: none;
    border-color: #2c5aa0;
    box-shadow: 0 0 0 3px rgba(44,90,160,0.1);
}
.checkout-form .form-group textarea {
    min-height: 70px;
    resize: vertical;
}
.method-options {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
}
.method-option {
    position: relative;
}
.method-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.method-option label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 14px 10px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
}
.method-option label i {
    font-size: 22px;
    color: #6b7280;
}
.method-option label span {
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
}
.method-option input[type="radio"]:checked + label {
    border-color: #2c5aa0;
    background: #eef2ff;
}
.method-option input[type="radio"]:checked + label i,
.method-option input[type="radio"]:checked + label span {
    color: #2c5aa0;
}
/* Order Summary Sidebar */
.order-summary {
    position: sticky;
    top: 1rem;
}
.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    font-size: 14px;
    color: #4b5563;
}
.summary-line.total {
    border-top: 2px solid #e5e7eb;
    margin-top: 8px;
    padding-top: 14px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}
.pending-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.pending-badge.overdue { background: #fee2e2; color: #991b1b; }
.pending-badge.due { background: #fef3c7; color: #92400e; }
.pending-badge.pending { background: #e0e7ff; color: #3730a3; }
.pending-list {
    margin-bottom: 1rem;
}
.pending-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
}
.pending-item:last-child { border-bottom: none; }
.pending-item .p-month { font-weight: 500; color: #1f2937; }
.pending-item .p-amount { font-weight: 600; color: #1f2937; }
.pending-item .p-due { font-size: 12px; color: #6b7280; }
.btn-checkout {
    display: block;
    width: 100%;
    padding: 14px;
    background: #10b981;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 1rem;
}
.btn-checkout:hover {
    background: #059669;
}
.btn-checkout:disabled {
    background: #9ca3af;
    cursor: not-allowed;
}
.btn-checkout i {
    margin-right: 6px;
}
.secure-note {
    text-align: center;
    font-size: 12px;
    color: #9ca3af;
    margin-top: 12px;
}
.secure-note i {
    margin-right: 4px;
}
.payment-history-mini {
    margin-top: 1rem;
}
.payment-history-mini h3 {
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 8px;
}
.history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 13px;
    border-bottom: 1px solid #f3f4f6;
}
.history-item:last-child { border-bottom: none; }
.history-item .paid-badge {
    background: #d1fae5;
    color: #065f46;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
    .method-options {
        grid-template-columns: 1fr 1fr;
    }
    .checkout-form .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="checkout-container">
    <!-- Header -->
    <div class="checkout-header">
        <a href="<?= route('admin.renters') ?>" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Renters
        </a>
    </div>

    <!-- Progress Steps -->
    <div class="checkout-steps">
        <div class="step active" id="step1">
            <div class="step-number">1</div>
            <span>Payment Details</span>
        </div>
        <div class="step-connector" id="conn1"></div>
        <div class="step" id="step2">
            <div class="step-number">2</div>
            <span>Review</span>
        </div>
        <div class="step-connector" id="conn2"></div>
        <div class="step" id="step3">
            <div class="step-number">3</div>
            <span>Confirmation</span>
        </div>
    </div>

    <!-- Step 1: Payment Details -->
    <div id="checkoutStep1">
        <div class="checkout-grid">
            <!-- Left: Form -->
            <div class="checkout-card">
                <h2><i class="fas fa-file-invoice-dollar"></i> Payment Details</h2>

                <!-- Renter Info -->
                <div class="renter-info">
                    <div class="renter-avatar"><?= e($initials) ?></div>
                    <div class="renter-details">
                        <h3><?= e($renterName) ?></h3>
                        <p><i class="fas fa-home"></i> <?= e($propertyName) ?></p>
                        <p><i class="fas fa-map-marker-alt"></i> <?= e($propertyAddress) ?></p>
                    </div>
                </div>

                <form id="checkoutForm" class="checkout-form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="renter_id" value="<?= $renterId ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-dollar-sign"></i> Amount</label>
                            <input type="number" name="amount" id="payAmount" value="<?= number_format($monthlyRent, 2, '.', '') ?>" min="0.01" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar"></i> Due Date</label>
                            <input type="date" name="due_date" id="payDueDate" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Payment Method</label>
                        <div class="method-options">
                            <div class="method-option">
                                <input type="radio" name="method" id="method_credit" value="credit_card" checked>
                                <label for="method_credit">
                                    <i class="fab fa-cc-visa"></i>
                                    <span>Credit Card</span>
                                </label>
                            </div>
                            <div class="method-option">
                                <input type="radio" name="method" id="method_debit" value="debit_card">
                                <label for="method_debit">
                                    <i class="fab fa-cc-mastercard"></i>
                                    <span>Debit Card</span>
                                </label>
                            </div>
                            <div class="method-option">
                                <input type="radio" name="method" id="method_bank" value="bank_transfer">
                                <label for="method_bank">
                                    <i class="fas fa-university"></i>
                                    <span>Bank Transfer</span>
                                </label>
                            </div>
                            <div class="method-option">
                                <input type="radio" name="method" id="method_check" value="check">
                                <label for="method_check">
                                    <i class="fas fa-money-check"></i>
                                    <span>Check</span>
                                </label>
                            </div>
                            <div class="method-option">
                                <input type="radio" name="method" id="method_cash" value="cash">
                                <label for="method_cash">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Cash</span>
                                </label>
                            </div>
                            <div class="method-option">
                                <input type="radio" name="method" id="method_mobile" value="mobile_pay">
                                <label for="method_mobile">
                                    <i class="fas fa-mobile-alt"></i>
                                    <span>Mobile Pay</span>
                                </label>
                            </div>
                            <div class="method-option">
                                <input type="radio" name="method" id="method_paypal" value="paypal">
                                <label for="method_paypal">
                                    <i class="fab fa-paypal"></i>
                                    <span>PayPal</span>
                                </label>
                            </div>
                            <div class="method-option">
                                <input type="radio" name="method" id="method_ethereum" value="ethereum">
                                <label for="method_ethereum">
                                    <i class="fab fa-ethereum"></i>
                                    <span>Ethereum</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-info-circle"></i> Status</label>
                            <select name="status" id="payStatus">
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar-check"></i> Paid Date</label>
                            <input type="date" name="paid_date" id="payPaidDate" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Period From</label>
                            <input type="date" name="period_from" id="payPeriodFrom" value="<?= date('Y-m-01') ?>">
                        </div>
                        <div class="form-group">
                            <label>Period To</label>
                            <input type="date" name="period_to" id="payPeriodTo" value="<?= date('Y-m-t') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-sticky-note"></i> Notes (optional)</label>
                        <textarea name="notes" id="payNotes" placeholder="Payment notes..."></textarea>
                    </div>
                </form>
            </div>

            <!-- Right: Summary Sidebar -->
            <div>
                <div class="checkout-card order-summary">
                    <h2><i class="fas fa-receipt"></i> Order Summary</h2>

                    <?php if (!empty($pendingPayments)): ?>
                    <div class="pending-list">
                        <div style="font-size: 13px; font-weight: 600; color: #6b7280; margin-bottom: 6px;">
                            Pending Payments (<?= count($pendingPayments) ?>)
                        </div>
                        <?php foreach ($pendingPayments as $pp):
                            $ppDate = !empty($pp['due_date']) ? new DateTime($pp['due_date']) : null;
                            $isOverdue = $ppDate && $ppDate < new DateTime();
                        ?>
                        <div class="pending-item">
                            <div>
                                <div class="p-month"><?= $ppDate ? e($ppDate->format('F Y')) : 'N/A' ?></div>
                                <div class="p-due">Due: <?= $ppDate ? e($ppDate->format('M j, Y')) : 'N/A' ?>
                                    <span class="pending-badge <?= $isOverdue ? 'overdue' : 'pending' ?>"><?= $isOverdue ? 'Overdue' : 'Pending' ?></span>
                                </div>
                            </div>
                            <div class="p-amount">$<?= number_format((float)($pp['amount'] ?? 0), 2) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="summary-line">
                        <span>Renter</span>
                        <span><strong><?= e($renterName) ?></strong></span>
                    </div>
                    <div class="summary-line">
                        <span>Monthly Rent</span>
                        <span>$<?= number_format($monthlyRent, 2) ?></span>
                    </div>
                    <div class="summary-line">
                        <span>Payment Amount</span>
                        <span id="summaryAmount">$<?= number_format($monthlyRent, 2) ?></span>
                    </div>
                    <div class="summary-line">
                        <span>Method</span>
                        <span id="summaryMethod">Credit Card</span>
                    </div>
                    <div class="summary-line total">
                        <span>Total</span>
                        <span id="summaryTotal">$<?= number_format($monthlyRent, 2) ?></span>
                    </div>

                    <button type="button" class="btn-checkout" id="btnReview">
                        <i class="fas fa-arrow-right"></i> Review Payment
                    </button>
                    <div class="secure-note">
                        <i class="fas fa-lock"></i> Secure payment processing
                    </div>

                    <?php if (!empty($paidPayments)): ?>
                    <div class="payment-history-mini">
                        <h3>Recent Payments</h3>
                        <?php foreach (array_slice($paidPayments, 0, 3) as $hp):
                            $hpDate = !empty($hp['paid_date']) ? new DateTime($hp['paid_date']) : null;
                        ?>
                        <div class="history-item">
                            <span><?= $hpDate ? e($hpDate->format('M j, Y')) : 'N/A' ?></span>
                            <span>$<?= number_format((float)($hp['amount'] ?? 0), 2) ?></span>
                            <span class="paid-badge">Paid</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2: Review -->
    <div id="checkoutStep2" style="display: none;">
        <div class="checkout-card" style="max-width: 640px; margin: 0 auto;">
            <h2><i class="fas fa-clipboard-check"></i> Review Payment</h2>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Please review the payment details below before confirming.</p>

            <div class="renter-info">
                <div class="renter-avatar"><?= e($initials) ?></div>
                <div class="renter-details">
                    <h3><?= e($renterName) ?></h3>
                    <p><?= e($propertyName) ?></p>
                </div>
            </div>

            <div style="background: #f8fafc; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div class="summary-line"><span>Amount</span><strong id="reviewAmount">-</strong></div>
                <div class="summary-line"><span>Payment Method</span><span id="reviewMethod">-</span></div>
                <div class="summary-line"><span>Status</span><span id="reviewStatus">-</span></div>
                <div class="summary-line"><span>Due Date</span><span id="reviewDueDate">-</span></div>
                <div class="summary-line"><span>Paid Date</span><span id="reviewPaidDate">-</span></div>
                <div class="summary-line"><span>Period</span><span id="reviewPeriod">-</span></div>
                <div class="summary-line" id="reviewNotesRow" style="display:none;"><span>Notes</span><span id="reviewNotes">-</span></div>
                <div class="summary-line total"><span>Total Charge</span><strong id="reviewTotal">-</strong></div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="button" class="btn-checkout" style="background: #6b7280; flex: 1;" id="btnBackToDetails">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <button type="button" class="btn-checkout" style="flex: 2;" id="btnConfirm">
                    <i class="fas fa-check-circle"></i> Confirm Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Step 3: Confirmation (shown after form submit) -->
    <div id="checkoutStep3" style="display: none;">
        <div class="checkout-card" style="max-width: 500px; margin: 0 auto; text-align: center; padding: 3rem 2rem;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #d1fae5; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-check" style="font-size: 36px; color: #10b981;"></i>
            </div>
            <h2 style="justify-content: center; margin-bottom: 0.5rem;">Payment Recorded!</h2>
            <p style="color: #6b7280; margin-bottom: 2rem;">The payment has been successfully recorded for <strong><?= e($renterName) ?></strong>.</p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="<?= route('admin.payments') ?>" class="btn-checkout" style="display: inline-block; text-decoration: none; text-align: center; max-width: 200px;">
                    <i class="fas fa-list"></i> View Payments
                </a>
                <a href="<?= route('admin.renters') ?>" class="btn-checkout" style="display: inline-block; text-decoration: none; text-align: center; background: #2c5aa0; max-width: 200px;">
                    <i class="fas fa-users"></i> Back to Renters
                </a>
            </div>
        </div>
    </div>
</div>

<script>
const methodLabels = {
    credit_card: 'Credit Card',
    debit_card: 'Debit Card',
    bank_transfer: 'Bank Transfer',
    check: 'Check',
    cash: 'Cash',
    mobile_pay: 'Mobile Pay',
    paypal: 'PayPal',
    ethereum: 'Ethereum'
};

// Live update summary sidebar
const amountInput = document.getElementById('payAmount');
const methodInputs = document.querySelectorAll('input[name="method"]');
const statusSelect = document.getElementById('payStatus');
const paidDateInput = document.getElementById('payPaidDate');

function updateSummary() {
    const amount = parseFloat(amountInput.value) || 0;
    const method = document.querySelector('input[name="method"]:checked')?.value || '';
    document.getElementById('summaryAmount').textContent = '$' + amount.toFixed(2);
    document.getElementById('summaryTotal').textContent = '$' + amount.toFixed(2);
    document.getElementById('summaryMethod').textContent = methodLabels[method] || method;
}

amountInput.addEventListener('input', updateSummary);
methodInputs.forEach(r => r.addEventListener('change', updateSummary));

// Toggle paid date based on status
statusSelect.addEventListener('change', function() {
    if (this.value === 'paid') {
        paidDateInput.value = new Date().toISOString().split('T')[0];
        paidDateInput.closest('.form-group').style.display = '';
    } else {
        paidDateInput.value = '';
        paidDateInput.closest('.form-group').style.display = 'none';
    }
});

// Step navigation
function setStep(step) {
    document.getElementById('checkoutStep1').style.display = step === 1 ? '' : 'none';
    document.getElementById('checkoutStep2').style.display = step === 2 ? '' : 'none';
    document.getElementById('checkoutStep3').style.display = step === 3 ? '' : 'none';

    // Update step indicators
    for (let i = 1; i <= 3; i++) {
        const el = document.getElementById('step' + i);
        el.className = 'step' + (i < step ? ' completed' : (i === step ? ' active' : ''));
    }
    for (let i = 1; i <= 2; i++) {
        const conn = document.getElementById('conn' + i);
        conn.className = 'step-connector' + (i < step ? ' active' : '');
    }
}

// Review button
document.getElementById('btnReview').addEventListener('click', function() {
    // Validate
    const amount = parseFloat(amountInput.value);
    if (!amount || amount <= 0) {
        Swal.fire({ icon: 'warning', title: 'Invalid Amount', text: 'Please enter a valid payment amount.', confirmButtonColor: '#2c5aa0' });
        return;
    }
    const dueDate = document.getElementById('payDueDate').value;
    if (!dueDate) {
        Swal.fire({ icon: 'warning', title: 'Due Date Required', text: 'Please select a due date.', confirmButtonColor: '#2c5aa0' });
        return;
    }

    const method = document.querySelector('input[name="method"]:checked')?.value || '';
    const status = statusSelect.value;
    const paidDate = paidDateInput.value;
    const periodFrom = document.getElementById('payPeriodFrom').value;
    const periodTo = document.getElementById('payPeriodTo').value;
    const notes = document.getElementById('payNotes').value;

    // Populate review
    document.getElementById('reviewAmount').textContent = '$' + amount.toFixed(2);
    document.getElementById('reviewMethod').textContent = methodLabels[method] || method;
    document.getElementById('reviewStatus').innerHTML = status === 'paid'
        ? '<span style="background:#d1fae5;color:#065f46;padding:2px 10px;border-radius:12px;font-size:12px;font-weight:600;">Paid</span>'
        : '<span style="background:#fef3c7;color:#92400e;padding:2px 10px;border-radius:12px;font-size:12px;font-weight:600;">Pending</span>';
    document.getElementById('reviewDueDate').textContent = dueDate ? new Date(dueDate + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';
    document.getElementById('reviewPaidDate').textContent = paidDate ? new Date(paidDate + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A';

    if (periodFrom && periodTo) {
        document.getElementById('reviewPeriod').textContent =
            new Date(periodFrom + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + ' - ' +
            new Date(periodTo + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    } else {
        document.getElementById('reviewPeriod').textContent = 'N/A';
    }

    if (notes.trim()) {
        document.getElementById('reviewNotes').textContent = notes;
        document.getElementById('reviewNotesRow').style.display = '';
    } else {
        document.getElementById('reviewNotesRow').style.display = 'none';
    }

    document.getElementById('reviewTotal').textContent = '$' + amount.toFixed(2);

    setStep(2);
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Back to details
document.getElementById('btnBackToDetails').addEventListener('click', function() {
    setStep(1);
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Confirm & submit
document.getElementById('btnConfirm').addEventListener('click', function() {
    const form = document.getElementById('checkoutForm');
    form.action = '/admin/payments';
    form.method = 'POST';

    // Show step 3 immediately for visual feedback, then submit
    setStep(3);
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Small delay so user sees the confirmation, then submit
    setTimeout(() => {
        form.submit();
    }, 800);
});
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
