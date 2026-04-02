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
        <button class="btn btn-success" onclick="scheduleInspection()">
            <i class="fas fa-calendar-check"></i> Schedule
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

<!-- Maintenance Requests List -->
<div id="maintenanceRequests" style="display: flex; flex-direction: column; gap: 1rem; margin: 2rem 0;">
    <?php if (empty($maintenanceRequests)): ?>
        <div style="padding: 2rem; text-align: center; color: #999; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p>No maintenance requests found.</p>
        </div>
    <?php else: ?>
        <?php foreach ($maintenanceRequests as $request): ?>
            <?php
            $statusText = match($request['status']) {
                'open' => 'Open',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'closed' => 'Closed',
                default => 'Open'
            };
            $statusBg = match($request['status']) {
                'open' => 'background:#fef3c7;color:#92400e;',
                'in_progress' => 'background:#dbeafe;color:#1e40af;',
                'completed' => 'background:#d1fae5;color:#065f46;',
                'closed' => 'background:#e5e7eb;color:#374151;',
                default => 'background:#fef3c7;color:#92400e;'
            };
            $borderColor = match($request['priority']) {
                'high', 'emergency' => '#ef4444',
                'medium' => '#f59e0b',
                'low' => '#10b981',
                default => '#3b82f6'
            };
            if ($request['status'] === 'in_progress') $borderColor = '#3b82f6';
            if ($request['status'] === 'completed') $borderColor = '#ef4444';
            if ($request['status'] === 'closed') $borderColor = '#10b981';

            $categoryText = ucfirst(str_replace('_', ' ', $request['category']));
            $renterName = trim(($request['first_name'] ?? '') . ' ' . ($request['last_name'] ?? ''));
            ?>
            <div class="request-card" onclick="viewRequest(<?= (int) $request['id'] ?>)"
                 style="cursor:pointer; padding:1.5rem; border-left:4px solid <?= $borderColor ?>; background:white; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); transition:box-shadow 0.2s;"
                 onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.06)'">

                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.75rem;">
                    <h3 style="margin:0; font-size:1.1rem; font-weight:600; color:#1a1a1a;"><?= e($request['title']) ?></h3>
                    <span style="font-size:0.75rem; padding:0.25rem 0.75rem; border-radius:12px; white-space:nowrap; font-weight:600; <?= $statusBg ?>">
                        <?= $statusText ?>
                    </span>
                </div>

                <div style="color:#2c5aa0; font-size:0.9rem; margin-bottom:0.75rem;">
                    <?= e($request['property_name'] ?? 'N/A') ?>
                </div>

                <div style="font-size:0.875rem; color:#555; margin-bottom:1rem; line-height:1.5;">
                    <?= e($request['description']) ?>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f0f0f0; padding-top:0.75rem;">
                    <div style="display:flex; gap:1.25rem; font-size:0.8rem; color:#888;">
                        <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($request['created_at'])) ?></span>
                        <?php if ($renterName): ?>
                            <span><i class="fas fa-user"></i> <?= e($renterName) ?></span>
                        <?php endif; ?>
                        <span><i class="fas fa-tag"></i> <?= e($categoryText) ?></span>
                    </div>
                    <button class="btn-small btn-icon" onclick="event.stopPropagation(); updateRequestStatus(<?= (int) $request['id'] ?>)" title="Edit" style="border:1px solid #ddd; border-radius:6px; padding:6px 8px; background:white; cursor:pointer;">
                        <i class="fas fa-edit" style="color:#666;"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Upcoming Maintenance Section -->
