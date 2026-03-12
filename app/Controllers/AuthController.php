<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Core/CSRF.php';

class AuthController extends Controller
{
    /**
     * Handle POST login request
     */
    public function login(): void
    {
        // Only process POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('public.home');
            return;
        }

        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Get username and password from POST
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validate fields are not empty
        if (empty($username) || empty($password)) {
            flash('error', 'Username/email and password are required.');
            session_flash_old_input(['username' => $username]);
            $this->back();
            return;
        }

        // Find user by username or email
        $user = User::findByUsernameOrEmail($username);

        // Verify password if user exists
        if ($user && password_verify($password, $user['password'])) {
            // Successful login - set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user;

            flash('success', 'Welcome, ' . e($user['first_name'] ?? $user['username']) . '!');

            // Redirect based on user role
            if ($user['role'] === 'admin') {
                $this->redirect(route('admin.dashboard'));
            } else {
                $this->redirect(route('renter.portal'));
            }
        } else {
            // Invalid credentials
            flash('error', 'Invalid username/email or password. Please try again.');
            session_flash_old_input(['username' => $username]);
            $this->back();
        }
    }

    /**
     * Handle logout
     */
    public function logout(): void
    {
        // Set flash message BEFORE destroying session
        flash('success', 'You have been logged out successfully.');

        // Clear session data
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        $this->redirect(route('home'));
    }
}
