<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/Renter.php';
require_once BASE_PATH . '/app/Models/Payment.php';
require_once BASE_PATH . '/app/Models/Notification.php';

class PaymentController extends Controller
{
    /**
     * Process a rent payment from the renter portal
     */
    public function store(): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'Invalid security token. Please try again.');
            $this->redirect(route('renter.portal') . '?tab=payments');
            return;
        }

        // Get authenticated user
        $user = auth();
        if (!$user || !isset($user['id'])) {
            flash('error', 'Unauthorized');
            $this->redirect(route('login'));
            return;
        }

        $userId = (int) $user['id'];

        // Get renter record
        $renter = Renter::findByUserId($userId);
        if (!$renter) {
            flash('error', 'Renter record not found.');
            $this->redirect(route('renter.portal'));
            return;
        }

        $renterId = (int) $renter['id'];
        $propertyId = (int) ($renter['property_id'] ?? 0);

        // Validate inputs
        $amount = (float) ($_POST['amount'] ?? 0);
        $method = trim($_POST['method'] ?? '');
        $paymentId = isset($_POST['payment_id']) ? (int) $_POST['payment_id'] : null;

        $errors = [];

        if ($amount <= 0) {
            $errors[] = 'Invalid payment amount.';
        }

        $validMethods = ['credit_card', 'debit_card', 'bank_transfer', 'check', 'cash', 'mobile_pay'];
        if (empty($method) || !in_array($method, $validMethods)) {
            $errors[] = 'Please select a valid payment method.';
        }

        if (!empty($errors)) {
            flash('error', implode(' ', $errors));
            $this->redirect(route('renter.portal') . '?tab=payments');
            return;
        }

        // If a specific payment_id was provided, update that payment to paid
        if ($paymentId) {
            $existingPayment = Payment::find($paymentId);
            if ($existingPayment && (int)($existingPayment['renter_id'] ?? 0) === $renterId) {
                Payment::update($paymentId, [
                    'status' => 'paid',
                    'method' => $method,
                    'paid_date' => date('Y-m-d'),
                    'receipt_number' => 'RCP-' . strtoupper(substr(md5((string)$paymentId . date('YmdHis')), 0, 8))
                ]);

                Notification::create([
                    'user_id' => $userId,
                    'type' => 'payment',
                    'icon' => 'check-circle',
                    'title' => 'Payment Confirmed',
                    'message' => 'Your payment of $' . number_format($amount, 2) . ' has been processed successfully.',
                    'link' => '/renter/portal?tab=payments'
                ]);

                flash('success', 'Payment of $' . number_format($amount, 2) . ' processed successfully!');
                $this->redirect(route('renter.portal') . '?tab=payments');
                return;
            }
        }

        // Otherwise, find the earliest pending payment for this renter and mark it paid
        $payments = Payment::forRenter($renterId);
        $pendingPayment = null;

        foreach ($payments as $p) {
            if (($p['status'] ?? '') === 'pending') {
                if ($pendingPayment === null || ($p['due_date'] ?? '') < ($pendingPayment['due_date'] ?? '')) {
                    $pendingPayment = $p;
                }
            }
        }

        if ($pendingPayment) {
            Payment::update((int)$pendingPayment['id'], [
                'status' => 'paid',
                'method' => $method,
                'paid_date' => date('Y-m-d'),
                'receipt_number' => 'RCP-' . strtoupper(substr(md5((string)$pendingPayment['id'] . date('YmdHis')), 0, 8))
            ]);

            Notification::create([
                'user_id' => $userId,
                'type' => 'payment',
                'icon' => 'check-circle',
                'title' => 'Payment Confirmed',
                'message' => 'Your payment of $' . number_format($amount, 2) . ' has been processed successfully.',
                'link' => '/renter/portal?tab=payments'
            ]);

            flash('success', 'Payment of $' . number_format($amount, 2) . ' processed successfully!');
        } else {
            // No pending payment found - create a new payment record
            Payment::create([
                'renter_id' => $renterId,
                'property_id' => $propertyId,
                'amount' => $amount,
                'due_date' => date('Y-m-d'),
                'paid_date' => date('Y-m-d'),
                'method' => $method,
                'status' => 'paid',
                'receipt_number' => 'RCP-' . strtoupper(substr(md5(date('YmdHis') . (string)$renterId), 0, 8))
            ]);

            flash('success', 'Payment of $' . number_format($amount, 2) . ' recorded successfully!');
        }

        $this->redirect(route('renter.portal') . '?tab=payments');
    }
}
