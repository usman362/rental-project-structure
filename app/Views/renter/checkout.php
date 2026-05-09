<?php
$title = 'Payment Checkout';
ob_start();

$renter = $renter ?? [];
$payment = $payment ?? [];
$paidPayments = $paidPayments ?? [];
$gateways = $gateways ?? [];

$renterName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
$propertyName = $renter['property_name'] ?? 'N/A';
$amount = (float)($payment['amount'] ?? 0);
$paymentId = (int)($payment['id'] ?? 0);
$dueDate = !empty($payment['due_date']) ? new DateTime($payment['due_date']) : null;
$isOverdue = $dueDate && $dueDate < new DateTime();

$paypalEnabled = ($gateways['pg_paypal_enabled'] ?? '0') === '1' && !empty($gateways['pg_paypal_client_id']);
$ethEnabled = ($gateways['pg_eth_enabled'] ?? '0') === '1' && !empty($gateways['pg_eth_wallet']);
$paypalClientId = $gateways['pg_paypal_client_id'] ?? '';
$paypalMode = $gateways['pg_paypal_mode'] ?? 'sandbox';
$ethWallet = $gateways['pg_eth_wallet'] ?? '';
$ethNetwork = $gateways['pg_eth_network'] ?? 'mainnet';
$ethRate = (float)($gateways['pg_eth_rate'] ?? 2000);
$noGateways = !$paypalEnabled && !$ethEnabled;
?>