<div style="background:white; border-radius:10px; padding:1.5rem; margin-bottom:2rem; box-shadow:0 2px 10px rgba(0,0,0,0.05); border:1px solid #eaeaea;">
    <h3 style="margin:0 0 1.25rem 0; color:#2c5aa0; font-size:1.15rem;">
        <i class="fas fa-calendar-alt" style="margin-right:0.5rem;"></i>Upcoming Maintenance
    </h3>
    <div id="upcomingMaintenance">
        <?php
        // Filter upcoming scheduled items (if any have assigned_to and status is open/in_progress)
        $upcoming = array_filter($maintenanceRequests, function($r) {
            return in_array($r['status'], ['open', 'in_progress']) && !empty($r['assigned_to']);
        });
        ?>
        <?php if (!empty($upcoming)): ?>
            <?php foreach ($upcoming as $item): ?>
                <div style="border-bottom:1px solid #f0f0f0; padding:1rem 0;">
                    <div style="color:#2c5aa0; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem;">
                        <?= date('M d, Y', strtotime($item['created_at'])) ?>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding-left:1rem;">
                        <div>
                            <div style="font-weight:600; color:#333;"><?= e($item['title']) ?></div>
                            <div style="font-size:0.85rem; color:#666;"><?= e($item['property_name'] ?? '') ?></div>
                        </div>
                        <div style="font-size:0.85rem; color:#666;"><?= e($item['assigned_to']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align:center; padding:2rem 0; color:#999;">
                <i class="fas fa-calendar-check" style="font-size:1.5rem; margin-bottom:0.5rem; display:block;"></i>
                <p style="margin:0;">No upcoming scheduled maintenance.</p>
                <p style="margin:0.25rem 0 0; font-size:0.85rem;">Click "Schedule" to add an inspection.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Vendor Directory Section -->
<div style="margin-bottom:2rem;">
    <h3 style="margin:0 0 1.25rem 0; color:#333; font-size:1.15rem;">
        <i class="fas fa-address-book" style="margin-right:0.5rem; color:#2c5aa0;"></i>Vendor Directory
    </h3>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:1.25rem;">
        <?php
        $vendors = [
            ['initials' => 'PL', 'name' => 'Premium Plumbing', 'specialty' => 'Plumbing & Water Heater', 'phone' => '(555) 123-4567', 'email' => 'contact@premiumplumbing.com', 'rating' => 4.5],
            ['initials' => 'EC', 'name' => 'Electrical Experts', 'specialty' => 'Electrical & Lighting', 'phone' => '(555) 987-6543', 'email' => 'service@electricalexperts.com', 'rating' => 5],
            ['initials' => 'HC', 'name' => 'HVAC Care', 'specialty' => 'Heating & Cooling', 'phone' => '(555) 456-7890', 'email' => 'support@hvaccare.com', 'rating' => 4],
        ];
        foreach ($vendors as $vendor):
            $stars = '';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= floor($vendor['rating'])) {
                    $stars .= '<i class="fas fa-star" style="color:#f59e0b;"></i>';
                } elseif ($i - $vendor['rating'] <= 0.5) {
                    $stars .= '<i class="fas fa-star-half-alt" style="color:#f59e0b;"></i>';
                } else {
                    $stars .= '<i class="far fa-star" style="color:#f59e0b;"></i>';
                }
            }
        ?>
        <div style="background:white; border-radius:10px; padding:1.5rem; box-shadow:0 2px 10px rgba(0,0,0,0.05); border:1px solid #eaeaea;">
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
                <div style="width:45px; height:45px; border-radius:50%; background:#2c5aa0; color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.85rem;">
                    <?= $vendor['initials'] ?>
                </div>
                <div>
                    <div style="font-weight:600; color:#333;"><?= $vendor['name'] ?></div>
                    <div style="font-size:0.8rem; color:#888;"><?= $vendor['specialty'] ?></div>
                </div>
            </div>
            <div style="font-size:0.85rem; color:#555; margin-bottom:0.25rem;">
                <i class="fas fa-phone" style="width:18px; color:#2c5aa0;"></i> <?= $vendor['phone'] ?>
            </div>
            <div style="font-size:0.85rem; color:#555; margin-bottom:0.75rem;">
                <i class="fas fa-envelope" style="width:18px; color:#2c5aa0;"></i> <?= $vendor['email'] ?>
            </div>
            <div style="margin-bottom:1rem;">
                <?= $stars ?> <span style="font-size:0.8rem; color:#888;"><?= $vendor['rating'] ?>/5</span>
            </div>
            <button class="btn btn-secondary" style="font-size:0.8rem; padding:0.4rem 1rem;" onclick="Swal.fire({title:'Contact <?= $vendor['name'] ?>', html:'<p>Phone: <?= $vendor['phone'] ?></p><p>Email: <?= $vendor['email'] ?></p>', icon:'info', confirmButtonColor:'#2c5aa0'})">
                <i class="fas fa-phone"></i> Contact
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Create Request Modal -->
<div class="modal-overlay" id="createRequestModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create Maintenance Request</h2>
            <button class="close-modal" onclick="closeModal('createRequestModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="maintenanceForm" method="POST" action="<?= route('admin.maintenance') ?>">
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

        <div class="application-actions" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button class="btn btn-success" onclick="submitStatusChange('in_progress')">
                <i class="fas fa-play"></i> Start Work
            </button>
            <button class="btn btn-primary" onclick="submitStatusChange('completed')">
                <i class="fas fa-check"></i> Mark Complete
            </button>
            <button class="btn btn-secondary" onclick="assignVendor(currentRequestId)">
                <i class="fas fa-user-tie"></i> Assign Vendor
            </button>
            <button class="btn" style="background:#ef4444;color:white;" onclick="submitStatusChange('closed')">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    `;

    modal.classList.add('active');
}

function updateRequestStatus(id) {
    const request = maintenanceData.find(r => r.id === id);
    if (!request) return;

    Swal.fire({
        title: 'Update Status',
        html: `<p>Update status for <strong>${request.title}</strong></p><p style="color:#666;font-size:14px;">Current: ${request.status}</p>`,
        input: 'select',
        inputOptions: { 'open': 'Open', 'in_progress': 'In Progress', 'completed': 'Completed', 'closed': 'Closed' },
        inputValue: request.status,
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Update Status'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= route('admin.maintenance') ?>/${id}/status`;
            form.innerHTML = `
                <?= csrf_field() ?>
                <input type="hidden" name="status" value="${result.value}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function submitStatusChange(newStatus) {
    if (!currentRequestId) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= route("admin.maintenance") ?>/' + currentRequestId + '/status';
    form.innerHTML = `<?= csrf_field() ?><input type="hidden" name="status" value="${newStatus}">`;
    document.body.appendChild(form);
    form.submit();
}

function assignVendor(requestId) {
    const id = requestId || currentRequestId;
    if (!id) return;

    Swal.fire({
        title: 'Assign Vendor',
        html: `
            <div style="text-align:left;margin-top:0.5rem;">
                <label style="display:block;margin-bottom:0.5rem;font-weight:500;">Vendor / Company Name *</label>
                <input type="text" id="swal-vendor" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px;font-size:14px;margin-bottom:1rem;" placeholder="Enter vendor name or company...">
                <label style="display:block;margin-bottom:0.5rem;font-weight:500;">Contact Phone</label>
                <input type="tel" id="swal-vendor-phone" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px;font-size:14px;margin-bottom:1rem;" placeholder="Phone number...">
                <label style="display:block;margin-bottom:0.5rem;font-weight:500;">Scheduled Visit Date</label>
                <input type="date" id="swal-vendor-date" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px;font-size:14px;">
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-user-tie"></i> Assign',
        width: 450,
        preConfirm: () => {
            const vendor = document.getElementById('swal-vendor').value;
            if (!vendor) {
                Swal.showValidationMessage('Please enter a vendor name');
                return false;
            }
            return {
                vendor: vendor,
                phone: document.getElementById('swal-vendor-phone').value,
                date: document.getElementById('swal-vendor-date').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= route("admin.maintenance") ?>/' + id + '/assign';
            form.innerHTML = `
                <?= csrf_field() ?>
                <input type="hidden" name="assigned_to" value="${result.value.vendor}">
                <input type="hidden" name="vendor_phone" value="${result.value.phone}">
                <input type="hidden" name="visit_date" value="${result.value.date}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function exportMaintenance() {
    if (!maintenanceData || maintenanceData.length === 0) {
        Swal.fire({ title: 'No Data', text: 'No maintenance requests to export.', icon: 'warning', confirmButtonColor: '#2c5aa0' });
        return;
    }

    const headers = ['ID', 'Title', 'Property', 'Reported By', 'Category', 'Priority', 'Status', 'Assigned To', 'Estimated Cost', 'Actual Cost', 'Created Date', 'Description'];

    const rows = maintenanceData.map(r => [
        r.id,
        r.title || '',
        r.property_name || 'N/A',
        ((r.first_name || '') + ' ' + (r.last_name || '')).trim() || 'N/A',
        (r.category || '').replace(/_/g, ' '),
        (r.priority || '').charAt(0).toUpperCase() + (r.priority || '').slice(1),
        (r.status || '').replace(/_/g, ' ').charAt(0).toUpperCase() + (r.status || '').replace(/_/g, ' ').slice(1),
        r.assigned_to || 'Unassigned',
        parseFloat(r.estimated_cost || 0).toFixed(2),
        parseFloat(r.actual_cost || 0).toFixed(2),
        r.created_at ? new Date(r.created_at).toLocaleDateString() : '',
        r.description || ''
    ]);

    const csvContent = [headers, ...rows].map(row =>
        row.map(field => {
            const str = String(field);
            if (str.includes(',') || str.includes('"') || str.includes('\n')) {
                return '"' + str.replace(/"/g, '""') + '"';
            }
            return str;
        }).join(',')
    ).join('\n');

    const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'maintenance_export_' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    Swal.fire({
        title: 'Export Complete!',
        text: 'Maintenance data has been downloaded as CSV.',
        icon: 'success',
        confirmButtonColor: '#2c5aa0',
        timer: 3000,
        timerProgressBar: true
    });
}

const propertiesList = <?= json_encode(array_map(function($p) {
    return ['id' => $p['id'], 'name' => $p['name']];
}, $properties)) ?>;

function scheduleInspection() {
    const propertyOptions = propertiesList.map(p =>
        `<option value="${p.id}">${p.name}</option>`
    ).join('');

    Swal.fire({
        title: 'Schedule Inspection',
        html: `
            <div style="text-align:left;margin-top:1rem;">
                <label style="display:block;margin-bottom:0.5rem;font-weight:500;">Property *</label>
                <select id="swal-property" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px;margin-bottom:1rem;font-size:14px;">
                    <option value="">Select Property</option>
                    ${propertyOptions}
                </select>
                <label style="display:block;margin-bottom:0.5rem;font-weight:500;">Inspection Date *</label>
                <input type="date" id="swal-date" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px;margin-bottom:1rem;font-size:14px;">
                <label style="display:block;margin-bottom:0.5rem;font-weight:500;">Time Slot *</label>
                <select id="swal-time" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px;margin-bottom:1rem;font-size:14px;">
                    <option value="">Select Time</option>
                    <option value="09:00">9:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                </select>
                <label style="display:block;margin-bottom:0.5rem;font-weight:500;">Notes</label>
                <textarea id="swal-notes" rows="2" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:inherit;" placeholder="Any special instructions..."></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-calendar-check"></i> Schedule',
        width: 480,
        preConfirm: () => {
            const propertyId = document.getElementById('swal-property').value;
            const propertyName = document.getElementById('swal-property').selectedOptions[0]?.text || '';
            const date = document.getElementById('swal-date').value;
            const time = document.getElementById('swal-time').value;
            const notes = document.getElementById('swal-notes').value;
            if (!propertyId || !date || !time) {
                Swal.showValidationMessage('Please fill in property, date and time');
                return false;
            }
            return { propertyId, propertyName, date, time, notes };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const data = result.value;
            // POST to backend
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= route("admin.maintenance") ?>/schedule';
            form.innerHTML = `
                <?= csrf_field() ?>
                <input type="hidden" name="property_id" value="${data.propertyId}">
                <input type="hidden" name="scheduled_date" value="${data.date}">
                <input type="hidden" name="scheduled_time" value="${data.time}">
                <input type="hidden" name="notes" value="${data.notes}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
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
