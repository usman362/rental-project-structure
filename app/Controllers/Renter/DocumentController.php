<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Models/Renter.php';
require_once BASE_PATH . '/app/Models/Document.php';
require_once BASE_PATH . '/app/Models/Notification.php';

class DocumentController extends Controller
{
    /**
     * Upload a document from the renter portal
     */
    public function store(): void
    {
        // Verify CSRF token
        if (!CSRF::verify()) {
            flash('error', 'Invalid security token. Please try again.');
            $this->redirect(route('renter.portal') . '?tab=documents');
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
            $this->redirect(route('renter.portal') . '?tab=documents');
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
        $allowedMimes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx'];

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileMime = $file['type'] ?? '';

        if (!in_array($fileExtension, $allowedExtensions)) {
            flash('error', 'Invalid file type. Allowed: PDF, JPG, PNG, GIF, DOC, DOCX.');
            $this->redirect(route('renter.portal') . '?tab=documents');
            return;
        }

        // Validate file size (max 10MB)
        $maxSize = 10 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            flash('error', 'File is too large. Maximum size is 10MB.');
            $this->redirect(route('renter.portal') . '?tab=documents');
            return;
        }

        // Create uploads directory if needed
        $uploadDir = BASE_PATH . '/public/uploads/documents/renter_' . $renterId;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $uniqueName = date('Ymd_His') . '_' . uniqid() . '.' . $fileExtension;
        $filePath = $uploadDir . '/' . $uniqueName;
        $relativePath = 'uploads/documents/renter_' . $renterId . '/' . $uniqueName;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            flash('error', 'Failed to save uploaded file. Please try again.');
            $this->redirect(route('renter.portal') . '?tab=documents');
            return;
        }

        // Save document record to database
        $docId = Document::create([
            'renter_id' => $renterId,
            'property_id' => $propertyId,
            'user_id' => $userId,
            'title' => $title,
            'type' => $docType,
            'file_name' => $file['name'],
            'file_path' => $relativePath,
            'file_size' => $file['size'],
            'mime_type' => $fileMime,
            'uploaded_by' => 'renter'
        ]);

        if ($docId > 0) {
            Notification::create([
                'user_id' => $userId,
                'type' => 'info',
                'icon' => 'file-alt',
                'title' => 'Document Uploaded',
                'message' => 'Your document "' . $title . '" has been uploaded successfully.',
                'link' => '/renter/portal?tab=documents'
            ]);

            flash('success', 'Document "' . htmlspecialchars($title) . '" uploaded successfully!');
        } else {
            flash('error', 'Failed to save document record. Please try again.');
        }

        $this->redirect(route('renter.portal') . '?tab=documents');
    }

    /**
     * Download a document
     */
    public function download(): void
    {
        $user = auth();
        if (!$user || !isset($user['id'])) {
            flash('error', 'Unauthorized');
            $this->redirect(route('login'));
            return;
        }

        $userId = (int) $user['id'];
        $renter = Renter::findByUserId($userId);

        if (!$renter) {
            flash('error', 'Renter record not found.');
            $this->redirect(route('renter.portal'));
            return;
        }

        $renterId = (int) $renter['id'];

        // Get document ID from URL
        $docId = (int) ($_GET['id'] ?? 0);
        if ($docId <= 0) {
            flash('error', 'Invalid document.');
            $this->redirect(route('renter.portal') . '?tab=documents');
            return;
        }

        $document = Document::find($docId);

        if (!$document) {
            flash('error', 'Document not found.');
            $this->redirect(route('renter.portal') . '?tab=documents');
            return;
        }

        // Verify the document belongs to this renter
        if ((int)($document['renter_id'] ?? 0) !== $renterId) {
            flash('error', 'Access denied.');
            $this->redirect(route('renter.portal') . '?tab=documents');
            return;
        }

        $filePath = BASE_PATH . '/public/' . $document['file_path'];

        if (!file_exists($filePath)) {
            flash('error', 'File not found on server. Please contact management.');
            $this->redirect(route('renter.portal') . '?tab=documents');
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
}