<style>
.co-wrap { max-width: 900px; margin: 0 auto; padding: 1.5rem; }
.co-back { display: inline-flex; align-items: center; gap: 6px; color: #2c5aa0; text-decoration: none; font-weight: 500; font-size: 14px; padding: 8px 14px; border-radius: 8px; margin-bottom: 1.5rem; transition: background 0.2s; }
.co-back:hover { background: #eef2ff; }

.co-steps { display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; }
.co-s { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #9ca3af; font-weight: 500; }
.co-s.active { color: #2c5aa0; }
.co-s.done { color: #10b981; }
.co-sn { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; background: #e5e7eb; color: #6b7280; }
.co-s.active .co-sn { background: #2c5aa0; color: #fff; }
.co-s.done .co-sn { background: #10b981; color: #fff; }
.co-sl { width: 60px; height: 2px; background: #e5e7eb; margin: 0 12px; }
.co-sl.active { background: #10b981; }

.co-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; }
.co-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); padding: 1.5rem; }
.co-card h2 { font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; }
.co-card h2 i { color: #2c5aa0; }

.pay-banner { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%); border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #dbeafe; }
.pay-banner-left h3 { font-size: 16px; font-weight: 600; color: #1f2937; margin: 0; }
.pay-banner-left p { font-size: 13px; color: #6b7280; margin: 4px 0 0; }
.pay-big { font-size: 28px; font-weight: 700; color: #2c5aa0; }
.pay-tag { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-left: 8px; }
.pay-tag.overdue { background: #fee2e2; color: #991b1b; }
.pay-tag.pending { background: #fef3c7; color: #92400e; }

/* Gateway selector */
.gw-options { display: flex; gap: 12px; margin-bottom: 1.5rem; }
.gw-opt { flex: 1; position: relative; }
.gw-opt input { position: absolute; opacity: 0; width: 0; height: 0; }
.gw-opt label { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px 16px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; transition: all 0.2s; text-align: center; }
.gw-opt label .gw-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
.gw-opt label .gw-icon i { font-size: 26px; color: #fff; }
.gw-opt label .gw-name { font-size: 15px; font-weight: 600; color: #374151; }
.gw-opt label .gw-desc { font-size: 12px; color: #9ca3af; }
.gw-opt input:checked + label { border-color: #2c5aa0; background: #eef2ff; box-shadow: 0 0 0 3px rgba(44,90,160,0.1); }

/* PayPal container */
#paypal-button-container { min-height: 50px; margin-top: 1rem; }

/* ETH section */
.eth-info { background: #f8fafc; border-radius: 10px; padding: 1.25rem; margin-top: 1rem; }
.eth-addr { font-family: monospace; font-size: 13px; background: #1f2937; color: #10b981; padding: 10px 14px; border-radius: 8px; word-break: break-all; margin: 8px 0; display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.eth-addr button { background: #374151; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; white-space: nowrap; }
.eth-addr button:hover { background: #4b5563; }
.eth-connect-btn { display: block; width: 100%; padding: 14px; background: #627eea; color: #fff; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s; margin-top: 1rem; }
.eth-connect-btn:hover { background: #4c63d2; }
.eth-connect-btn i { margin-right: 6px; }
.eth-status { padding: 10px; border-radius: 8px; margin-top: 10px; font-size: 13px; display: none; }

/* Summary */
.co-summary { position: sticky; top: 5rem; }
.sl { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 14px; color: #4b5563; }
.sl.total { border-top: 2px solid #e5e7eb; margin-top: 8px; padding-top: 14px; font-size: 20px; font-weight: 700; color: #1f2937; }
.sec-txt { text-align: center; font-size: 12px; color: #9ca3af; margin-top: 10px; }
.sec-txt i { margin-right: 4px; }
.hist-mini { margin-top: 1.25rem; }
.hist-mini h3 { font-size: 13px; font-weight: 600; color: #6b7280; margin-bottom: 6px; }
.hist-item { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; font-size: 13px; border-bottom: 1px solid #f3f4f6; }
.hist-item:last-child { border-bottom: none; }
.hist-badge { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; }

.success-icon { width: 80px; height: 80px; border-radius: 50%; background: #d1fae5; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
.success-icon i { font-size: 36px; color: #10b981; }
.btn-back-portal { display: inline-block; padding: 14px 28px; background: #2c5aa0; color: #fff; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; text-decoration: none; transition: background 0.2s; }
.btn-back-portal:hover { background: #1d3a6e; }

.no-gw-msg { text-align: center; padding: 2rem; color: #6b7280; }
.no-gw-msg i { font-size: 48px; color: #d1d5db; margin-bottom: 1rem; display: block; }

@media (max-width: 768px) { .co-grid { grid-template-columns: 1fr; } .gw-options { flex-direction: column; } }
</style>

<div class="co-wrap">
    <a href="<?= route('renter.portal') ?>?tab=payments" class="co-back"><i class="fas fa-arrow-left"></i> Back to Payments</a>

    <!-- Steps -->
    <div class="co-steps">
        <div class="co-s active" id="s1"><div class="co-sn">1</div><span>Choose Method</span></div>
        <div class="co-sl" id="c1"></div>
        <div class="co-s" id="s2"><div class="co-sn">2</div><span>Pay</span></div>
        <div class="co-sl" id="c2"></div>
        <div class="co-s" id="s3"><div class="co-sn">3</div><span>Confirmation</span></div>
    </div>

    <!-- STEP 1: Choose Gateway -->
    <div id="view1">
        <div class="co-grid">
            <div class="co-card">
                <h2><i class="fas fa-wallet"></i> Choose Payment Method</h2>

                <div class="pay-banner">
                    <div class="pay-banner-left">
                        <h3><?= $dueDate ? e($dueDate->format('F Y')) . ' Rent' : 'Rent Payment' ?></h3>
                        <p>Due: <?= $dueDate ? e($dueDate->format('F j, Y')) : 'N/A' ?>
                            <span class="pay-tag <?= $isOverdue ? 'overdue' : 'pending' ?>"><?= $isOverdue ? 'Overdue' : 'Due' ?></span>
                        </p>
                    </div>
                    <div class="pay-big">$<?= number_format($amount, 2) ?></div>
                </div>

                <?php if ($noGateways): ?>
                <div class="no-gw-msg">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>No Payment Methods Available</h3>
                    <p>Payment gateways have not been configured yet. Please contact your property manager.</p>
                </div>
                <?php else: ?>

                <div class="gw-options">
                    <?php if ($paypalEnabled): ?>
                    <div class="gw-opt">
                        <input type="radio" name="gateway" id="gw_paypal" value="paypal" checked>
                        <label for="gw_paypal">
                            <div class="gw-icon" style="background: #003087;"><i class="fab fa-paypal"></i></div>
                            <div class="gw-name">PayPal</div>
                            <div class="gw-desc">Card or PayPal balance</div>
                        </label>
                    </div>
                    <?php endif; ?>
                    <?php if ($ethEnabled): ?>
                    <div class="gw-opt">
                        <input type="radio" name="gateway" id="gw_eth" value="ethereum" <?= !$paypalEnabled ? 'checked' : '' ?>>
                        <label for="gw_eth">
                            <div class="gw-icon" style="background: #627eea;"><i class="fab fa-ethereum"></i></div>
                            <div class="gw-name">Ethereum</div>
                            <div class="gw-desc">Pay with ETH</div>
                        </label>
                    </div>
                    <?php endif; ?>
                </div>

                <button type="button" class="eth-connect-btn" style="background: #2c5aa0;" id="btnProceed">
                    <i class="fas fa-arrow-right"></i> Proceed to Payment
                </button>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div>
                <div class="co-card co-summary">
                    <h2><i class="fas fa-receipt"></i> Summary</h2>
                    <div class="sl"><span>Property</span><strong style="font-size: 13px;"><?= e($propertyName) ?></strong></div>
                    <div class="sl"><span>Period</span><span><?= $dueDate ? e($dueDate->format('F Y')) : 'N/A' ?></span></div>
                    <div class="sl"><span>Due Date</span><span><?= $dueDate ? e($dueDate->format('M j, Y')) : 'N/A' ?></span></div>
                    <div class="sl total"><span>Total</span><span>$<?= number_format($amount, 2) ?></span></div>
                    <div class="sec-txt"><i class="fas fa-lock"></i> Secure payment processing</div>

                    <?php if (!empty($paidPayments)): ?>
                    <div class="hist-mini">
                        <h3>Recent Payments</h3>
                        <?php foreach (array_slice($paidPayments, 0, 3) as $hp):
                            $hpDate = !empty($hp['paid_date']) ? new DateTime($hp['paid_date']) : null;
                        ?>
                        <div class="hist-item">
                            <span><?= $hpDate ? e($hpDate->format('M j, Y')) : 'N/A' ?></span>
                            <span>$<?= number_format((float)($hp['amount'] ?? 0), 2) ?></span>
                            <span class="hist-badge">Paid</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 2: Pay -->
    <div id="view2" style="display: none;">
        <div class="co-card" style="max-width: 600px; margin: 0 auto;">
            <!-- PayPal Payment -->
            <div id="paypalSection" style="display: none;">
                <h2><i class="fab fa-paypal" style="color: #003087;"></i> Pay with PayPal</h2>
                <div class="pay-banner">
                    <div class="pay-banner-left">
                        <h3><?= $dueDate ? e($dueDate->format('F Y')) . ' Rent' : 'Payment' ?></h3>
                        <p><?= e($propertyName) ?></p>
                    </div>
                    <div class="pay-big">$<?= number_format($amount, 2) ?></div>
                </div>
                <div id="paypal-button-container"></div>
                <div class="sec-txt" style="margin-top: 1rem;"><i class="fas fa-lock"></i> Secured by PayPal</div>
            </div>

            <!-- Ethereum Payment -->
            <div id="ethSection" style="display: none;">
                <h2><i class="fab fa-ethereum" style="color: #627eea;"></i> Pay with Ethereum</h2>
                <div class="pay-banner">
                    <div class="pay-banner-left">
                        <h3><?= $dueDate ? e($dueDate->format('F Y')) . ' Rent' : 'Payment' ?></h3>
                        <p><?= e($propertyName) ?></p>
                    </div>
                    <div class="pay-big">$<?= number_format($amount, 2) ?></div>
                </div>

                <div class="eth-info">
                    <p style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 6px;">Send ETH to this address:</p>
                    <div class="eth-addr">
                        <span id="ethAddrText"><?= e($ethWallet) ?></span>
                        <button onclick="copyEthAddr()"><i class="fas fa-copy"></i> Copy</button>
                    </div>
                    <p style="font-size: 12px; color: #6b7280;">Network: <strong><?= e(ucfirst($ethNetwork)) ?></strong></p>
                    <p style="font-size: 12px; color: #ef4444; margin-top: 6px;"><i class="fas fa-exclamation-triangle"></i> Make sure you send on the correct network.</p>
                </div>

                <div style="margin-top: 1.25rem;">
                    <p style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Or connect your wallet:</p>
                    <button type="button" class="eth-connect-btn" id="btnConnectWallet">
                        <i class="fab fa-ethereum"></i> Connect MetaMask & Pay
                    </button>
                </div>

                <div class="eth-status" id="ethStatus"></div>

                <div style="text-align: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <p style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Already sent ETH manually?</p>
                    <button type="button" style="background: #10b981; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;" id="btnManualConfirm">
                        <i class="fas fa-check"></i> I've Sent the Payment
                    </button>
                </div>
            </div>

            <button type="button" style="background: none; border: none; color: #2c5aa0; cursor: pointer; font-size: 14px; font-weight: 500; margin-top: 1.25rem; padding: 8px 0;" id="btnBackTo1">
                <i class="fas fa-arrow-left"></i> Choose different method
            </button>
        </div>
    </div>

    <!-- STEP 3: Success -->
    <div id="view3" style="display: none;">
        <div class="co-card" style="max-width: 480px; margin: 0 auto; text-align: center; padding: 3rem 2rem;">
            <div class="success-icon"><i class="fas fa-check"></i></div>
            <h2 style="justify-content: center; margin-bottom: 0.5rem;">Payment Successful!</h2>
            <p style="color: #6b7280; margin-bottom: 0.5rem;">Your payment of <strong>$<?= number_format($amount, 2) ?></strong> has been processed.</p>
            <p style="color: #9ca3af; font-size: 13px; margin-bottom: 0.5rem;" id="successMethodText"></p>
            <p style="color: #9ca3af; font-size: 13px; margin-bottom: 2rem;" id="successTxText"></p>
            <a href="<?= route('renter.portal') ?>?tab=payments" class="btn-back-portal">
                <i class="fas fa-arrow-left"></i> Back to Payments
            </a>
        </div>
    </div>
</div>

<!-- Hidden form for server-side payment recording -->
<form id="payForm" method="POST" action="/renter/payments" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="payment_id" value="<?= $paymentId ?>">
    <input type="hidden" name="amount" value="<?= number_format($amount, 2, '.', '') ?>">
    <input type="hidden" name="method" id="formMethod" value="paypal">
</form>

<?php if ($paypalEnabled): ?>
<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=<?= e($paypalClientId) ?>&currency=USD"></script>
<?php endif; ?>

<script>
const AMOUNT = '<?= number_format($amount, 2, '.', '') ?>';
const PAYMENT_ID = <?= $paymentId ?>;
const ETH_WALLET = '<?= e($ethWallet) ?>';
const ETH_NETWORK = '<?= e($ethNetwork) ?>';
const ETH_RATE = <?= $ethRate ?>;
let selectedGateway = document.querySelector('input[name="gateway"]:checked')?.value || '';

function goStep(n) {
    document.getElementById('view1').style.display = n === 1 ? '' : 'none';
    document.getElementById('view2').style.display = n === 2 ? '' : 'none';
    document.getElementById('view3').style.display = n === 3 ? '' : 'none';
    for (let i = 1; i <= 3; i++) {
        document.getElementById('s' + i).className = 'co-s' + (i < n ? ' done' : (i === n ? ' active' : ''));
    }
    for (let i = 1; i <= 2; i++) {
        document.getElementById('c' + i).className = 'co-sl' + (i < n ? ' active' : '');
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Proceed button
const proceedBtn = document.getElementById('btnProceed');
if (proceedBtn) {
    proceedBtn.addEventListener('click', () => {
        selectedGateway = document.querySelector('input[name="gateway"]:checked')?.value || '';
        document.getElementById('paypalSection').style.display = selectedGateway === 'paypal' ? '' : 'none';
        document.getElementById('ethSection').style.display = selectedGateway === 'ethereum' ? '' : 'none';
        goStep(2);

        if (selectedGateway === 'paypal') {
            renderPayPalButtons();
        }
    });
}

// Back
document.getElementById('btnBackTo1')?.addEventListener('click', () => goStep(1));

// Record payment on server
function recordPaymentOnServer(method, txId) {
    document.getElementById('formMethod').value = method;
    document.getElementById('successMethodText').textContent = 'Paid via ' + (method === 'paypal' ? 'PayPal' : 'Ethereum');
    if (txId) {
        document.getElementById('successTxText').textContent = 'Transaction: ' + txId.substring(0, 20) + '...';
    }
    goStep(3);
    // Submit after showing success
    setTimeout(() => {
        document.getElementById('payForm').submit();
    }, 1500);
}

// ========== PAYPAL ==========
let paypalRendered = false;
function renderPayPalButtons() {
    if (paypalRendered || typeof paypal === 'undefined') return;
    paypalRendered = true;

    paypal.Buttons({
        style: { layout: 'vertical', color: 'blue', shape: 'rect', label: 'pay' },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    description: 'Rent Payment #' + PAYMENT_ID,
                    amount: { value: AMOUNT, currency_code: 'USD' }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                const txId = details.id || data.orderID || '';
                recordPaymentOnServer('paypal', txId);
            });
        },
        onError: function(err) {
            Swal.fire({ icon: 'error', title: 'Payment Failed', text: 'PayPal encountered an error. Please try again.', confirmButtonColor: '#2c5aa0' });
            console.error('PayPal error:', err);
        },
        onCancel: function() {
            Swal.fire({ icon: 'info', title: 'Payment Cancelled', text: 'You cancelled the PayPal payment.', confirmButtonColor: '#2c5aa0' });
        }
    }).render('#paypal-button-container');
}

// ========== ETHEREUM ==========
function copyEthAddr() {
    navigator.clipboard.writeText(ETH_WALLET).then(() => {
        Swal.fire({ icon: 'success', title: 'Copied!', text: 'Wallet address copied to clipboard.', timer: 1500, showConfirmButton: false });
    });
}

// MetaMask connection
const connectBtn = document.getElementById('btnConnectWallet');
if (connectBtn) {
    connectBtn.addEventListener('click', async () => {
        const status = document.getElementById('ethStatus');

        if (typeof window.ethereum === 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'MetaMask Not Found',
                html: 'Please install <a href="https://metamask.io" target="_blank" style="color:#2c5aa0;font-weight:600;">MetaMask</a> browser extension to pay with Ethereum.',
                confirmButtonColor: '#2c5aa0'
            });
            return;
        }

        try {
            connectBtn.disabled = true;
            connectBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting...';

            // Request accounts
            const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
            const sender = accounts[0];

            status.style.display = 'block';
            status.style.background = '#eef2ff';
            status.style.color = '#2c5aa0';
            status.innerHTML = '<i class="fas fa-wallet"></i> Connected: ' + sender.substring(0, 6) + '...' + sender.substring(38);

            connectBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending transaction...';

            // Convert USD amount to ETH (rough estimate — in production, use a price oracle)
            // For now we use a placeholder conversion
            const ethAmount = (parseFloat(AMOUNT) / ETH_RATE).toFixed(6);
            const weiAmount = '0x' + (BigInt(Math.floor(parseFloat(ethAmount) * 1e18))).toString(16);

            const txHash = await window.ethereum.request({
                method: 'eth_sendTransaction',
                params: [{
                    from: sender,
                    to: ETH_WALLET,
                    value: weiAmount,
                    gas: '0x5208' // 21000 gas for simple transfer
                }]
            });

            status.style.background = '#d1fae5';
            status.style.color = '#065f46';
            status.innerHTML = '<i class="fas fa-check-circle"></i> Transaction sent! Hash: ' + txHash.substring(0, 16) + '...';

            recordPaymentOnServer('ethereum', txHash);

        } catch (err) {
            connectBtn.disabled = false;
            connectBtn.innerHTML = '<i class="fab fa-ethereum"></i> Connect MetaMask & Pay';

            if (err.code === 4001) {
                status.style.display = 'block';
                status.style.background = '#fef3c7';
                status.style.color = '#92400e';
                status.innerHTML = '<i class="fas fa-info-circle"></i> Transaction rejected by user.';
            } else {
                status.style.display = 'block';
                status.style.background = '#fee2e2';
                status.style.color = '#991b1b';
                status.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error: ' + (err.message || 'Transaction failed');
            }
        }
    });
}

// Manual ETH confirmation
const manualBtn = document.getElementById('btnManualConfirm');
if (manualBtn) {
    manualBtn.addEventListener('click', () => {
        Swal.fire({
            title: 'Confirm Manual Payment',
            html: '<p style="color:#6b7280;">Enter your transaction hash so we can verify the payment:</p>',
            input: 'text',
            inputPlaceholder: '0x...',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: '<i class="fas fa-check"></i> Confirm',
            inputValidator: (value) => {
                if (!value || value.length < 10) return 'Please enter a valid transaction hash';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                recordPaymentOnServer('ethereum', result.value);
            }
        });
    });
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/renter.php';
?>
