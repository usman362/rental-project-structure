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

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Map View (hidden by default) -->
<div class="map-view-section" id="mapView" style="display: none; background: white; border-radius: 10px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eaeaea;">
    <h3>Property Locations</h3>
    <div id="propertyMap" style="height: 450px; border-radius: 8px; z-index: 1;"></div>
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
            <form id="propertyForm" method="POST" action="<?= route('admin.properties') ?>">
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
                <div class="gallery-item" style="cursor: pointer;" onclick="Swal.fire({title:'Upload Image',text:'Image upload feature will be available soon.',icon:'info',confirmButtonColor:'#2c5aa0'})">+Add</div>
            </div>

            <div class="tab-navigation" style="border-bottom: 2px solid #eaeaea; margin-bottom: 1.5rem; display: flex; gap: 0;">
                <button class="tab-btn active" onclick="switchTab('overview')">Overview</button>
                <button class="tab-btn" onclick="switchTab('units')">Units</button>
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

            <div class="tab-content" id="unitsTab">
                <h4 style="color: #2c5aa0; margin-bottom: 1rem;">Property Units</h4>
                <div id="unitsContainer">
                    <!-- Loaded by JavaScript -->
                </div>
                <button class="add-unit-btn" onclick="addUnit()">
                    <i class="fas fa-plus"></i> Add Unit
                </button>
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
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h4 style="color: #2c5aa0; margin: 0;">Property Documents</h4>
                    <button class="btn btn-primary" onclick="openUploadDocumentModal()" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                        <i class="fas fa-plus"></i> Add Document
                    </button>
                </div>
                <div id="documentsContainer">
                    <!-- Loaded by JavaScript -->
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #eaeaea;">
                <div class="application-actions" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" onclick="editCurrentProperty()">
                        <i class="fas fa-edit"></i> Edit Property
                    </button>
                    <button class="btn btn-secondary" onclick="manageUnits()">
                        <i class="fas fa-building"></i> Manage Units
                    </button>
                    <button class="btn" style="background: #ef4444; color: white;" onclick="deleteCurrentProperty()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Form (hidden, submitted via JS) -->
<form id="uploadDocumentForm" method="POST" enctype="multipart/form-data" style="display:none;">
    <?php echo csrf_field(); ?>
    <input type="file" id="docFileInput" name="document">
    <input type="hidden" id="docTitleInput" name="doc_title">
    <input type="hidden" id="docTypeInput" name="doc_type">
</form>

<script>
// Properties data
const propertiesData = <?php echo json_encode($properties); ?>;
const documentsData = <?php echo json_encode($documentsByProperty ?? []); ?>;
const downloadBaseUrl = '<?= route("admin.properties.documents.download") ?>';
const propertiesBaseUrl = '<?= route("admin.properties") ?>';
const csrfToken = '<?= CSRF::generate() ?>';

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

let leafletMap = null;
let mapInitialized = false;

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
        if (!mapInitialized) {
            setTimeout(() => initPropertyMap(), 100);
        } else if (leafletMap) {
            leafletMap.invalidateSize();
        }
    } else {
        mapView.style.display = 'none';
    }
}

function initPropertyMap() {
    leafletMap = L.map('propertyMap').setView([34.0522, -118.2437], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(leafletMap);

    mapInitialized = true;

    // Custom marker icon
    const markerIcon = L.divIcon({
        html: '<i class="fas fa-map-marker-alt" style="font-size:28px;color:#2c5aa0;"></i>',
        iconSize: [28, 36],
        iconAnchor: [14, 36],
        popupAnchor: [0, -36],
        className: 'custom-map-marker'
    });

    if (propertiesData && propertiesData.length > 0) {
        const bounds = [];
        let geocodeDelay = 0;

        propertiesData.forEach(prop => {
            // If property has lat/lng use them directly
            if (prop.latitude && prop.longitude) {
                const lat = parseFloat(prop.latitude);
                const lng = parseFloat(prop.longitude);
                addPropertyMarker(lat, lng, prop, markerIcon);
                bounds.push([lat, lng]);
            } else {
                // Geocode using Nominatim (free, no API key)
                const address = [prop.address, prop.city, prop.state, prop.zip].filter(Boolean).join(', ');
                if (address) {
                    geocodeDelay += 1100; // Nominatim rate limit: 1 req/sec
                    setTimeout(() => geocodeAndMark(address, prop, markerIcon, bounds), geocodeDelay);
                }
            }
        });

        // Fit bounds after geocoding completes
        if (bounds.length > 0) {
            setTimeout(() => {
                if (bounds.length > 0) leafletMap.fitBounds(bounds, { padding: [40, 40] });
            }, geocodeDelay + 500);
        }
    }
}

function geocodeAndMark(address, prop, icon, bounds) {
    fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address) + '&limit=1')
        .then(res => res.json())
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                addPropertyMarker(lat, lng, prop, icon);
                bounds.push([lat, lng]);
                if (bounds.length > 0) leafletMap.fitBounds(bounds, { padding: [40, 40] });
            }
        })
        .catch(() => {});
}

