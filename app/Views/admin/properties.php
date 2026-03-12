<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/Core/CSRF.php';

ob_start();
?>

<div class="content-header">
    <h1>Property Management</h1>
    <div class="content-actions">
        <button class="btn btn-secondary" onclick="exportProperties()">
            <i class="fas fa-download"></i> Export
        </button>
        <button class="btn btn-primary" onclick="openAddPropertyModal()">
            <i class="fas fa-plus"></i> Add Property
        </button>
    </div>
</div>

<!-- View Toggle Buttons -->
<div class="view-toggles" style="display: flex; gap: 0.5rem; margin-bottom: 2rem;">
    <button class="btn btn-primary view-toggle active" data-view="grid" onclick="switchView('grid')">
        <i class="fas fa-th"></i> Grid View
    </button>
    <button class="btn view-toggle" data-view="map" onclick="switchView('map')">
        <i class="fas fa-map"></i> Map View
    </button>
    <button class="btn view-toggle" data-view="list" onclick="switchView('list')">
        <i class="fas fa-list"></i> List View
    </button>
</div>

<!-- Map View (hidden by default) -->
<div class="map-view-section" id="mapView" style="display: none; background: white; border-radius: 10px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eaeaea;">
    <h3>Property Locations</h3>
    <div style="background: linear-gradient(135deg, #e8f0fe, #f0f0f0); height: 300px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #666;">
        <div style="text-align: center;">
            <i class="fas fa-map-marker-alt" style="font-size: 2.5rem; color: #2c5aa0; margin-bottom: 1rem;"></i>
            <p style="margin: 0;">Interactive Map</p>
            <small>Property locations would be displayed here</small>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" action="">
        <div class="filter-row">
            <div class="filter-group">
                <label>Search Properties</label>
                <input
                    type="text"
                    id="propSearch"
                    name="search"
                    placeholder="Search by address or name..."
                    value="<?php echo e($filters['search'] ?? ''); ?>"
                >
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select id="propStatusFilter" name="status">
                    <option value="">All Status</option>
                    <option value="available" <?php echo isset($filters['status']) && $filters['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                    <option value="occupied" <?php echo isset($filters['status']) && $filters['status'] === 'occupied' ? 'selected' : ''; ?>>Occupied</option>
                    <option value="maintenance" <?php echo isset($filters['status']) && $filters['status'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Type</label>
                <select id="propTypeFilter" name="type">
                    <option value="">All Types</option>
                    <option value="apartment" <?php echo isset($filters['type']) && $filters['type'] === 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                    <option value="house" <?php echo isset($filters['type']) && $filters['type'] === 'house' ? 'selected' : ''; ?>>House</option>
                    <option value="condo" <?php echo isset($filters['type']) && $filters['type'] === 'condo' ? 'selected' : ''; ?>>Condo</option>
                    <option value="townhouse" <?php echo isset($filters['type']) && $filters['type'] === 'townhouse' ? 'selected' : ''; ?>>Townhouse</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="<?php echo route('admin.properties'); ?>" class="btn" style="margin-left: 1rem;">
            Reset
        </a>
    </form>
</div>

<!-- Properties Grid -->
<div class="properties-grid" id="propertiesGrid">
    <?php
    if (empty($properties)):
    ?>
        <div style="grid-column: 1 / -1; padding: 2rem; text-align: center; color: #666;">
            No properties found.
        </div>
    <?php
    else:
        foreach ($properties as $prop):
            $statusClass = match($prop['status']) {
                'available' => 'badge-available',
                'occupied' => 'badge-occupied',
                'maintenance' => 'badge-maintenance',
                default => 'badge-available'
            };

            $statusText = match($prop['status']) {
                'available' => 'Available',
                'occupied' => 'Occupied',
                'maintenance' => 'Maintenance',
                default => ucfirst($prop['status'])
            };

            $amenities = !empty($prop['amenities']) ?
                json_decode($prop['amenities'], true) ?? [] : [];

            $unitCount = 0; // This would be populated from units table in production
    ?>
        <div class="property-card">
            <div class="property-badge <?php echo $statusClass; ?>">
                <?php echo $statusText; ?>
            </div>
            <div class="property-image">
                <i class="fas fa-home"></i>
            </div>
            <div class="property-info">
                <div class="property-listing-number">
                    <?php echo e($prop['listing_number'] ?? 'N/A'); ?>
                </div>
                <h3 class="property-title">
                    <?php echo e($prop['name']); ?>
                </h3>
                <p class="property-address">
                    <?php echo e($prop['address'] . ', ' . $prop['city'] . ', ' . $prop['state'] . ' ' . $prop['zip']); ?>
                </p>
                <div class="property-details">
                    <div class="property-detail">
                        <div class="detail-label">Type</div>
                        <div class="detail-value"><?php echo e($prop['type']); ?></div>
                    </div>
                    <div class="property-detail">
                        <div class="detail-label">Beds</div>
                        <div class="detail-value"><?php echo e((string) $prop['bedrooms']); ?></div>
                    </div>
                    <div class="property-detail">
                        <div class="detail-label">Baths</div>
                        <div class="detail-value"><?php echo e((string) $prop['bathrooms']); ?></div>
                    </div>
                </div>
                <div class="property-rent">
                    $<?php echo number_format((float) $prop['monthly_rent'], 2); ?>/month
                </div>
                <div class="property-footer">
                    <div class="property-occupancy">
                        <?php echo e((string) ($unitCount ?? 0)); ?> unit<?php echo ($unitCount ?? 0) !== 1 ? 's' : ''; ?>
                    </div>
                    <div class="property-actions">
                        <button class="btn-small btn-icon" onclick="viewProperty(<?php echo $prop['id']; ?>)" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-small btn-icon" onclick="editProperty(<?php echo $prop['id']; ?>)" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-small btn-icon" onclick="deleteProperty(<?php echo $prop['id']; ?>)" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php
        endforeach;
    endif;
    ?>
</div>

<!-- Add Property Modal -->
<div class="modal-overlay" id="addPropertyModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Property</h2>
            <button class="close-modal" onclick="closeModal('addPropertyModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="propertyForm" method="POST" action="/admin/properties">
                <?php echo csrf_field(); ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="propName">Property Name *</label>
                        <input type="text" id="propName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="propType">Type *</label>
                        <select id="propType" name="type" required>
                            <option value="">Select Type</option>
                            <option value="apartment">Apartment Building</option>
                            <option value="house">Single Family House</option>
                            <option value="condo">Condominium</option>
                            <option value="townhouse">Townhouse</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="propAddress">Address *</label>
                    <input type="text" id="propAddress" name="address" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="propCity">City *</label>
                        <input type="text" id="propCity" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="propState">State *</label>
                        <input type="text" id="propState" name="state" required>
                    </div>
                    <div class="form-group">
                        <label for="propZip">ZIP Code *</label>
                        <input type="text" id="propZip" name="zip" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="propRent">Monthly Rent *</label>
                        <input type="number" id="propRent" name="monthly_rent" required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="propDeposit">Security Deposit</label>
                        <input type="number" id="propDeposit" name="deposit" min="0" step="0.01">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="propBedrooms">Bedrooms</label>
                        <input type="number" id="propBedrooms" name="bedrooms" min="0">
                    </div>
                    <div class="form-group">
                        <label for="propBathrooms">Bathrooms</label>
                        <input type="number" id="propBathrooms" name="bathrooms" min="0" step="0.5">
                    </div>
                    <div class="form-group">
                        <label for="propSize">Square Feet</label>
                        <input type="number" id="propSize" name="sqft" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="propStatus">Status *</label>
                    <select id="propStatus" name="status" required>
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Under Maintenance</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="propDescription">Description</label>
                    <textarea id="propDescription" name="description" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="propAmenities">Amenities (comma separated)</label>
                    <input type="text" id="propAmenities" name="amenities" placeholder="e.g., Parking, Laundry, Pool, Gym">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Save Property
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Property Details Modal -->
<div class="modal-overlay property-details-modal" id="propertyDetailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Property Details</h2>
            <button class="close-modal" onclick="closeModal('propertyDetailsModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="property-gallery">
                <div class="gallery-item"><i class="fas fa-home"></i></div>
                <div class="gallery-item"><i class="fas fa-image"></i></div>
                <div class="gallery-item"><i class="fas fa-image"></i></div>
                <div class="gallery-item"><i class="fas fa-image"></i></div>
            </div>

            <div class="tab-navigation">
                <button class="tab-btn active" onclick="switchTab('overview')">Overview</button>
                <button class="tab-btn" onclick="switchTab('finances')">Finances</button>
                <button class="tab-btn" onclick="switchTab('documents')">Documents</button>
            </div>

            <div class="tab-content active" id="overviewTab">
                <div class="info-grid" id="propertyOverview">
                    <!-- Loaded by JavaScript -->
                </div>

                <div style="margin-top: 1.5rem;">
                    <h4 style="color: #2c5aa0; margin-bottom: 0.75rem;">Amenities</h4>
                    <ul class="amenities-list" id="propertyAmenities">
                        <!-- Loaded by JavaScript -->
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="financesTab">
                <h4 style="color: #2c5aa0; margin-bottom: 1rem;">Financial Information</h4>
                <div class="info-grid">
                    <div class="detail-item">
                        <label>Monthly Rent</label>
                        <span id="modalRent">$0</span>
                    </div>
                    <div class="detail-item">
                        <label>Security Deposit</label>
                        <span id="modalDeposit">$0</span>
                    </div>
                    <div class="detail-item">
                        <label>Annual Rent Revenue</label>
                        <span id="modalRevenue">$0</span>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="documentsTab">
                <h4 style="color: #2c5aa0; margin-bottom: 1rem;">Property Documents</h4>
                <p style="color: #666; text-align: center;">No documents uploaded yet.</p>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #eaeaea;">
                <div class="application-actions">
                    <button class="btn btn-primary" onclick="editCurrentProperty()">
                        <i class="fas fa-edit"></i> Edit Property
                    </button>
                    <button class="btn" style="background: #ef4444; color: white;" onclick="deleteCurrentProperty()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Properties data
const propertiesData = <?php echo json_encode($properties); ?>;

let currentView = 'grid';
let currentPropertyId = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
});

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function switchView(view) {
    currentView = view;

    // Update toggle buttons
    document.querySelectorAll('.view-toggle').forEach(btn => {
        btn.classList.remove('active', 'btn-primary');
    });
    event.target.closest('.view-toggle').classList.add('active', 'btn-primary');

    // Toggle views
    const mapView = document.getElementById('mapView');
    const propertyGrid = document.querySelector('.properties-grid') || document.querySelector('[class*="property"]');

    if (view === 'map') {
        mapView.style.display = 'block';
    } else {
        mapView.style.display = 'none';
    }
}

