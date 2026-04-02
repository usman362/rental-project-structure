<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/Renter.php';
require_once BASE_PATH . '/app/Models/MaintenanceRequest.php';
require_once BASE_PATH . '/app/Models/Notification.php';

class MaintenanceController extends Controller
{
    /**
     * Store a new maintenance request from the renter portal
     */
    public function store(): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'Invalid security token. Please try again.');
            $this->redirect(route('renter.portal') . '?tab=maintenance');
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
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $priority = trim($_POST['priority'] ?? 'medium');
        $location = trim($_POST['location'] ?? '');
        $accessInstructions = trim($_POST['access_instructions'] ?? '');

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Title is required.';
        }
        if (empty($description)) {
            $errors[] = 'Description is required.';
        }
        if (empty($category)) {
            $errors[] = 'Please select the type of issue.';
        }
        if (empty($priority)) {
            $errors[] = 'Please select the urgency level.';
        }

        $validCategories = ['plumbing', 'electrical', 'appliances', 'heating_cooling', 'flooring', 'doors_windows', 'other'];
        if (!empty($category) && !in_array($category, $validCategories)) {
            $errors[] = 'Invalid issue type selected.';
        }

        $validPriorities = ['emergency', 'high', 'medium', 'low'];
        if (!empty($priority) && !in_array($priority, $validPriorities)) {
            $errors[] = 'Invalid urgency level selected.';
        }

        if (!empty($errors)) {
            flash('error', implode(' ', $errors));
            // Store old input for form repopulation
            session_flash_old_input($_POST);
            $this->redirect(route('renter.portal') . '?tab=maintenance');
            return;
        }

        // Build full description with location and access instructions
        $fullDescription = $description;
        if (!empty($location)) {
            $fullDescription .= "\n\nLocation: " . ucfirst(str_replace('_', ' ', $location));
        }
        if (!empty($accessInstructions)) {
            $fullDescription .= "\n\nAccess Instructions: " . $accessInstructions;
        }

        // Create the maintenance request
        $requestId = MaintenanceRequest::create([
            'property_id' => $propertyId,
            'renter_id' => $renterId,
            'title' => $title,
            'description' => $fullDescription,
            'category' => $category,
            'priority' => $priority,
            'status' => 'open'
        ]);

        if ($requestId > 0) {
            Notification::create([
                'user_id' => $userId,
                'type' => 'maintenance',
                'icon' => 'wrench',
                'title' => 'Maintenance Request Submitted',
                'message' => 'Your maintenance request "' . $title . '" has been submitted and is being reviewed.',
                'link' => '/renter/portal?tab=maintenance'
            ]);

            flash('success', 'Maintenance request submitted successfully! We will review it within 24 hours.');
        } else {
            flash('error', 'Failed to submit maintenance request. Please try again.');
        }

        $this->redirect(route('renter.portal') . '?tab=maintenance');
    }
}
