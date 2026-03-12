<?php
$title = 'Maintenance Requests';
$active = 'maintenance';
ob_start();

// Ensure all variables are available
$maintenanceRequests = $maintenanceRequests ?? [];
$statusCounts = $statusCounts ?? ['open' => 0, 'in_progress' => 0, 'completed' => 0, 'closed' => 0];
$properties = $properties ?? [];
$renters = $renters ?? [];
$highPriorityCount = $highPriorityCount ?? 0;
$completedThisMonth = $completedThisMonth ?? 0;
?>

<div class="content-header">
    <h1>Maintenance Management</h1>
    <div class="content-actions">
        <button class="btn btn-secondary" onclick="exportMaintenance()">
            <i class="fas fa-download"></i> Export
        </button>
        <button class="btn btn-primary" onclick="createRequest()">
            <i class="fas fa-plus"></i> New Request
        </button>
    </div>
</div>

<!-- Maintenance Stats Cards -->
<div class="maintenance-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #3b82f6; color: white;">
            <i class="fas fa-tools"></i>
        </div>
        <div class="stat-value"><?= $statusCounts['open'] ?></div>
        <div class="stat-label">Open Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #f59e0b; color: white;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value"><?= $statusCounts['in_progress'] ?></div>
        <div class="stat-label">In Progress</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #10b981; color: white;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value"><?= $completedThisMonth ?></div>
        <div class="stat-label">Completed This Month</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #ef4444; color: white;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-value"><?= $highPriorityCount ?></div>
        <div class="stat-label">High Priority</div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" id="filterForm">
        <div class="filter-row">
            <div class="filter-group">
                <label>Search Requests</label>
                <input type="text" name="search" placeholder="Search by title or description..." value="<?= e($_GET['search'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="open" <?= ($_GET['status'] ?? '') === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= ($_GET['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="closed" <?= ($_GET['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Priority</label>
                <select name="priority">
                    <option value="">All Priorities</option>
                    <option value="high" <?= ($_GET['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                    <option value="medium" <?= ($_GET['priority'] ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="low" <?= ($_GET['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Property</label>
                <select name="property_id">
                    <option value="">All Properties</option>
                    <?php foreach ($properties as $property): ?>
                        <option value="<?= (int) $property['id'] ?>" <?= ($_GET['property_id'] ?? '') === (string) $property['id'] ? 'selected' : '' ?>>
                            <?= e($property['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="<?= route('admin.maintenance') ?>" class="btn" style="margin-left: 1rem;">
            Reset
        </a>
    </form>
</div>

<!-- Maintenance Requests Grid -->
<div id="maintenanceRequests" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin: 2rem 0;">
    <?php if (empty($maintenanceRequests)): ?>
        <div style="grid-column: 1/-1; padding: 2rem; text-align: center; color: #999;">
            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p>No maintenance requests found.</p>
        </div>
    <?php else: ?>
        <?php foreach ($maintenanceRequests as $request): ?>
            <?php
            $priorityClass = 'priority-' . $request['priority'];
            $statusClass = match($request['status']) {
                'open' => 'status-open',
                'in_progress' => 'status-in-progress',
                'completed' => 'status-completed',
                'closed' => 'status-closed',
                default => 'status-open'
            };
            $statusText = match($request['status']) {
                'open' => 'Open',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'closed' => 'Closed',
                default => 'Open'
            };
            $priorityText = ucfirst($request['priority']);
            ?>
            <div class="request-card <?= $priorityClass ?>" onclick="viewRequest(<?= (int) $request['id'] ?>)" style="cursor: pointer; padding: 1.5rem; border-left: 4px solid; background: white; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h3 class="request-title" style="margin: 0; color: #2c5aa0; font-size: 1rem;">
                        <?= e($request['title']) ?>
                    </h3>
                    <span class="request-status <?= $statusClass ?>" style="font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 12px; white-space: nowrap;">
                        <?= $statusText ?>
                    </span>
                </div>

                <div style="margin-bottom: 0.75rem;">
                    <div style="font-weight: 600; color: #333;"><?= e($request['property_name'] ?? 'N/A') ?></div>
                    <?php if (!empty($request['first_name'])): ?>
                        <div style="font-size: 0.875rem; color: #666;"><?= e($request['first_name'] . ' ' . $request['last_name']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="request-description" style="font-size: 0.875rem; color: #666; margin-bottom: 1rem; max-height: 3em; overflow: hidden; text-overflow: ellipsis;">
                    <?= e(substr($request['description'], 0, 100)) ?>...
                </div>

                <div class="request-footer" style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #999; border-top: 1px solid #eee; padding-top: 0.75rem;">
                    <div style="display: flex; gap: 1rem;">
                        <span><i class="fas fa-calendar"></i> <?= date('M d', strtotime($request['created_at'])) ?></span>
                        <span><i class="fas fa-tag"></i> <?= e(ucfirst(str_replace('_', ' ', $request['category']))) ?></span>
                        <span style="color: <?= $request['priority'] === 'high' || $request['priority'] === 'emergency' ? '#ef4444' : ($request['priority'] === 'medium' ? '#f59e0b' : '#10b981') ?>;">
                            <i class="fas fa-flag"></i> <?= $priorityText ?>
                        </span>
                    </div>
                    <button class="btn-small btn-icon" onclick="event.stopPropagation(); updateRequestStatus(<?= (int) $request['id'] ?>)" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Create Request Modal -->
<div class="modal-overlay" id="createRequestModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create Maintenance Request</h2>
            <button class="close-modal" onclick="closeModal('createRequestModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="maintenanceForm" method="POST" action="<?= route('admin.maintenance') ?>/store">
                <?= csrf_field() ?>

                <div class="form-section">
                    <h4>Request Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="property_id">Property *</label>
                            <select id="property_id" name="property_id" required>
                                <option value="">Select Property</option>
                                <?php foreach ($properties as $property): ?>
                                    <option value="<?= (int) $property['id'] ?>">
                                        <?= e($property['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="plumbing">Plumbing</option>
                                <option value="electrical">Electrical</option>
                                <option value="appliance">Appliance</option>
                                <option value="heating_cooling">Heating & Cooling</option>
                                <option value="structural">Structural</option>
                                <option value="pest_control">Pest Control</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="priority">Priority *</label>
                            <select id="priority" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="renter_id">Renter</label>
                            <select id="renter_id" name="renter_id">
                                <option value="">Select Renter</option>
                                <?php foreach ($renters as $renter): ?>
                                    <option value="<?= (int) $renter['id'] ?>">
                                        <?= e($renter['first_name'] . ' ' . $renter['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" required placeholder="Brief description of the issue">
                    </div>
                    <div class="form-group">
                        <label for="description">Detailed Description *</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="estimated_cost">Estimated Cost</label>
                        <input type="number" id="estimated_cost" name="estimated_cost" min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Submit Request
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Maintenance Details Modal -->
<div class="modal-overlay" id="maintenanceDetailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Maintenance Request Details</h2>
            <button class="close-modal" onclick="closeModal('maintenanceDetailsModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="details-section" id="detailsContent">
                <!-- Loaded by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Pass maintenance requests data to JavaScript
const maintenanceData = <?= json_encode(array_map(function($r) {
    return [
        'id' => $r['id'],
        'property_id' => $r['property_id'],
        'renter_id' => $r['renter_id'],
        'title' => $r['title'],
        'description' => $r['description'],
        'category' => $r['category'],
        'priority' => $r['priority'],
        'status' => $r['status'],
        'assigned_to' => $r['assigned_to'],
        'estimated_cost' => $r['estimated_cost'],
        'actual_cost' => $r['actual_cost'],
        'property_name' => $r['property_name'],
        'first_name' => $r['first_name'] ?? '',
        'last_name' => $r['last_name'] ?? '',
        'created_at' => $r['created_at'],
        'updated_at' => $r['updated_at']
    ];
}, $maintenanceRequests)) ?>;

let currentRequestId = null;

function createRequest() {
    document.getElementById('createRequestModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function viewRequest(id) {
    currentRequestId = id;
    const request = maintenanceData.find(r => r.id === id);

    if (!request) return;

    const modal = document.getElementById('maintenanceDetailsModal');
    const content = document.getElementById('detailsContent');

    const statusText = {
        'open': 'Open',
        'in_progress': 'In Progress',
        'completed': 'Completed',
        'closed': 'Closed'
    }[request.status] || 'Open';

    const categoryText = request.category.replace(/_/g, ' ').split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');

    const priorityColor = request.priority === 'high' || request.priority === 'emergency' ? '#ef4444' :
                         request.priority === 'medium' ? '#f59e0b' : '#10b981';

    content.innerHTML = `
        <h3>${request.title}</h3>
        <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div class="detail-item">
                <label>Property</label>
                <span>${request.property_name}</span>
            </div>
            <div class="detail-item">
                <label>Category</label>
                <span>${categoryText}</span>
            </div>
            <div class="detail-item">
                <label>Priority</label>
                <span style="color: ${priorityColor}; text-transform: capitalize;">${request.priority}</span>
            </div>
            <div class="detail-item">
                <label>Status</label>
                <span style="text-transform: capitalize;">${statusText}</span>
            </div>
            <div class="detail-item">
                <label>Reported By</label>
                <span>${request.first_name} ${request.last_name}</span>
            </div>
            <div class="detail-item">
                <label>Reported Date</label>
                <span>${new Date(request.created_at).toLocaleDateString()}</span>
            </div>
            <div class="detail-item">
                <label>Assigned To</label>
                <span>${request.assigned_to || 'Not assigned'}</span>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f9f9f9; border-radius: 4px;">
            <h4 style="margin-top: 0;">Description</h4>
            <p>${request.description}</p>
        </div>

        <div class="cost-breakdown" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: #f0f9ff; border-radius: 4px;">
            <div>
                <div style="font-size: 0.875rem; color: #666;">Estimated Cost</div>
                <div style="font-size: 1.25rem; font-weight: 600; color: #2c5aa0;">$${parseFloat(request.estimated_cost || 0).toFixed(2)}</div>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: #666;">Actual Cost</div>
                <div style="font-size: 1.25rem; font-weight: 600; color: #2c5aa0;">$${parseFloat(request.actual_cost || 0).toFixed(2)}</div>
            </div>
        </div>

        <div class="application-actions" style="display: flex; gap: 1rem;">
            <form method="POST" action="" style="display: contents;">
                <?= csrf_field() ?>
                <input type="hidden" name="status" value="in_progress">
                <button type="submit" class="btn btn-success" onclick="this.form.action='<?= route('admin.maintenance') ?>/${currentRequestId}/status'">
                    <i class="fas fa-play"></i> Start Work
                </button>
            </form>
            <form method="POST" action="" style="display: contents;">
                <?= csrf_field() ?>
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn btn-primary" onclick="this.form.action='<?= route('admin.maintenance') ?>/${currentRequestId}/status'">
                    <i class="fas fa-check"></i> Mark Complete
                </button>
            </form>
            <button class="btn btn-secondary" onclick="alert('Assigning vendor...')">
                <i class="fas fa-user-tie"></i> Assign Vendor
            </button>
        </div>
    `;

    modal.classList.add('active');
}

function updateRequestStatus(id) {
    const request = maintenanceData.find(r => r.id === id);
    if (!request) return;

    const newStatus = prompt(`Update status for "${request.title}":\n\nCurrent: ${request.status}\n\nEnter new status (open, in_progress, completed, closed):`, request.status);

    if (newStatus && ['open', 'in_progress', 'completed', 'closed'].includes(newStatus)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= route('admin.maintenance') ?>/${id}/status`;
        form.innerHTML = `
            <?= csrf_field() ?>
            <input type="hidden" name="status" value="${newStatus}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function exportMaintenance() {
    alert('Exporting maintenance data...');
}

// Close modal when clicking outside
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