function openAddPropertyModal() {
    document.getElementById('propertyForm').reset();
    document.getElementById('propertyForm').action = '/admin/properties';
    document.querySelector('#propertyForm button[type="submit"]').innerHTML =
        '<i class="fas fa-save"></i> Save Property';
    document.getElementById('addPropertyModal').classList.add('active');
}

function viewProperty(id) {
    currentPropertyId = id;
    const prop = propertiesData.find(p => p.id === id);

    if (!prop) return;

    // Load overview
    const overview = document.getElementById('propertyOverview');
    overview.innerHTML = `
        <div class="detail-item">
            <label>Listing Number</label>
            <span>${escapeHtml(prop.listing_number || 'N/A')}</span>
        </div>
        <div class="detail-item">
            <label>Property Name</label>
            <span>${escapeHtml(prop.name)}</span>
        </div>
        <div class="detail-item">
            <label>Address</label>
            <span>${escapeHtml(prop.address + ', ' + prop.city + ', ' + prop.state + ' ' + prop.zip)}</span>
        </div>
        <div class="detail-item">
            <label>Type</label>
            <span>${escapeHtml(prop.type)}</span>
        </div>
        <div class="detail-item">
            <label>Status</label>
            <span style="text-transform: capitalize;">${escapeHtml(prop.status)}</span>
        </div>
        <div class="detail-item">
            <label>Bedrooms</label>
            <span>${prop.bedrooms}</span>
        </div>
        <div class="detail-item">
            <label>Bathrooms</label>
            <span>${prop.bathrooms}</span>
        </div>
        <div class="detail-item">
            <label>Square Feet</label>
            <span>${prop.sqft ? prop.sqft.toLocaleString() : '0'}</span>
        </div>
    `;

    // Load amenities
    const amenitiesList = document.getElementById('propertyAmenities');
    amenitiesList.innerHTML = '';
    const amenities = typeof prop.amenities === 'string' ?
        JSON.parse(prop.amenities || '[]') : (prop.amenities || []);
    amenities.forEach(amenity => {
        const li = document.createElement('li');
        li.className = 'amenity-item';
        li.innerHTML = `<i class="fas fa-check"></i> ${escapeHtml(amenity)}`;
        amenitiesList.appendChild(li);
    });

    // Load finances
    document.getElementById('modalRent').textContent = `$${Number(prop.monthly_rent).toFixed(2)}`;
    document.getElementById('modalDeposit').textContent = `$${Number(prop.deposit).toFixed(2)}`;
    document.getElementById('modalRevenue').textContent =
        `$${(Number(prop.monthly_rent) * 12).toLocaleString('en-US', {minimumFractionDigits: 2})}`;

    document.getElementById('propertyDetailsModal').classList.add('active');
}

