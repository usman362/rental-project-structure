<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Models/SupportRequest.php';

class HelpController extends Controller
{
    /**
     * Display the help center
     */
    public function index(): void
    {
        // Get authenticated user
        $user = auth();
        if (!$user) {
            $this->redirect(route('auth.login'));
            return;
        }

        // Static FAQ data (these are informational, fine to keep as array)
        $faqs = [
            [
                'question' => 'How do I submit a maintenance request?',
                'answer' => 'To submit a maintenance request, navigate to the Maintenance section in your dashboard. Click "Submit Request," describe the issue, select the area of your unit, and upload photos if available. Your request will be reviewed within 24 hours.'
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept payment via bank transfer, credit card, debit card, and ACH transfers. All payments should be made to the designated account provided in your lease agreement. Online payments can be made through our secure portal.'
            ],
            [
                'question' => 'How can I provide notice to vacate?',
                'answer' => 'Submit a written notice through the Documents section of your portal. Make sure to provide at least 30 days notice as required by your lease agreement. You will receive a confirmation email once processed.'
            ],
            [
                'question' => 'What is included in my lease?',
                'answer' => 'Your lease document outlines all terms and conditions of your tenancy, including rent amount, move-in date, lease term, utilities, pet policy, and other important information. You can review your complete lease in the Documents section.'
            ],
            [
                'question' => 'How do I reset my password?',
                'answer' => 'Click on "Forgot Password" on the login page and enter your email address. You will receive a password reset link via email within 5 minutes. Follow the link to create a new password.'
            ],
            [
                'question' => 'Can I request a lease modification?',
                'answer' => 'Lease modifications require written approval from management. Submit your request through the portal with detailed information about the requested change. A property manager will review and contact you within 3-5 business days.'
            ],
            [
                'question' => 'What should I do in case of an emergency?',
                'answer' => 'For emergencies that pose a safety risk (fire, gas leak, etc.), call 911 immediately. For other urgent maintenance needs, contact our 24/7 emergency line listed in the Emergency Contact section of your portal.'
            ],
            [
                'question' => 'How do I download my lease documents?',
                'answer' => 'Navigate to the Documents section of your portal to view and download all your lease documents, including the lease agreement, move-in inspection reports, and any amendments.'
            ]
        ];

        // Get support requests from database
        $supportRequests = SupportRequest::forUser((int)$user['id']);

        // Get flash messages
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        // Pass data to view
        $this->view('renter.help', [
            'user' => $user,
            'faqs' => $faqs,
            'supportRequests' => $supportRequests,
            'flash' => $flash,
            'title' => 'Help Center',
            'active' => 'help'
        ]);
    }

    /**
     * Store a support request
     */
    public function store(): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'Invalid CSRF token');
            $this->back();
            return;
        }

        // Get authenticated user
        $user = auth();
        if (!$user) {
            flash('error', 'Unauthorized');
            $this->redirect(route('auth.login'));
            return;
        }

        // Validate inputs
        $errors = [];

        $subject = trim($_POST['subject'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($subject)) {
            $errors[] = 'Subject is required';
        }
        if (empty($category)) {
            $errors[] = 'Category is required';
        }
        if (empty($message)) {
            $errors[] = 'Message is required';
        }
        if (strlen($message) < 10) {
            $errors[] = 'Message must be at least 10 characters long';
        }

        // If there are validation errors, redirect back with flash message
        if (!empty($errors)) {
            flash('error', implode(', ', $errors));
            $this->back();
            return;
        }

        // Valid categories
        $validCategories = [
            'payments_billing' => 'Payments & Billing',
            'maintenance' => 'Maintenance Requests',
            'lease_documents' => 'Lease & Documents',
            'account_settings' => 'Account Settings',
            'other' => 'Other'
        ];

        // Verify category is valid
        if (!isset($validCategories[$category])) {
            flash('error', 'Invalid category selected');
            $this->back();
            return;
        }

        // Create support request in database
        SupportRequest::create([
            'user_id' => $user['id'],
            'subject' => $subject,
            'category' => $validCategories[$category],
            'message' => $message,
            'status' => 'open'
        ]);

        // Flash success message
        flash('success', 'Support request submitted successfully. We will review it shortly.');
        $this->back();
    }
}
