<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/Payment.php';
require_once BASE_PATH . '/app/Models/Renter.php';

class PaymentController extends Controller
{
    /**
     * Display payments list with filters
     */
    public function index(): void
    {
        // Get filter parameters
        $filters = [];
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (!empty($_GET['method'])) {
            $filters['method'] = $_GET['method'];
        }
        if (!empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (!empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }

        // Get payments
        $payments = Payment::all($filters);

        // Get payment summary
        $summary = Payment::summary();

        // Get all renters for dropdown
        $renters = Renter::all();

        $this->view('admin.payments', [
            'title' => 'Payments',
            'active' => 'payments',
            'user' => auth(),
            'payments' => $payments,
            'summary' => $summary,
            'renters' => $renters
        ]);
    }

    /**
     * Store a new payment
     */
    public function store(): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Validate required fields
        $errors = [];
        if (empty($_POST['renter_id'])) {
            $errors[] = 'Renter is required.';
        }
        if (empty($_POST['amount'])) {
            $errors[] = 'Amount is required.';
        }
        if (empty($_POST['due_date'])) {
            $errors[] = 'Due date is required.';
        }
        if (empty($_POST['method'])) {
            $errors[] = 'Payment method is required.';
        }

        if (!empty($errors)) {
            session_flash_errors(['error' => $errors]);
            session_flash_old_input($_POST);
            $this->back();
            return;
        }

        // Get renter to fetch property_id
        $renter = Renter::find((int) $_POST['renter_id']);
        if (!$renter) {
            flash('error', 'Renter not found.');
            $this->back();
            return;
        }

        // Generate receipt number
        $receiptNumber = 'RCP-' . date('Ym') . '-' . str_pad((string) (Payment::count() + 1), 4, '0', STR_PAD_LEFT);

        // Create payment
        $paymentData = [
            'renter_id' => (int) $_POST['renter_id'],
            'property_id' => (int) $renter['property_id'],
            'amount' => (float) $_POST['amount'],
            'due_date' => $_POST['due_date'],
            'paid_date' => !empty($_POST['paid_date']) ? $_POST['paid_date'] : null,
            'method' => $_POST['method'],
            'status' => $_POST['status'] ?? 'pending',
            'period_from' => !empty($_POST['period_from']) ? $_POST['period_from'] : null,
            'period_to' => !empty($_POST['period_to']) ? $_POST['period_to'] : null,
            'notes' => $_POST['notes'] ?? null,
            'receipt_number' => $receiptNumber
        ];

        Payment::create($paymentData);

        flash('success', 'Payment recorded successfully!');
        $this->redirect(route('admin.payments'));
    }

    /**
     * Update a payment
     */
    public function update(int $id): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Get payment to verify it exists
        $payment = Payment::find($id);
        if (!$payment) {
            flash('error', 'Payment not found.');
            $this->back();
            return;
        }

        // Build update data from POST
        $updateData = [];

        if (isset($_POST['amount'])) {
            $updateData['amount'] = (float) $_POST['amount'];
        }
        if (isset($_POST['due_date'])) {
            $updateData['due_date'] = $_POST['due_date'];
        }
        if (isset($_POST['paid_date'])) {
            $updateData['paid_date'] = !empty($_POST['paid_date']) ? $_POST['paid_date'] : null;
        }
        if (isset($_POST['method'])) {
            $updateData['method'] = $_POST['method'];
        }
        if (isset($_POST['status'])) {
            $updateData['status'] = $_POST['status'];
        }
        if (isset($_POST['period_from'])) {
            $updateData['period_from'] = !empty($_POST['period_from']) ? $_POST['period_from'] : null;
        }
        if (isset($_POST['period_to'])) {
            $updateData['period_to'] = !empty($_POST['period_to']) ? $_POST['period_to'] : null;
        }
        if (isset($_POST['notes'])) {
            $updateData['notes'] = $_POST['notes'];
        }

        Payment::update($id, $updateData);

        flash('success', 'Payment updated successfully!');
        $this->redirect(route('admin.payments'));
    }
}
