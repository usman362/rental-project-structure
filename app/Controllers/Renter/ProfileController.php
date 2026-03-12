<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Models/Renter.php';
require_once BASE_PATH . '/app/Models/Property.php';

class ProfileController extends Controller
{
    /**
     * Display the renter's profile
     */
    public function index(): void
    {
        // Get authenticated user
        $user = auth();
        if (!$user) {
            $this->redirect(route('auth.login'));
            return;
        }

        // Get renter information by user_id
        $renter = Renter::findByUserId($user['id']);
        if (!$renter) {
            flash('error', 'Renter profile not found');
            $this->redirect(route('renter.portal'));
            return;
        }

        // Get property information if available
        $property = null;
        if ($renter['property_id']) {
            $property = Property::find($renter['property_id']);
        }

        // Get flash messages
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        // Pass data to view
        $this->view('renter.profile', [
            'user' => $user,
            'renter' => $renter,
            'property' => $property,
            'flash' => $flash,
            'title' => 'My Profile',
            'active' => 'profile'
        ]);
    }

    /**
     * Update the renter's profile
     */
    public function update(): void
    {
        // Verify CSRF token
        $csrf = $_POST['_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
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

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $dob = trim($_POST['dob'] ?? '');
        $emergencyContact = trim($_POST['emergency_contact'] ?? '');
        $emergencyPhone = trim($_POST['emergency_phone'] ?? '');
        $relationship = trim($_POST['relationship'] ?? '');
        $occupation = trim($_POST['occupation'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $permanentAddress = trim($_POST['permanent_address'] ?? '');

        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        if (empty($phone)) {
            $errors[] = 'Phone number is required';
        }

        // Handle password change if provided
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                $errors[] = 'Current password is required to change password';
            } elseif (!password_verify($currentPassword, $user['password'])) {
                $errors[] = 'Current password is incorrect';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'New passwords do not match';
            } elseif (strlen($newPassword) < 8) {
                $errors[] = 'New password must be at least 8 characters long';
            }
        }

        // If there are validation errors, redirect back with flash message
        if (!empty($errors)) {
            flash('error', implode(', ', $errors));
            $this->back();
            return;
        }

        // Update user information
        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone
        ];

        // Add password if changing
        if (!empty($newPassword)) {
            $userData['password'] = $newPassword;
        }

        $userUpdateSuccess = User::update($user['id'], $userData);

        // Update renter information
        $renterData = [
            'emergency_contact' => $emergencyContact ?: null,
            'notes' => $bio ?: null
        ];

        // Try to update renter record
        $renter = Renter::findByUserId($user['id']);
        if ($renter) {
            Renter::update($renter['id'], $renterData);
        }

        // Flash success message
        flash('success', 'Profile updated successfully');
        $this->back();
    }
}
