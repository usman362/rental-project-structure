<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/Renter.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Models/Property.php';
require_once BASE_PATH . '/app/Core/CSRF.php';

class RenterController extends Controller
{
    /**
     * Display renters list with filters
     */
    public function index(): void
    {
        // Get filter parameters
        $filters = [];
        if (!empty($_GET['q'])) {
            $filters['search'] = $_GET['q'];
        }
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (!empty($_GET['property_id'])) {
            $filters['property_id'] = $_GET['property_id'];
        }

        // Get renters with filters
        $renters = Renter::all($filters);

        // Get properties for filter dropdown
        $properties = Property::all();

        // Pass to view
        $this->view('admin.renters', [
            'renters' => $renters,
            'properties' => $properties,
            'title' => 'Renters',
            'active' => 'renters',
            'user' => auth()
        ]);
    }

    /**
     * Store a new renter (via AJAX/form)
     */
    public function store(): void
    {
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
        $propertyId = $_POST['property_id'] ?? null;
        $moveInDate = $_POST['move_in_date'] ?? '';
        $leaseEndDate = $_POST['lease_end'] ?? null;
        $monthlyRent = $_POST['monthly_rent'] ?? 0;
        $securityDeposit = $_POST['security_deposit'] ?? 0;
        $notes = $_POST['notes'] ?? '';

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
        if (empty($phone)) {
            $errors[] = 'Phone is required.';
        }
        if (empty($propertyId)) {
            $errors[] = 'Property selection is required.';
        }
        if (empty($moveInDate)) {
            $errors[] = 'Move-in date is required.';
        }

        // If validation fails, flash errors and go back
        if (!empty($errors)) {
            flash('error', implode(' ', $errors));
            $this->back();
            return;
        }

        try {
            // Generate default password
            $defaultPassword = 'Welcome@123';

            // Create user record
            $userId = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'username' => strtolower(substr($firstName, 0, 1) . $lastName . time()),
                'password' => $defaultPassword,
                'role' => 'renter'
            ]);

            // Create renter record
            $renterId = Renter::create([
                'user_id' => $userId,
                'property_id' => (int) $propertyId,
                'move_in_date' => $moveInDate,
                'lease_end' => $leaseEndDate ?: null,
                'monthly_rent' => (float) $monthlyRent,
                'security_deposit' => (float) $securityDeposit,
                'notes' => $notes,
                'status' => 'active'
            ]);

            $generatedUsername = strtolower(substr($firstName, 0, 1) . $lastName . time());
            flash('success', "Renter {$firstName} {$lastName} created successfully! Default password: {$defaultPassword}");
            $this->redirect(route('admin.renters'));
        } catch (Exception $e) {
            flash('error', 'An error occurred while creating the renter. Please try again.');
            $this->back();
        }
    }

    /**
     * Update a renter
     */
    public function update(int $id): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Get renter
        $renter = Renter::find($id);
        if (!$renter) {
            flash('error', 'Renter not found.');
            $this->redirect(route('admin.renters'));
            return;
        }

        // Extract fields
        $firstName = $_POST['first_name'] ?? $renter['first_name'];
        $lastName = $_POST['last_name'] ?? $renter['last_name'];
        $email = $_POST['email'] ?? $renter['email'];
        $phone = $_POST['phone'] ?? $renter['phone'];
        $propertyId = $_POST['property_id'] ?? $renter['property_id'];
        $moveInDate = $_POST['move_in_date'] ?? $renter['move_in_date'];
        $leaseEndDate = $_POST['lease_end'] ?? $renter['lease_end'];
        $monthlyRent = $_POST['monthly_rent'] ?? $renter['monthly_rent'];
        $status = $_POST['status'] ?? $renter['status'];

        try {
            // Update user record
            User::update($renter['user_id'], [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone
            ]);

            // Update renter record
            Renter::update($id, [
                'property_id' => (int) $propertyId,
                'move_in_date' => $moveInDate,
                'lease_end' => $leaseEndDate,
                'monthly_rent' => (float) $monthlyRent,
                'status' => $status
            ]);

            flash('success', "Renter {$firstName} {$lastName} updated successfully!");
            $this->redirect(route('admin.renters'));
        } catch (Exception $e) {
            flash('error', 'An error occurred while updating the renter. Please try again.');
            $this->back();
        }
    }

    /**
     * Delete a renter
     */
    public function delete(int $id): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        try {
            // Get renter info before deletion
            $renter = Renter::find($id);
            if (!$renter) {
                flash('error', 'Renter not found.');
                $this->redirect(route('admin.renters'));
                return;
            }

            // Delete renter record
            Renter::delete($id);

            // Optionally delete user record
            if ($renter['user_id']) {
                User::delete($renter['user_id']);
            }

            flash('success', "Renter {$renter['first_name']} {$renter['last_name']} deleted successfully!");
            $this->redirect(route('admin.renters'));
        } catch (Exception $e) {
            flash('error', 'An error occurred while deleting the renter. Please try again.');
            $this->back();
        }
    }
}