function editProperty(id) {
    const prop = propertiesData.find(p => p.id === id);
    if (!prop) return;

    currentPropertyId = id;

    // Fill form
    document.getElementById('propName').value = prop.name;
    document.getElementById('propType').value = prop.type;
    document.getElementById('propAddress').value = prop.address;
    document.getElementById('propCity').value = prop.city;
    document.getElementById('propState').value = prop.state;
    document.getElementById('propZip').value = prop.zip;
    document.getElementById('propRent').value = prop.monthly_rent;
    document.getElementById('propDeposit').value = prop.deposit;
    document.getElementById('propBedrooms').value = prop.bedrooms;
    document.getElementById('propBathrooms').value = prop.bathrooms;
    document.getElementById('propSize').value = prop.sqft;
    document.getElementById('propStatus').value = prop.status;
    document.getElementById('propDescription').value = prop.description || '';

    const amenities = typeof prop.amenities === 'string' ?
        JSON.parse(prop.amenities || '[]') : (prop.amenities || []);
    document.getElementById('propAmenities').value = amenities.join(', ');

    // Update form action
    document.getElementById('propertyForm').action = '/admin/properties/' + id;

    // Update submit button
    const submitBtn = document.querySelector('#propertyForm button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Property';

    document.getElementById('addPropertyModal').classList.add('active');
}

function editCurrentProperty() {
    if (currentPropertyId) {
        closeModal('propertyDetailsModal');
        editProperty(currentPropertyId);
    }
}

function deleteProperty(id) {
    if (confirm('Are you sure you want to delete this property?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/properties/' + id + '/delete';

        form.innerHTML = `
            ${document.querySelector('input[name="_token"]').outerHTML}
        `;

        document.body.appendChild(form);
        form.submit();
    }
}

function deleteCurrentProperty() {
    if (currentPropertyId) {
        closeModal('propertyDetailsModal');
        deleteProperty(currentPropertyId);
    }
}

function switchTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(tabName + 'Tab').classList.add('active');
}

function exportProperties() {
    alert('Exporting properties data...');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<style>
.view-toggle {
    padding: 0.5rem 1.25rem;
    border: 1px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
}
.view-toggle.active {
    background: #2c5aa0;
    color: white;
    border-color: #2c5aa0;
}
</style>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/admin.php';
?>
