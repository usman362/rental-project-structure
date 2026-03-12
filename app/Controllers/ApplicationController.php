<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/Property.php';
require_once BASE_PATH . '/app/Models/Application.php';
require_once BASE_PATH . '/app/Core/CSRF.php';

class ApplicationController extends Controller
{
    /**
     * Display rental application page with available properties
     */
    public function index(): void
    {
        // Load available properties
        $properties = Property::available();

        // Convert properties array to JSON for JavaScript property search
        $propertyPayload = json_encode($properties);

        $this->view('public.application', [
            'properties' => $properties,
            'propertyPayload' => $propertyPayload
        ]);
    }

    /**
     * Handle POST application submission
     */
    public function store(): void
    {
        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(route('application'));
            return;
        }

        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Extract and validate required fields
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $employment = $_POST['employment'] ?? '';
        $monthlyIncome = $_POST['monthly_income'] ?? null;
        $creditScore = $_POST['credit_score'] ?? null;
        $propertyId = $_POST['property_id'] ?? null;

        // Validate required fields
        $errors = [];

        if (empty($firstName)) {
            $errors[] = 'First name is required.';
        }

        if (empty($lastName)) {
            $errors[] = 'Last name is required.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }

        if (empty($propertyId)) {
            $errors[] = 'Property selection is required.';
        }

        // If validation fails, flash errors and go back
        if (!empty($errors)) {
            session_flash_errors($errors);
            session_flash_old_input($_POST);
            $this->back();
            return;
        }

        try {
            // Create the application
            $applicationId = Application::create([
                'property_id' => (int) $propertyId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'employment' => $employment,
                'monthly_income' => $monthlyIncome ? (float) $monthlyIncome : null,
                'credit_score' => $creditScore ? (int) $creditScore : null,
                'source' => 'web',
                'status' => 'pending'
            ]);

            flash('success', 'Your application has been submitted successfully! We will review it and contact you soon.');
            $this->redirect(route('application'));
        } catch (Exception $e) {
            flash('error', 'An error occurred while submitting your application. Please try again.');
            session_flash_old_input($_POST);
            $this->back();
        }
    }
}
