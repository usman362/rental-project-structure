<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/CSRF.php';

ob_start();
?>

<div class="content-header">
    <h1>Application Management</h1>
    <div class="content-actions">
        <button class="btn btn-secondary" onclick="exportApplications()">
            <i class="fas fa-download"></i> Export
        </button>
    </div>
</div>

<!-- Quick Stats Bar -->
<div class="quick-stats">
    <div class="quick-stat">
        <div class="quick-stat-number"><?php echo e((string) ($statusCounts['total'] ?? 0)); ?></div>
        <div class="quick-stat-label">Total</div>
    </div>
    <div class="quick-stat">
        <div class="quick-stat-number"><?php echo e((string) ($statusCounts['pending'] ?? 0)); ?></div>
        <div class="quick-stat-label">Pending</div>
    </div>
    <div class="quick-stat">
        <div class="quick-stat-number"><?php echo e((string) ($statusCounts['approved'] ?? 0)); ?></div>
        <div class="quick-stat-label">Approved</div>
    </div>
    <div class="quick-stat">
        <div class="quick-stat-number"><?php echo e((string) ($statusCounts['rejected'] ?? 0)); ?></div>
        <div class="quick-stat-label">Rejected</div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" action="">
        <div class="filter-row">
            <div class="filter-group">
                <label>Search Applications</label>
                <input
                    type="text"
                    id="appSearch"
                    name="search"
                    placeholder="Search by applicant or property..."
                    value="<?php echo e($filters['search'] ?? ''); ?>"
                >
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="appStatusFilter" name="status">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo isset($filters['status']) && $filters['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo isset($filters['status']) && $filters['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="rejected" <?php echo isset($filters['status']) && $filters['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    <option value="withdrawn" <?php echo isset($filters['status']) && $filters['status'] === 'withdrawn' ? 'selected' : ''; ?>>Withdrawn</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Date From</label>
                <input
                    type="date"
                    id="appDateFrom"
                    name="date_from"
                    value="<?php echo e($filters['date_from'] ?? ''); ?>"
                >
            </div>
            <div class="filter-group">
                <label>To</label>
                <input
                    type="date"
                    id="appDateTo"
                    name="date_to"
                    value="<?php echo e($filters['date_to'] ?? ''); ?>"
                >
            </div>
        </div>
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="<?php echo route('admin.applications'); ?>" class="btn" style="margin-left: 1rem;">
            Reset
        </a>
    </form>
</div>

<!-- Applications Grid -->
<div class="applications-grid" id="applicationsGrid">
    <?php
    if (empty($applications)):
    ?>
        <div style="grid-column: 1 / -1; padding: 2rem; text-align: center; color: #666;">
            No applications found.
        </div>
    <?php
    else:
        foreach ($applications as $app):
            $statusClass = match($app['status']) {
                'pending' => 'status-pending',
                'approved' => 'status-approved',
                'rejected' => 'status-rejected',
                default => 'status-pending'
            };

            $statusText = match($app['status']) {
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'withdrawn' => 'Withdrawn',
                default => ucfirst($app['status'])
            };

            $submittedAt = !empty($app['submitted_at']) ?
                date('M d, Y', strtotime($app['submitted_at'])) : 'N/A';
    ?>
        <div class="application-card">
            <div class="application-header">
                <div class="applicant-name">
                    <?php echo e($app['first_name'] . ' ' . $app['last_name']); ?>
                </div>
                <div class="application-date"><?php echo e($submittedAt); ?></div>
            </div>
            <div class="application-property">
                <?php echo e($app['property_name'] ?? 'No Property'); ?>
            </div>
            <div class="application-status <?php echo $statusClass; ?>">
                <?php echo $statusText; ?>
            </div>
            <div class="application-details">
                <div>
                    <div class="detail-label">Income</div>
                    <div class="detail-value">
                        $<?php echo e((string) ($app['monthly_income'] ?? 0)); ?>/month
                    </div>
                </div>
                <div>
                    <div class="detail-label">Credit Score</div>
                    <div class="detail-value">
                        <?php echo e((string) ($app['credit_score'] ?? 'N/A')); ?>
                    </div>
                </div>
                <div>
                    <div class="detail-label">Email</div>
                    <div class="detail-value">
                        <?php echo e($app['email']); ?>
                    </div>
                </div>
            </div>
            <div class="application-actions">
                <button class="btn-small view-btn" onclick="viewApplication(<?php echo $app['id']; ?>)">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="btn-small review-btn" onclick="updateStatus(<?php echo $app['id']; ?>, 'pending')">
                    <i class="fas fa-search"></i> Review
                </button>
            </div>
        </div>
    <?php
        endforeach;
    endif;
    ?>
</div>

<!-- Application Details Modal -->
<div class="modal-overlay application-details-modal" id="appDetailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Application Details</h2>
            <button class="close-modal" onclick="closeModal('appDetailsModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="details-section">
                <h3>Applicant Information</h3>
                <div class="details-grid" id="applicantInfo">
                    <!-- Loaded by JavaScript -->
                </div>
            </div>

            <div class="details-section">
                <h3>Employment & Financial</h3>
                <div class="details-grid" id="employmentInfo">
                    <!-- Loaded by JavaScript -->
                </div>
            </div>

            <div class="details-section">
                <h3>Property Information</h3>
                <div class="details-grid" id="propertyInfo">
                    <!-- Loaded by JavaScript -->
                </div>
            </div>

            <div class="details-section">
                <h3>Application Notes</h3>
                <div class="details-grid" id="notesInfo">
                    <!-- Loaded by JavaScript -->
                </div>
            </div>

            <div class="details-section">
                <h3>Actions</h3>
                <div class="application-actions">
                    <button class="btn btn-success" onclick="approveApplication()">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <form id="changeStatusForm" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" id="statusAppId">
                        <select name="status" id="statusSelect" style="padding: 0.5rem; margin-right: 0.5rem;">
                            <option value="">Change Status...</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="withdrawn">Withdrawn</option>
                        </select>
                        <button type="button" class="btn btn-secondary" onclick="submitStatusChange()">
                            <i class="fas fa-sync"></i> Update Status
                        </button>
                    </form>
                    <button class="btn" style="background: #ef4444; color: white;" onclick="rejectApplication()">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Application Modal -->
<div class="modal-overlay" id="approveApplicationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Approve Application & Create Renter</h2>
            <button class="close-modal" onclick="closeModal('approveApplicationModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="approveForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="approveAppId">

                <div class="details-section">
                    <h3>Applicant Information</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <label>Name</label>
                            <span id="approveApplicantName"></span>
                        </div>
                        <div class="detail-item">
                            <label>Email</label>
                            <span id="approveApplicantEmail"></span>
                        </div>
                        <div class="detail-item">
                            <label>Phone</label>
                            <span id="approveApplicantPhone"></span>
                        </div>
                    </div>
                </div>

                <div class="details-section">
                    <h3>Renter Account Information</h3>
                    <p style="color: #666; margin-bottom: 1rem;">
                        A renter account will be created with these temporary credentials:
                    </p>
                    <div class="details-grid">
                        <div class="detail-item">
                            <label>Temporary Password</label>
                            <span>Welcome@123</span>
                        </div>
                    </div>
                    <p style="color: #999; font-size: 0.9rem; margin-top: 1rem;">
                        The renter will receive login credentials via email and should change their password on first login.
                    </p>
                </div>

                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #eaeaea;">
                    <div style="display: flex; gap: 1rem;">
                        <button type="button" class="btn btn-success" style="flex: 1;" onclick="submitApprovalForm()">
                            <i class="fas fa-check"></i> Approve & Create Renter
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal('approveApplicationModal')" style="flex: 1;">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Applications data
const applicationsData = <?php echo json_encode($applications); ?>;

let currentAppId = null;

// Initialize modals on click outside
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function viewApplication(id) {
    currentAppId = id;
    const app = applicationsData.find(a => a.id === id);

    if (!app) return;

    // Load applicant info
    const applicantInfo = document.getElementById('applicantInfo');
    applicantInfo.innerHTML = `
        <div class="detail-item">
            <label>Full Name</label>
            <span>${escapeHtml(app.first_name + ' ' + app.last_name)}</span>
        </div>
        <div class="detail-item">
            <label>Email</label>
            <span>${escapeHtml(app.email)}</span>
        </div>
        <div class="detail-item">
            <label>Phone</label>
            <span>${escapeHtml(app.phone)}</span>
        </div>
    `;

    // Load employment info
    const employmentInfo = document.getElementById('employmentInfo');
    employmentInfo.innerHTML = `
        <div class="detail-item">
            <label>Employment</label>
            <span>${escapeHtml(app.employment || 'N/A')}</span>
        </div>
        <div class="detail-item">
            <label>Monthly Income</label>
            <span>$${app.monthly_income || 0}</span>
        </div>
        <div class="detail-item">
            <label>Credit Score</label>
            <span>${app.credit_score || 'N/A'}</span>
        </div>
    `;

    // Load property info
    const propertyInfo = document.getElementById('propertyInfo');
    const submittedDate = app.submitted_at ? new Date(app.submitted_at).toLocaleDateString() : 'N/A';
    propertyInfo.innerHTML = `
        <div class="detail-item">
            <label>Property</label>
            <span>${escapeHtml(app.property_name || 'N/A')}</span>
        </div>
        <div class="detail-item">
            <label>Desired Move-in</label>
            <span>${escapeHtml(app.desired_move_in || 'N/A')}</span>
        </div>
        <div class="detail-item">
            <label>Lease Term</label>
            <span>${escapeHtml(app.lease_term || 'N/A')} months</span>
        </div>
        <div class="detail-item">
            <label>Applied Date</label>
            <span>${submittedDate}</span>
        </div>
    `;

    // Load notes info
    const notesInfo = document.getElementById('notesInfo');
    notesInfo.innerHTML = `
        <div class="detail-item" style="grid-column: 1 / -1;">
            <label>Notes</label>
            <span style="white-space: pre-wrap;">${escapeHtml(app.notes || 'No notes')}</span>
        </div>
    `;

    // Show modal
    document.getElementById('appDetailsModal').classList.add('active');
}

function updateStatus(appId, status) {
    currentAppId = appId;
    document.getElementById('statusAppId').value = appId;
    document.getElementById('statusSelect').value = status;
}

function submitStatusChange() {
    const appId = document.getElementById('statusAppId').value;
    const status = document.getElementById('statusSelect').value;

    if (!status) {
        alert('Please select a status');
        return;
    }

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/applications/' + appId + '/status';

    form.innerHTML = `
        ${document.querySelector('input[name="_token"]').outerHTML}
        <input type="hidden" name="status" value="${escapeHtml(status)}">
    `;

    document.body.appendChild(form);
    form.submit();
}

function approveApplication() {
    const app = applicationsData.find(a => a.id === currentAppId);

    if (!app) return;

    // Load approval form
    document.getElementById('approveAppId').value = currentAppId;
    document.getElementById('approveApplicantName').textContent =
        escapeHtml(app.first_name + ' ' + app.last_name);
    document.getElementById('approveApplicantEmail').textContent =
        escapeHtml(app.email);
    document.getElementById('approveApplicantPhone').textContent =
        escapeHtml(app.phone);

    // Update form action
    document.getElementById('approveForm').action =
        '/admin/applications/' + currentAppId + '/approve';

    // Close details modal and open approval modal
    closeModal('appDetailsModal');
    document.getElementById('approveApplicationModal').classList.add('active');
}

function submitApprovalForm() {
    const appId = document.getElementById('approveAppId').value;
    const form = document.getElementById('approveForm');
    form.action = '/admin/applications/' + appId + '/approve';
    form.submit();
}

function rejectApplication() {
    if (currentAppId) {
        const reason = prompt('Please enter reason for rejection:');
        if (reason) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/applications/' + currentAppId + '/status';

            form.innerHTML = `
                ${document.querySelector('input[name="_token"]').outerHTML}
                <input type="hidden" name="status" value="rejected">
            `;

            document.body.appendChild(form);
            form.submit();
        }
    }
}

function exportApplications() {
    alert('Exporting applications data...');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
