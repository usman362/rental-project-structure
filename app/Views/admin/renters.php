<?php
$title = 'Renter Management';
$active = 'renters';
ob_start();
?>

<div class="content-header">
    <h1>Renter Management</h1>
    <div class="content-actions">
        <button class="btn btn-secondary" onclick="exportRenters()">
            <i class="fas fa-download"></i> Export
        </button>
        <button class="btn btn-primary" onclick="openAddRenterModal()">
            <i class="fas fa-user-plus"></i> Add Renter
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section" style="background: white; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
    <form method="GET" action="<?= route('admin.renters') ?>">
        <div class="filter-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="filter-group">
                <label for="searchInput" style="display: block; margin-bottom: 0.5rem; color: #666; font-weight: 500;">Search Renters</label>
                <input type="text" id="searchInput" name="q" placeholder="Search by name, email, or phone..." value="<?= e($_GET['q'] ?? '') ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div class="filter-group">
                <label for="statusFilter" style="display: block; margin-bottom: 0.5rem; color: #666; font-weight: 500;">Status</label>
                <select id="statusFilter" name="status" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="propertyFilter" style="display: block; margin-bottom: 0.5rem; color: #666; font-weight: 500;">Property</label>
                <select id="propertyFilter" name="property_id" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Properties</option>
                    <?php foreach ($properties as $property): ?>
                        <option value="<?= e((string)$property['id']) ?>" <?= ($_GET['property_id'] ?? '') == $property['id'] ? 'selected' : '' ?>>
                            <?= e($property['name'] ?? $property['address']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
            <a href="<?= route('admin.renters') ?>" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Renters Table -->
<div class="table-container" style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
    <div class="table-title" style="padding: 1.5rem; border-bottom: 1px solid #eaeaea; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 16px; font-weight: 600;">All Renters (<?= count($renters) ?>)</span>
        <div style="font-size: 14px; color: #666;">
            Showing 1-<?= count($renters) ?> of <?= count($renters) ?>
        </div>
    </div>

    <?php if (!empty($renters)): ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #eaeaea;">
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Name</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Contact Info</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Property</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Move-in Date</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Monthly Rent</th>
                    <th style="padding: 1rem; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Status</th>
                    <th style="padding: 1rem; text-align: center; color: #666; font-weight: 600; font-size: 14px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($renters as $renter): ?>
                    <tr style="border-bottom: 1px solid #eaeaea; hover: background: #f9fafb;">
                        <td style="padding: 1rem;">
                            <strong><?= e($renter['first_name'] ?? '') ?> <?= e($renter['last_name'] ?? '') ?></strong><br>
                            <small style="color: #666;">ID: R<?= str_pad((string)$renter['id'], 4, '0', STR_PAD_LEFT) ?></small>
                        </td>
                        <td style="padding: 1rem;">
                            <?= e($renter['email'] ?? '') ?><br>
                            <?= e($renter['phone'] ?? '') ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?= e($renter['property_name'] ?? 'N/A') ?>
                        </td>
                        <td style="padding: 1rem; color: #666;">
                            <?php
                                if (isset($renter['move_in_date'])) {
                                    $date = new DateTime($renter['move_in_date']);
                                    echo e($date->format('M d, Y'));
                                }
                            ?>
                        </td>
                        <td style="padding: 1rem;">
                            <strong>$<?= number_format($renter['monthly_rent'] ?? 0, 2) ?></strong>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 12px; font-weight: 600;
                                <?php
                                    $statusClass = ($renter['status'] ?? 'active') === 'active' ? 'background: #d1fae5; color: #065f46;' : 'background: #fee2e2; color: #7f1d1d;';
                                    echo $statusClass;
                                ?>">
                                <?= e(ucfirst($renter['status'] ?? 'active')) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <button class="btn-small btn-icon" title="View" onclick="viewRenter(<?= (int)$renter['id'] ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-small btn-icon" title="Edit" onclick="editRenter(<?= (int)$renter['id'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-small btn-icon" title="Message" onclick="sendMessage(<?= (int)$renter['id'] ?>)">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="btn-small btn-icon" title="Record Payment" onclick="recordPayment(<?= (int)$renter['id'] ?>)">
                                    <i class="fas fa-credit-card"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="padding: 3rem; text-align: center; color: #666;">
            <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem; display: block;"></i>
            <p>No renters found.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Add Renter Modal -->
<div class="modal-overlay" id="addRenterModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; border-radius: 10px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div class="modal-header" style="padding: 1.5rem; border-bottom: 1px solid #eaeaea; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 1.5rem;">Add New Renter</h2>
            <button class="close-modal" onclick="closeModal('addRenterModal')" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: #666;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 1.5rem;">
            <form id="addRenterForm" method="POST" action="<?= route('admin.renters') ?>" style="display: flex; flex-direction: column; gap: 1rem;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="create">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="firstName" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">First Name *</label>
                        <input type="text" id="firstName" name="first_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label for="lastName" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Last Name *</label>
                        <input type="text" id="lastName" name="last_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="email" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Email *</label>
                        <input type="email" id="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label for="phone" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Phone *</label>
                        <input type="tel" id="phone" name="phone" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="property" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Assigned Property *</label>
                    <select id="property" name="property_id" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Property</option>
                        <?php foreach ($properties as $property): ?>
                            <option value="<?= e((string)$property['id']) ?>">
                                <?= e($property['name'] ?? $property['address']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="moveInDate" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Move-in Date *</label>
                        <input type="date" id="moveInDate" name="move_in_date" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label for="leaseEnd" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Lease End Date</label>
                        <input type="date" id="leaseEnd" name="lease_end_date" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="rentAmount" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Monthly Rent *</label>
                        <input type="number" id="rentAmount" name="monthly_rent" required min="0" step="0.01" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label for="securityDeposit" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Security Deposit</label>
                        <input type="number" id="securityDeposit" name="security_deposit" min="0" step="0.01" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Notes</label>
                    <textarea id="notes" name="notes" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; font-family: inherit;"></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
                    <i class="fas fa-save"></i> Save Renter
                </button>
            </form>
        </div>
    </div>
</div>

<!-- View Renter Modal -->
<div class="modal-overlay" id="viewRenterModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; border-radius: 10px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div class="modal-header" style="padding: 1.5rem; border-bottom: 1px solid #eaeaea; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 1.5rem;">Renter Details</h2>
            <button class="close-modal" onclick="closeModal('viewRenterModal')" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: #666;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 1.5rem;">
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <!-- Name & ID -->
                <div style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f0f0f0;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #2c5aa0; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 600;">
                        <span id="viewAvatar"></span>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.2rem;" id="viewFullName"></h3>
                        <span style="color: #666; font-size: 13px;" id="viewRenterId"></span>
                    </div>
                    <span id="viewStatusBadge" style="margin-left: auto; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 12px; font-weight: 600;"></span>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 style="margin: 0 0 0.75rem 0; color: #333; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Contact Information</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div>
                            <span style="display: block; color: #999; font-size: 12px; margin-bottom: 0.25rem;">Email</span>
                            <span style="color: #333; font-size: 14px;" id="viewEmail"></span>
                        </div>
                        <div>
                            <span style="display: block; color: #999; font-size: 12px; margin-bottom: 0.25rem;">Phone</span>
                            <span style="color: #333; font-size: 14px;" id="viewPhone"></span>
                        </div>
                    </div>
                </div>

                <!-- Property & Lease -->
                <div style="border-top: 1px solid #f0f0f0; padding-top: 1rem;">
                    <h4 style="margin: 0 0 0.75rem 0; color: #333; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Property & Lease</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div>
                            <span style="display: block; color: #999; font-size: 12px; margin-bottom: 0.25rem;">Property</span>
                            <span style="color: #333; font-size: 14px;" id="viewProperty"></span>
                        </div>
                        <div>
                            <span style="display: block; color: #999; font-size: 12px; margin-bottom: 0.25rem;">Monthly Rent</span>
                            <span style="color: #333; font-size: 14px; font-weight: 600;" id="viewRent"></span>
                        </div>
                        <div>
                            <span style="display: block; color: #999; font-size: 12px; margin-bottom: 0.25rem;">Move-in Date</span>
                            <span style="color: #333; font-size: 14px;" id="viewMoveIn"></span>
                        </div>
                        <div>
                            <span style="display: block; color: #999; font-size: 12px; margin-bottom: 0.25rem;">Lease End Date</span>
                            <span style="color: #333; font-size: 14px;" id="viewLeaseEnd"></span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="border-top: 1px solid #f0f0f0; padding-top: 1rem; display: flex; gap: 0.75rem;">
                    <button class="btn btn-primary" style="flex: 1;" onclick="closeModal('viewRenterModal'); editRenter(currentViewRenterId);">
                        <i class="fas fa-edit"></i> Edit Renter
                    </button>
                    <button class="btn btn-secondary" style="flex: 1;" onclick="closeModal('viewRenterModal');">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Renter Modal -->
<div class="modal-overlay" id="editRenterModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; border-radius: 10px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div class="modal-header" style="padding: 1.5rem; border-bottom: 1px solid #eaeaea; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 1.5rem;">Edit Renter</h2>
            <button class="close-modal" onclick="closeModal('editRenterModal')" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: #666;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 1.5rem;">
            <form id="editRenterForm" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                <?= csrf_field() ?>
                <input type="hidden" id="editRenterId" name="renter_id">
                <input type="hidden" name="action" value="update">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="editFirstName" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">First Name *</label>
                        <input type="text" id="editFirstName" name="first_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label for="editLastName" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Last Name *</label>
                        <input type="text" id="editLastName" name="last_name" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="editEmail" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Email *</label>
                        <input type="email" id="editEmail" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label for="editPhone" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Phone *</label>
                        <input type="tel" id="editPhone" name="phone" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="editProperty" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Assigned Property *</label>
                    <select id="editProperty" name="property_id" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Property</option>
                        <?php foreach ($properties as $property): ?>
                            <option value="<?= e((string)$property['id']) ?>">
                                <?= e($property['name'] ?? $property['address']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="editMoveInDate" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Move-in Date *</label>
                        <input type="date" id="editMoveInDate" name="move_in_date" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label for="editLeaseEnd" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Lease End Date</label>
                        <input type="date" id="editLeaseEnd" name="lease_end_date" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="editRentAmount" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Monthly Rent *</label>
                        <input type="number" id="editRentAmount" name="monthly_rent" required min="0" step="0.01" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label for="editStatus" style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">Status *</label>
                        <select id="editStatus" name="status" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> Update Renter
                    </button>
                    <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="deleteRenter()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Renters data passed from PHP
const rentersData = <?= json_encode($renters ?? []) ?>;

function openAddRenterModal() {
    document.getElementById('addRenterModal').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function editRenter(id) {
    const renter = rentersData.find(r => r.id === id);
    if (!renter) {
        Swal.fire({ title: 'Error', text: 'Renter not found', icon: 'error', confirmButtonColor: '#2c5aa0' });
        return;
    }

    // Fill form fields
    document.getElementById('editRenterId').value = renter.id;
    document.getElementById('editFirstName').value = renter.first_name || '';
    document.getElementById('editLastName').value = renter.last_name || '';
    document.getElementById('editEmail').value = renter.email || '';
    document.getElementById('editPhone').value = renter.phone || '';
    document.getElementById('editProperty').value = renter.property_id || '';
    document.getElementById('editMoveInDate').value = renter.move_in_date || '';
    document.getElementById('editLeaseEnd').value = renter.lease_end_date || '';
    document.getElementById('editRentAmount').value = renter.monthly_rent || 0;
    document.getElementById('editStatus').value = renter.status || 'active';

    // Update form action
    document.getElementById('editRenterForm').action = '<?= route("admin.renters") ?>/' + id + '/update';

    openEditRenterModal();
}

function openEditRenterModal() {
    document.getElementById('editRenterModal').style.display = 'flex';
}

function deleteRenter() {
    const renterId = document.getElementById('editRenterId').value;
    Swal.fire({
        title: 'Delete Renter?',
        html: '<p>This action <strong>cannot be undone</strong>. All renter data will be permanently removed.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= route("admin.renters") ?>/' + renterId + '/delete';
            form.innerHTML = '<?= csrf_field() ?>';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

let currentViewRenterId = null;

function viewRenter(id) {
    const renter = rentersData.find(r => r.id === id);
    if (!renter) return;

    currentViewRenterId = id;

    // Avatar initials
    const initials = ((renter.first_name || '').charAt(0) + (renter.last_name || '').charAt(0)).toUpperCase();
    document.getElementById('viewAvatar').textContent = initials;

    // Name & ID
    document.getElementById('viewFullName').textContent = (renter.first_name || '') + ' ' + (renter.last_name || '');
    document.getElementById('viewRenterId').textContent = 'ID: R' + String(renter.id).padStart(4, '0');

    // Status badge
    const statusBadge = document.getElementById('viewStatusBadge');
    const isActive = (renter.status || 'active') === 'active';
    statusBadge.textContent = isActive ? 'Active' : 'Inactive';
    statusBadge.style.background = isActive ? '#d1fae5' : '#fee2e2';
    statusBadge.style.color = isActive ? '#065f46' : '#7f1d1d';

    // Contact
    document.getElementById('viewEmail').textContent = renter.email || 'N/A';
    document.getElementById('viewPhone').textContent = renter.phone || 'N/A';

    // Property & Lease
    document.getElementById('viewProperty').textContent = renter.property_name || 'N/A';
    document.getElementById('viewRent').textContent = '$' + parseFloat(renter.monthly_rent || 0).toFixed(2);

    if (renter.move_in_date) {
        const mid = new Date(renter.move_in_date + 'T00:00:00');
        document.getElementById('viewMoveIn').textContent = mid.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    } else {
        document.getElementById('viewMoveIn').textContent = 'N/A';
    }

    if (renter.lease_end_date) {
        const led = new Date(renter.lease_end_date + 'T00:00:00');
        document.getElementById('viewLeaseEnd').textContent = led.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    } else {
        document.getElementById('viewLeaseEnd').textContent = 'N/A';
    }

    document.getElementById('viewRenterModal').style.display = 'flex';
}

function sendMessage(id) {
    const renter = rentersData.find(r => r.id === id);
    if (renter) {
        Swal.fire({
            title: 'Send Message',
            html: `<p>Send message to <strong>${renter.first_name} ${renter.last_name}</strong></p>`,
            input: 'textarea',
            inputPlaceholder: 'Type your message here...',
            showCancelButton: true,
            confirmButtonColor: '#2c5aa0',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane"></i> Send',
            inputValidator: (value) => {
                if (!value) return 'Please enter a message';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Message Sent!',
                    html: `Message has been sent to <strong>${renter.email}</strong>`,
                    icon: 'success',
                    confirmButtonColor: '#2c5aa0',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    }
}

function recordPayment(id) {
    const renter = rentersData.find(r => r.id === id);
    if (renter) {
        Swal.fire({
            title: 'Record Payment',
            html: `<p>Record payment for <strong>${renter.first_name} ${renter.last_name}</strong></p>
                   <p style="color: #666; font-size: 14px;">Monthly rent: $${renter.monthly_rent}</p>`,
            input: 'number',
            inputValue: renter.monthly_rent,
            inputAttributes: { min: 0, step: '0.01' },
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Record Payment',
            inputValidator: (value) => {
                if (!value || value <= 0) return 'Please enter a valid amount';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Payment Recorded!',
                    html: `Payment of <strong>$${parseFloat(result.value).toFixed(2)}</strong> recorded for ${renter.first_name} ${renter.last_name}`,
                    icon: 'success',
                    confirmButtonColor: '#2c5aa0',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    }
}

function exportRenters() {
    if (!rentersData || rentersData.length === 0) {
        Swal.fire({ title: 'No Data', text: 'No renters to export.', icon: 'warning', confirmButtonColor: '#2c5aa0' });
        return;
    }

    // CSV headers
    const headers = ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Property', 'Move-in Date', 'Lease End Date', 'Monthly Rent', 'Status'];

    // CSV rows
    const rows = rentersData.map(r => [
        'R' + String(r.id).padStart(4, '0'),
        r.first_name || '',
        r.last_name || '',
        r.email || '',
        r.phone || '',
        r.property_name || 'N/A',
        r.move_in_date || '',
        r.lease_end_date || '',
        parseFloat(r.monthly_rent || 0).toFixed(2),
        (r.status || 'active').charAt(0).toUpperCase() + (r.status || 'active').slice(1)
    ]);

    // Build CSV string with proper escaping
    const csvContent = [headers, ...rows].map(row =>
        row.map(field => {
            const str = String(field);
            if (str.includes(',') || str.includes('"') || str.includes('\n')) {
                return '"' + str.replace(/"/g, '""') + '"';
            }
            return str;
        }).join(',')
    ).join('\n');

    // BOM for Excel UTF-8 compatibility + download
    const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'renters_export_' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    Swal.fire({
        title: 'Export Complete!',
        text: 'Renter data has been downloaded as CSV.',
        icon: 'success',
        confirmButtonColor: '#2c5aa0',
        timer: 3000,
        timerProgressBar: true
    });
}

// Close modal when clicking outside
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

// Handle form submission
document.getElementById('editRenterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    this.submit();
});
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