function addPropertyMarker(lat, lng, prop, icon) {
    const statusColor = (prop.status || '') === 'available' ? '#10b981' : (prop.status || '') === 'occupied' ? '#ef4444' : '#f59e0b';
    const statusText = (prop.status || 'N/A').charAt(0).toUpperCase() + (prop.status || '').slice(1);

    const popup = `
        <div style="min-width:200px;font-family:inherit;">
            <h4 style="margin:0 0 0.5rem 0;color:#333;">${escapeHtml(prop.name || 'Unnamed')}</h4>
            <p style="margin:0 0 0.25rem 0;color:#666;font-size:13px;">
                <i class="fas fa-map-marker-alt" style="color:#2c5aa0;width:16px;"></i>
                ${escapeHtml((prop.address || '') + ', ' + (prop.city || '') + ', ' + (prop.state || '') + ' ' + (prop.zip || ''))}
            </p>
            <p style="margin:0 0 0.25rem 0;color:#666;font-size:13px;">
                <i class="fas fa-home" style="color:#2c5aa0;width:16px;"></i>
                ${escapeHtml(prop.type || 'N/A')} &bull; ${prop.bedrooms || 0} bed / ${prop.bathrooms || 0} bath
            </p>
            <p style="margin:0.5rem 0 0 0;">
                <span style="background:${statusColor};color:white;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:600;">${statusText}</span>
            </p>
            <button onclick="viewProperty(${prop.id})" style="margin-top:0.5rem;background:#2c5aa0;color:white;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;font-size:12px;width:100%;">
                <i class="fas fa-eye"></i> View Details
            </button>
        </div>
    `;

    L.marker([lat, lng], { icon: icon }).addTo(leafletMap).bindPopup(popup);
}

function openAddPropertyModal() {
    document.getElementById('propertyForm').reset();
    document.getElementById('propertyForm').action = '<?= route("admin.properties") ?>';
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

    // Load units
    const unitsContainer = document.getElementById('unitsContainer');
    if (prop.unit) {
        unitsContainer.innerHTML = `
            <div class="unit-item">
                <div class="unit-info">
                    <strong>${escapeHtml(prop.unit)}</strong>
                    <div class="unit-meta">
                        <span>${prop.bedrooms} Bedroom</span>
                        <span>Status: <strong style="text-transform:capitalize;">${escapeHtml(prop.status)}</strong></span>
                        <span>Rent: $${Number(prop.monthly_rent).toLocaleString()}</span>
                    </div>
                </div>
                <button class="unit-edit-btn" onclick="editUnit('${escapeHtml(prop.unit)}')">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        `;
    } else {
        unitsContainer.innerHTML = '<p style="color: #666; text-align: center;">No units added yet.</p>';
    }

    // Load finances
    document.getElementById('modalRent').textContent = `$${Number(prop.monthly_rent).toFixed(2)}`;
    document.getElementById('modalDeposit').textContent = `$${Number(prop.deposit).toFixed(2)}`;
    document.getElementById('modalRevenue').textContent =
        `$${(Number(prop.monthly_rent) * 12).toLocaleString('en-US', {minimumFractionDigits: 2})}`;

    // Load documents
    loadDocuments(id);

    // Reset to overview tab
    switchTabDirect('overview');

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
    document.getElementById('propertyForm').action = '<?= route("admin.properties") ?>/' + id + '/update';

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
    Swal.fire({
        title: 'Delete Property?',
        html: '<p>This action <strong>cannot be undone</strong>. The property and all associated data will be removed.</p>',
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
            form.action = '<?= route("admin.properties") ?>/' + id + '/delete';

            form.innerHTML = `
                ${document.querySelector('input[name="_token"]').outerHTML}
            `;

            document.body.appendChild(form);
            form.submit();
        }
    });
}

