<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/Property.php';

class PropertyController extends Controller
{
    /**
     * Display all properties with filters and status counts
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

        if (!empty($_GET['type'])) {
            $filters['type'] = $_GET['type'];
        }

        // Get properties with filters
        $properties = Property::all($filters);

        // Get status counts for quick stats
        $statusCounts = Property::countByStatus();
        $totalCount = array_sum($statusCounts);

        // Add total to status counts
        $statusCounts['total'] = $totalCount;

        // Pass data to view
        $this->view('admin.properties', [
            'properties' => $properties,
            'statusCounts' => $statusCounts,
            'title' => 'Property Management',
            'active' => 'properties',
            'user' => auth(),
            'filters' => $filters
        ]);
    }

    /**
     * Create a new property
     */
    public function store(): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Get form data
        $name = $_POST['name'] ?? null;
        $address = $_POST['address'] ?? null;
        $city = $_POST['city'] ?? null;
        $state = $_POST['state'] ?? null;
        $zip = $_POST['zip'] ?? null;
        $rent = $_POST['monthly_rent'] ?? null;

        // Validate required fields
        if (!$name || !$address || !$rent) {
            flash('error', 'Name, address, and rent are required.');
            session_flash_old_input($_POST);
            $this->back();
            return;
        }

        try {
            // Create property
            $propertyId = Property::create([
                'name' => $name,
                'address' => $address,
                'unit' => $_POST['unit'] ?? null,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'type' => $_POST['type'] ?? 'apartment',
                'monthly_rent' => (float) $rent,
                'deposit' => (float) ($_POST['deposit'] ?? $rent),
                'status' => $_POST['status'] ?? 'available',
                'bedrooms' => (int) ($_POST['bedrooms'] ?? 0),
                'bathrooms' => (float) ($_POST['bathrooms'] ?? 0),
                'sqft' => (int) ($_POST['sqft'] ?? 0),
                'description' => $_POST['description'] ?? null,
                'amenities' => isset($_POST['amenities']) ?
                    array_filter(array_map('trim', explode(',', $_POST['amenities']))) :
                    []
            ]);

            flash('success', "Property '{$name}' created successfully!");
        } catch (Exception $e) {
            flash('error', 'Error creating property: ' . $e->getMessage());
            session_flash_old_input($_POST);
        }

        $this->back();
    }

    /**
     * Update an existing property
     */
    public function update(int $id): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Check if property exists
        $property = Property::find($id);
        if (!$property) {
            flash('error', 'Property not found.');
            $this->back();
            return;
        }

        // Get form data
        $name = $_POST['name'] ?? $property['name'];
        $address = $_POST['address'] ?? $property['address'];
        $rent = $_POST['monthly_rent'] ?? $property['monthly_rent'];

        // Validate required fields
        if (!$name || !$address || !$rent) {
            flash('error', 'Name, address, and rent are required.');
            session_flash_old_input($_POST);
            $this->back();
            return;
        }

        try {
            // Update property
            Property::update($id, [
                'name' => $name,
                'address' => $address,
                'unit' => $_POST['unit'] ?? null,
                'city' => $_POST['city'] ?? $property['city'],
                'state' => $_POST['state'] ?? $property['state'],
                'zip' => $_POST['zip'] ?? $property['zip'],
                'type' => $_POST['type'] ?? $property['type'],
                'monthly_rent' => (float) $rent,
                'deposit' => (float) ($_POST['deposit'] ?? $property['deposit']),
                'status' => $_POST['status'] ?? $property['status'],
                'bedrooms' => (int) ($_POST['bedrooms'] ?? $property['bedrooms']),
                'bathrooms' => (float) ($_POST['bathrooms'] ?? $property['bathrooms']),
                'sqft' => (int) ($_POST['sqft'] ?? $property['sqft']),
                'description' => $_POST['description'] ?? $property['description'],
                'amenities' => isset($_POST['amenities']) ?
                    array_filter(array_map('trim', explode(',', $_POST['amenities']))) :
                    (json_decode($property['amenities'] ?? '[]', true) ?? [])
            ]);

            flash('success', "Property '{$name}' updated successfully!");
        } catch (Exception $e) {
            flash('error', 'Error updating property: ' . $e->getMessage());
            session_flash_old_input($_POST);
        }

        $this->back();
    }

    /**
     * Delete a property
     */
    public function delete(int $id): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Check if property exists
        $property = Property::find($id);
        if (!$property) {
            flash('error', 'Property not found.');
            $this->back();
            return;
        }

        try {
            $propertyName = $property['name'];
            Property::delete($id);
            flash('success', "Property '{$propertyName}' deleted successfully!");
        } catch (Exception $e) {
            flash('error', 'Error deleting property: ' . $e->getMessage());
        }

        $this->back();
    }
}
