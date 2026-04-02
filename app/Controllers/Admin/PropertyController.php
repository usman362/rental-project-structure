<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/Property.php';
require_once BASE_PATH . '/app/Models/Document.php';

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

        // Get documents grouped by property
        $allDocuments = Document::all();
        $documentsByProperty = [];
        foreach ($allDocuments as $doc) {
            $pid = (int) ($doc['property_id'] ?? 0);
            if ($pid > 0) {
                $documentsByProperty[$pid][] = $doc;
            }
        }

        // Pass data to view
        $this->view('admin.properties', [
            'properties' => $properties,
            'statusCounts' => $statusCounts,
            'title' => 'Property Management',
            'active' => 'properties',
            'user' => auth(),
            'filters' => $filters,
            'documentsByProperty' => $documentsByProperty
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

    /**
     * Upload a document to a property
     */
    public function uploadDocument(int $propertyId): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        // Check property exists
        $property = Property::find($propertyId);
        if (!$property) {
            flash('error', 'Property not found.');
            $this->back();
            return;
        }

        // Check if file was uploaded
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds maximum upload size.',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds maximum form size.',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded. Please select a file.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Missing temporary folder.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write file.',
            ];

            $errorCode = $_FILES['document']['error'] ?? UPLOAD_ERR_NO_FILE;
            $errorMsg = $errorMessages[$errorCode] ?? 'File upload failed.';

            flash('error', $errorMsg);
            $this->back();
            return;
        }

        $file = $_FILES['document'];
        $title = trim($_POST['doc_title'] ?? '');
        $docType = trim($_POST['doc_type'] ?? 'other');

        // Validate title
        if (empty($title)) {
            $title = pathinfo($file['name'], PATHINFO_FILENAME);
        }

        // Validate file type
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'xls', 'xlsx'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            flash('error', 'Invalid file type. Allowed: PDF, JPG, PNG, GIF, DOC, DOCX, XLS, XLSX.');
            $this->back();
            return;
        }

        // Validate file size (max 10MB)
        $maxSize = 10 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            flash('error', 'File is too large. Maximum size is 10MB.');
            $this->back();
            return;
        }

        // Create uploads directory
        $uploadDir = BASE_PATH . '/public/uploads/documents/property_' . $propertyId;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $uniqueName = date('Ymd_His') . '_' . uniqid() . '.' . $fileExtension;
        $filePath = $uploadDir . '/' . $uniqueName;
        $relativePath = 'uploads/documents/property_' . $propertyId . '/' . $uniqueName;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            flash('error', 'Failed to save uploaded file. Please try again.');
            $this->back();
            return;
        }

        $user = auth();

        // Save document record
        $docId = Document::create([
            'renter_id' => null,
            'property_id' => $propertyId,
            'user_id' => $user['id'] ?? null,
            'title' => $title,
            'type' => $docType,
            'file_name' => $file['name'],
            'file_path' => $relativePath,
            'file_size' => $file['size'],
            'mime_type' => $file['type'] ?? 'application/octet-stream',
            'uploaded_by' => 'admin'
        ]);

        if ($docId > 0) {
            flash('success', 'Document "' . htmlspecialchars($title) . '" uploaded successfully!');
        } else {
            flash('error', 'Failed to save document record.');
        }

        $this->back();
    }

    /**
     * Download a property document
     */
    public function downloadDocument(): void
    {
        $docId = (int) ($_GET['id'] ?? 0);
        if ($docId <= 0) {
            flash('error', 'Invalid document.');
            $this->back();
            return;
        }

        $document = Document::find($docId);
        if (!$document) {
            flash('error', 'Document not found.');
            $this->back();
            return;
        }

        $filePath = BASE_PATH . '/public/' . $document['file_path'];

        if (!file_exists($filePath)) {
            flash('error', 'File not found on server.');
            $this->back();
            return;
        }

        // Serve the file for download
        $mimeType = $document['mime_type'] ?? 'application/octet-stream';
        $fileName = $document['file_name'] ?? basename($filePath);

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache, must-revalidate');
        readfile($filePath);
        exit;
    }

    /**
     * Delete a property document
     */
    public function deleteDocument(int $docId): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'CSRF token mismatch. Please try again.');
            $this->back();
            return;
        }

        $document = Document::find($docId);
        if (!$document) {
            flash('error', 'Document not found.');
            $this->back();
            return;
        }

        // Delete actual file
        $filePath = BASE_PATH . '/public/' . $document['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete database record
        Document::delete($docId);

        flash('success', 'Document deleted successfully!');
        $this->back();
    }
}
