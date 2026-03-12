<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/MaintenanceRequest.php';
require_once BASE_PATH . '/app/Models/Property.php';
require_once BASE_PATH . '/app/Models/Renter.php';

class MaintenanceController extends Controller
{
    /**
     * Display maintenance requests with filters
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
        if (!empty($_GET['priority'])) {
            $filters['priority'] = $_GET['priority'];
        }
        if (!empty($_GET['property_id'])) {
            $filters['property_id'] = $_GET['property_id'];
        }

        // Get maintenance requests
        $maintenanceRequests = MaintenanceRequest::all($filters);

        // Get status counts
        $statusCounts = MaintenanceRequest::countByStatus();

        // Get properties for dropdown
        $properties = Property::all();

        // Get renters for dropdown
        $renters = Renter::all();

        // Count by priority (for stats)
        $highPriorityCount = count(array_filter($maintenanceRequests, function($r) {
            return $r['priority'] === 'high' || $r['priority'] === 'emergency';
        }));

        // Count completed this month
        $completedThisMonth = count(array_filter($maintenanceRequests, function($r) {
            if ($r['status'] !== 'completed') return false;
            $createdMonth = date('Y-m', strtotime($r['created_at']));
            $currentMonth = date('Y-m');
            return $createdMonth === $currentMonth;
        }));

        $this->view('admin.maintenance', [
            'title' => 'Maintenance',
            'active' => 'maintenance',
            'user' => auth(),
            'maintenanceRequests' => $maintenanceRequests,
            'statusCounts' => $statusCounts,
            'properties' => $properties,
            'renters' => $renters,
            'highPriorityCount' => $highPriorityCount,
            'completedThisMonth' => $completedThisMonth
        ]);
    }

    /**
     * Store a new maintenance request
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
        if (empty($_POST['property_id'])) {
            $errors[] = 'Property is required.';
        }
        if (empty($_POST['title'])) {
            $errors[] = 'Title is required.';
        }
        if (empty($_POST['description'])) {
            $errors[] = 'Description is required.';
        }
        if (empty($_POST['category'])) {
            $errors[] = 'Category is required.';
        }

        if (!empty($errors)) {
            session_flash_errors(['error' => $errors]);
            session_flash_old_input($_POST);
            $this->back();
            return;
        }

        // Create maintenance request
        $requestData = [
            'property_id' => (int) $_POST['property_id'],
            'renter_id' => !empty($_POST['renter_id']) ? (int) $_POST['renter_id'] : null,
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category' => $_POST['category'],
            'priority' => $_POST['priority'] ?? 'medium',
            'status' => 'open',
            'estimated_cost' => !empty($_POST['estimated_cost']) ? (float) $_POST['estimated_cost'] : null
        ];

        MaintenanceRequest::create($requestData);

        flash('success', 'Maintenance request created successfully!');
        $this->redirect(route('admin.maintenance'));
    }

    /**
     * Update maintenance request status
     */
    public function updateStatus(int $id): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Get maintenance request
        $request = MaintenanceRequest::find($id);
        if (!$request) {
            flash('error', 'Maintenance request not found.');
            $this->back();
            return;
        }

        // Validate status
        $validStatuses = ['open', 'in_progress', 'completed', 'closed'];
        $newStatus = $_POST['status'] ?? 'open';

        if (!in_array($newStatus, $validStatuses)) {
            flash('error', 'Invalid status.');
            $this->back();
            return;
        }

        // Update status
        MaintenanceRequest::updateStatus($id, $newStatus);

        // Update additional fields if provided
        $updateData = [];
        if (!empty($_POST['assigned_to'])) {
            $updateData['assigned_to'] = (int) $_POST['assigned_to'];
        }
        if (!empty($_POST['actual_cost'])) {
            $updateData['actual_cost'] = (float) $_POST['actual_cost'];
        }

        if (!empty($updateData)) {
            MaintenanceRequest::update($id, $updateData);
        }

        flash('success', 'Maintenance request updated successfully!');
        $this->redirect(route('admin.maintenance'));
    }
}
