<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/Application.php';
require_once BASE_PATH . '/app/Models/Property.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Models/Renter.php';

class ApplicationController extends Controller
{
    /**
     * Display all applications with filters and status counts
     */
    public function index(): void
    {
        // Get filters from request
        $filters = [];

        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }

        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }

        if (!empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }

        if (!empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }

        // Get applications with filters
        $applications = Application::all($filters);

        // Get status counts for quick stats
        $statusCounts = Application::countByStatus();
        $totalCount = array_sum($statusCounts);

        // Add total to status counts
        $statusCounts['total'] = $totalCount;

        // Pass data to view
        $this->view('admin.applications', [
            'applications' => $applications,
            'statusCounts' => $statusCounts,
            'title' => 'Application Management',
            'active' => 'applications',
            'user' => auth(),
            'filters' => $filters
        ]);
    }

    /**
     * Update application status (reviewed, approved, rejected, etc)
     */
    public function updateStatus(int $id): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Get the new status from POST
        $status = $_POST['status'] ?? null;

        if (!$status) {
            flash('error', 'Status is required.');
            $this->back();
            return;
        }

        // Update application status
        $user = auth();
        $reviewedBy = $user['id'] ?? null;

        Application::updateStatus($id, $status, $reviewedBy);

        flash('success', "Application status updated to: {$status}");
        $this->back();
    }

    /**
     * Approve an application and create a renter account
     */
    public function approve(int $id): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Get the application
        $application = Application::find($id);

        if (!$application) {
            flash('error', 'Application not found.');
            $this->back();
            return;
        }

        // Create user account for renter
        $firstName = $application['first_name'] ?? '';
        $lastName = $application['last_name'] ?? '';
        $email = $application['email'] ?? '';
        $username = strtolower(str_replace(' ', '.', "{$firstName} {$lastName}"));
        $defaultPassword = 'Welcome@123';

        try {
            // Check if user already exists
            $existingUser = User::findByEmail($email);

            if ($existingUser) {
                $userId = $existingUser['id'];
            } else {
                // Create new user
                $userId = User::create([
                    'username' => $username,
                    'email' => $email,
                    'password' => $defaultPassword,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $application['phone'] ?? null,
                    'role' => 'renter'
                ]);
            }

            // Create renter record linked to property
            $propertyId = $application['property_id'] ?? null;
            if ($propertyId) {
                Renter::create([
                    'user_id' => $userId,
                    'property_id' => $propertyId,
                    'move_in_date' => $application['desired_move_in'] ?? date('Y-m-d'),
                    'lease_end' => null,
                    'monthly_rent' => 0,
                    'security_deposit' => 0,
                    'status' => 'active',
                    'emergency_contact' => null,
                    'notes' => null
                ]);
            }

            // Update application status to approved
            $user = auth();
            $reviewedBy = $user['id'] ?? null;
            Application::updateStatus($id, 'approved', $reviewedBy);

            // Flash success message with credentials
            $message = "Application approved successfully!\n";
            $message .= "Renter Account Created:\n";
            $message .= "Email: {$email}\n";
            $message .= "Username: {$username}\n";
            $message .= "Temporary Password: {$defaultPassword}";

            flash('success', $message);
        } catch (Exception $e) {
            flash('error', 'Error approving application: ' . $e->getMessage());
        }

        $this->back();
    }
}