function deleteCurrentProperty() {
    if (currentPropertyId) {
        closeModal('propertyDetailsModal');
        deleteProperty(currentPropertyId);
    }
}

function switchTab(tabName) {
    document.querySelectorAll('#propertyDetailsModal .tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    if (event && event.target) {
        event.target.classList.add('active');
    }

    document.querySelectorAll('#propertyDetailsModal .tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(tabName + 'Tab').classList.add('active');
}

function switchTabDirect(tabName) {
    document.querySelectorAll('#propertyDetailsModal .tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const tabBtns = document.querySelectorAll('#propertyDetailsModal .tab-btn');
    tabBtns.forEach(btn => {
        if (btn.textContent.trim().toLowerCase() === tabName) {
            btn.classList.add('active');
        }
    });

    document.querySelectorAll('#propertyDetailsModal .tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(tabName + 'Tab').classList.add('active');
}

function addUnit() {
    Swal.fire({
        title: 'Add Unit',
        html: `
            <div style="text-align:left;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Unit Name/Number *</label>
                    <input type="text" id="swal-unit-name" class="swal2-input" style="width:100%;margin:0;" placeholder="e.g. Unit 4A">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Bedrooms</label>
                        <input type="number" id="swal-unit-beds" class="swal2-input" style="width:100%;margin:0;" value="1" min="0">
                    </div>
                    <div>
                        <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Rent ($)</label>
                        <input type="number" id="swal-unit-rent" class="swal2-input" style="width:100%;margin:0;" value="0" min="0" step="0.01">
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Status</label>
                    <select id="swal-unit-status" class="swal2-select" style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:4px;">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-plus"></i> Add Unit',
        preConfirm: () => {
            const name = document.getElementById('swal-unit-name').value;
            if (!name) { Swal.showValidationMessage('Unit name is required'); }
            return { name };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Unit Added!',
                html: `<strong>${escapeHtml(result.value.name)}</strong> has been added to this property.`,
                icon: 'success',
                confirmButtonColor: '#2c5aa0',
                timer: 2500,
                timerProgressBar: true
            });
        }
    });
}

function editUnit(unitName) {
    Swal.fire({
        title: 'Edit Unit',
        html: `<p>Editing unit <strong>${unitName}</strong></p>`,
        input: 'select',
        inputOptions: { 'available': 'Available', 'occupied': 'Occupied', 'maintenance': 'Maintenance' },
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Update Status'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Unit Updated!',
                text: `${unitName} status updated to ${result.value}.`,
                icon: 'success',
                confirmButtonColor: '#2c5aa0',
                timer: 2500,
                timerProgressBar: true
            });
        }
    });
}

function manageUnits() {
    switchTab('units');
    // Scroll to units tab if in the modal
    const unitsTab = document.getElementById('unitsTab');
    if (unitsTab) {
        // Activate the units tab button manually
        document.querySelectorAll('#propertyDetailsModal .tab-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.trim() === 'Units') btn.classList.add('active');
        });
        document.querySelectorAll('#propertyDetailsModal .tab-content').forEach(c => c.classList.remove('active'));
        unitsTab.classList.add('active');
    }
}

function exportProperties() {
    if (!propertiesData || propertiesData.length === 0) {
        Swal.fire({ title: 'No Data', text: 'No properties to export.', icon: 'warning', confirmButtonColor: '#2c5aa0' });
        return;
    }

    const headers = ['Listing Number', 'Name', 'Address', 'City', 'State', 'Zip', 'Type', 'Status', 'Bedrooms', 'Bathrooms', 'Sqft', 'Rent Price'];

    const rows = propertiesData.map(p => [
        p.listing_number || '',
        p.name || '',
        p.address || '',
        p.city || '',
        p.state || '',
        p.zip || '',
        p.type || '',
        (p.status || '').charAt(0).toUpperCase() + (p.status || '').slice(1),
        p.bedrooms || 0,
        p.bathrooms || 0,
        p.sqft || 0,
        parseFloat(p.rent_price || p.monthly_rent || 0).toFixed(2)
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
    link.download = 'properties_export_' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    Swal.fire({
        title: 'Export Complete!',
        text: 'Properties data has been downloaded as CSV.',
        icon: 'success',
        confirmButtonColor: '#2c5aa0',
        timer: 3000,
        timerProgressBar: true
    });
}

function loadDocuments(propertyId) {
    const container = document.getElementById('documentsContainer');
    const docs = documentsData[propertyId] || [];

    if (docs.length === 0) {
        container.innerHTML = '<p style="color: #666; text-align: center; padding: 2rem 0;">No documents uploaded yet.</p>';
        return;
    }

    let html = '';
    docs.forEach(doc => {
        const fileIcon = getFileIcon(doc.file_name || doc.type || '');
        const fileSize = formatFileSize(doc.file_size || 0);
        const uploadDate = doc.created_at ? new Date(doc.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';
        const typeBadge = doc.type ? `<span style="background: #e8f0fe; color: #2c5aa0; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem; text-transform: capitalize;">${escapeHtml(doc.type.replace('_', ' '))}</span>` : '';

        html += `
            <div class="document-item" style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; border: 1px solid #eaeaea; border-radius: 8px; margin-bottom: 0.5rem; background: #fafafa;">
                <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1;">
                    <div style="width: 40px; height: 40px; background: #e8f0fe; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2c5aa0; font-size: 1.1rem;">
                        <i class="fas ${fileIcon}"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #333; font-size: 0.9rem;">${escapeHtml(doc.title)}</div>
                        <div style="font-size: 0.75rem; color: #888; margin-top: 0.15rem;">
                            ${typeBadge} ${fileSize} &bull; ${uploadDate}
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="${downloadBaseUrl}?id=${doc.id}" class="btn btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; text-decoration: none;">
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button class="btn" style="padding: 0.35rem 0.6rem; font-size: 0.8rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;" onclick="deleteDocument(${doc.id}, ${JSON.stringify(doc.title)})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function getFileIcon(fileName) {
    const ext = (typeof fileName === 'string' ? fileName.split('.').pop() : '').toLowerCase();
    switch(ext) {
        case 'pdf': return 'fa-file-pdf';
        case 'doc': case 'docx': return 'fa-file-word';
        case 'xls': case 'xlsx': return 'fa-file-excel';
        case 'jpg': case 'jpeg': case 'png': case 'gif': return 'fa-file-image';
        default: return 'fa-file-alt';
    }
}

function formatFileSize(bytes) {
    if (!bytes || bytes === 0) return '';
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

function openUploadDocumentModal() {
    Swal.fire({
        title: 'Upload Document',
        html: `
            <div style="text-align:left;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Document Title *</label>
                    <input type="text" id="swal-doc-title" class="swal2-input" style="width:100%;margin:0;" placeholder="e.g. Lease Agreement">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Document Type</label>
                    <select id="swal-doc-type" class="swal2-select" style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:4px;">
                        <option value="lease">Lease Agreement</option>
                        <option value="inspection">Inspection Report</option>
                        <option value="insurance">Property Insurance</option>
                        <option value="tax">Tax Document</option>
                        <option value="maintenance">Maintenance Record</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div style="margin-bottom:0.5rem;">
                    <label style="display:block;margin-bottom:0.25rem;font-weight:500;">Select File *</label>
                    <input type="file" id="swal-doc-file" accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx" style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:4px;">
                    <small style="color:#888;">Allowed: PDF, JPG, PNG, GIF, DOC, DOCX, XLS, XLSX (max 10MB)</small>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#2c5aa0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-upload"></i> Upload',
        preConfirm: () => {
            const title = document.getElementById('swal-doc-title').value.trim();
            const fileInput = document.getElementById('swal-doc-file');
            if (!title) {
                Swal.showValidationMessage('Document title is required');
                return false;
            }
            if (!fileInput.files || fileInput.files.length === 0) {
                Swal.showValidationMessage('Please select a file to upload');
                return false;
            }
            return {
                title: title,
                type: document.getElementById('swal-doc-type').value,
                file: fileInput.files[0]
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Build and submit the hidden form
            const form = document.getElementById('uploadDocumentForm');
            form.action = propertiesBaseUrl + '/' + currentPropertyId + '/documents';
            document.getElementById('docTitleInput').value = result.value.title;
            document.getElementById('docTypeInput').value = result.value.type;

            // Set the file using DataTransfer
            const dt = new DataTransfer();
            dt.items.add(result.value.file);
            document.getElementById('docFileInput').files = dt.files;

            form.submit();
        }
    });
}

function deleteDocument(docId, docTitle) {
    Swal.fire({
        title: 'Delete Document?',
        html: `<p>Are you sure you want to delete <strong>${docTitle}</strong>? This action cannot be undone.</p>`,
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
            form.action = propertiesBaseUrl + '/documents/' + docId + '/delete';
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
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
