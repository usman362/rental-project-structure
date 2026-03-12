<?php
$title = 'Rental Application';
ob_start();
?>

<style>
    /* Application Page Styles */
    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Property Input Section */
    .property-input-section {
        background: linear-gradient(135deg, #2c5aa0 0%, #1d3a6e 100%);
        color: white;
        padding: 3rem;
        border-radius: 12px;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(44, 90, 160, 0.2);
    }

    .property-input-section h1 {
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .property-input-section p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        opacity: 0.95;
    }

    .input-group {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .input-row {
        display: flex;
        gap: 1.5rem;
    }

    .input-row .form-group {
        flex: 1;
    }

    .property-input-section .form-group label {
        color: white;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .property-input-section .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        background: rgba(255, 255, 255, 0.95);
    }

    .property-input-section .form-group input:focus {
        outline: none;
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }

    .submit-property-btn {
        background: white;
        color: #2c5aa0;
        border: none;
        padding: 14px 30px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
    }

    .submit-property-btn:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Property Info Section */
    .property-info-section {
        display: none;
        background: white;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .property-info-section.visible {
        display: block;
    }

    .property-header {
        margin-bottom: 2rem;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1.5rem;
    }

    .property-header h2 {
        font-size: 2rem;
        color: #1a1a1a;
        margin: 0 0 0.5rem 0;
    }

    .property-header p {
        font-size: 1.1rem;
        color: #2c5aa0;
        margin: 0;
        font-weight: 500;
    }

    .property-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2.5rem;
    }

    .details-card {
        background: #f9f9f9;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #2c5aa0;
    }

    .details-card h3 {
        color: #2c5aa0;
        margin: 0 0 1rem 0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .details-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .details-card li {
        padding: 0.5rem 0;
        color: #333;
        font-size: 15px;
    }

    .details-card li strong {
        color: #2c5aa0;
    }

    .application-btn-container {
        text-align: center;
        margin-top: 2rem;
    }

    .open-application-btn {
        background: linear-gradient(135deg, #2c5aa0 0%, #1d3a6e 100%);
        color: white;
        border: none;
        padding: 14px 40px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .open-application-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(44, 90, 160, 0.3);
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
        padding: 20px;
        overflow-y: auto;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 100%;
        max-width: 650px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease-out;
        margin: auto;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        background-color: #2c5aa0;
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .close-modal {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.3s;
    }

    .close-modal:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .modal-body {
        padding: 2rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section h3 {
        color: #2c5aa0;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.75rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group-full {
        display: flex;
        flex-direction: column;
    }

    .form-group-full label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #333;
        font-size: 14px;
    }

    .form-group-full label.required::after {
        content: ' *';
        color: #e74c3c;
    }

    .form-group-full input,
    .form-group-full select,
    .form-group-full textarea {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 15px;
        font-family: inherit;
        transition: border 0.3s, box-shadow 0.3s;
    }

    .form-group-full input:focus,
    .form-group-full select:focus,
    .form-group-full textarea:focus {
        border-color: #2c5aa0;
        outline: none;
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }

    .form-group-full small {
        font-size: 13px;
        color: #777;
        margin-top: 0.3rem;
    }

    /* Modal specific form group */
    #portalLoginModal .form-group {
        margin-bottom: 1.5rem;
    }

    #portalLoginModal .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #444;
        font-weight: 500;
        font-size: 14px;
    }

    #portalLoginModal .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: border 0.3s, box-shadow 0.3s;
    }

    #portalLoginModal .form-group input:focus {
        border-color: #2c5aa0;
        outline: none;
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }

    .modal-btn {
        width: 100%;
        background-color: #2c5aa0;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 10px;
    }

    .modal-btn:hover {
        background-color: #1d4a8a;
    }

    .modal-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: #777;
        font-size: 14px;
    }

    .modal-footer a {
        color: #2c5aa0;
        text-decoration: none;
    }

    .modal-footer a:hover {
        text-decoration: underline;
    }

    /* Occupants section */
    .occupants-section {
        background-color: #f0f7ff;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .add-occupant-btn {
        background-color: #2c5aa0;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .add-occupant-btn:hover {
        background-color: #1d4a8a;
    }

    .remove-occupant-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: background-color 0.3s;
    }

    .remove-occupant-btn:hover {
        background-color: #c0392b;
    }

    /* Disclosure section */
    .disclosure-section {
        background-color: #f0f7ff;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #2c5aa0;
        margin-top: 2rem;
    }

    .disclosure-content {
        max-height: 200px;
        overflow-y: auto;
        padding: 1rem;
        background-color: white;
        border-radius: 5px;
        border: 1px solid #ddd;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .terms-agreement {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
        align-items: flex-start;
    }

    .terms-agreement input[type="checkbox"] {
        margin-top: 0.3rem;
        cursor: pointer;
        min-width: 18px;
    }

    .terms-agreement label {
        font-size: 14px;
        color: #333;
        cursor: pointer;
        line-height: 1.5;
    }

    .submit-application-btn {
        width: 100%;
        background: linear-gradient(135deg, #2c5aa0 0%, #1d3a6e 100%);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 1.5rem;
    }

    .submit-application-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(44, 90, 160, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .input-row {
            flex-direction: column;
        }

        .property-input-section {
            padding: 2rem;
        }

        .property-input-section h1 {
            font-size: 1.8rem;
        }

        .modal-body {
            max-height: 80vh;
        }
    }
</style>

<div class="container">
    <!-- Property Input Section -->
    <div class="property-input-section">
        <h1><i class="fas fa-home"></i> Rental Application</h1>
        <p>Enter the property address or listing number to begin your rental application</p>

        <form id="propertyInputForm">
            <div class="input-group">
                <div class="input-row">
                    <div class="form-group">
                        <label for="propertyAddress">Property Address</label>
                        <input
                            type="text"
                            id="propertyAddress"
                            placeholder="e.g., 1234 Main Street, City, State"
                        >
                    </div>
                </div>

                <div class="input-row">
                    <div class="form-group">
                        <label for="listingNumber">Listing Number (Optional)</label>
                        <input
                            type="text"
                            id="listingNumber"
                            placeholder="e.g., SMP-2024-001"
                        >
                    </div>
                </div>

                <button type="submit" class="submit-property-btn">
                    <i class="fas fa-search"></i> Find Property & Start Application
                </button>
            </div>
        </form>
    </div>

    <!-- Property Info Display (Hidden by default) -->
    <div class="property-info-section" id="propertyInfoSection">
        <div class="property-header">
            <h2 id="propertyAddressDisplay">Property Address</h2>
            <p id="propertyRentDisplay">$0/month</p>
        </div>

        <div class="property-details-grid">
            <div class="details-card">
                <h3><i class="fas fa-home"></i> Property Details</h3>
                <ul>
                    <li>Type: <strong id="propertyType">-</strong></li>
                    <li>Bedrooms: <strong id="propertyBedrooms">-</strong></li>
                    <li>Bathrooms: <strong id="propertyBathrooms">-</strong></li>
                    <li>Square Footage: <strong id="propertySqft">-</strong></li>
                </ul>
            </div>

            <div class="details-card">
                <h3><i class="fas fa-dollar-sign"></i> Move-in Costs</h3>
                <ul>
                    <li>Monthly Rent: <strong id="displayMonthlyRent">$0</strong></li>
                    <li>Security Deposit: <strong id="displayDeposit">$0</strong></li>
                    <li style="background-color: #e8f4fc; padding: 10px; border-radius: 5px; margin-top: 10px;">
                        Total Due: <strong id="totalCost">$0</strong>
                    </li>
                </ul>
            </div>

            <div class="details-card">
                <h3><i class="fas fa-list"></i> Listing Information</h3>
                <ul>
                    <li>Listing #: <strong id="propertyListing">-</strong></li>
                    <li>Status: <strong id="propertyStatus">-</strong></li>
                    <li id="propertyDescriptionItem" style="display: none; margin-top: 10px;">
                        <strong>Description:</strong><br>
                        <span id="propertyDescription" style="font-size: 14px;"></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="application-btn-container">
            <button class="open-application-btn" id="openApplicationBtn">
                <i class="fas fa-file-alt"></i> Start Rental Application
            </button>
        </div>
    </div>
</div>

<!-- Portal Login Modal -->
<div class="modal-overlay" id="portalLoginModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Client Portal Login</h2>
            <button class="close-modal" id="closePortalModal" type="button">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= route('login') ?>">
                <div class="form-group">
                    <label for="portalUsername">Username or Email</label>
                    <input
                        type="text"
                        id="portalUsername"
                        name="username"
                        placeholder="Enter your username or email"
                        value="<?= e(old('username')) ?>"
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="portalPassword">Password</label>
                    <input
                        type="password"
                        id="portalPassword"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                </div>
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="login">
                <button type="submit" class="modal-btn">Login to Portal</button>
            </form>
            <div class="modal-footer">
                <p>Need help accessing your account? Contact <a href="mailto:support@sotelomanage.com">support@sotelomanage.com</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Rental Application Modal -->
<div class="modal-overlay" id="applicationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="applicationModalTitle">Rental Application</h2>
            <button class="close-modal" id="closeApplicationModal" type="button">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= route('application') ?>" id="rentalApplicationForm">
                <!-- Primary Applicant Information -->
                <div class="form-section">
                    <h3><i class="fas fa-user"></i> Primary Applicant Information</h3>
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="firstName" class="required">First Name</label>
                            <input
                                type="text"
                                id="firstName"
                                name="first_name"
                                value="<?= e(old('first_name')) ?>"
                                required
                            >
                        </div>
                        <div class="form-group-full">
                            <label for="lastName" class="required">Last Name</label>
                            <input
                                type="text"
                                id="lastName"
                                name="last_name"
                                value="<?= e(old('last_name')) ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="email" class="required">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= e(old('email')) ?>"
                                required
                            >
                        </div>
                        <div class="form-group-full">
                            <label for="phone" class="required">Phone Number</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="<?= e(old('phone')) ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="ssn" class="required">SSN (Last 4 Digits)</label>
                            <input
                                type="text"
                                id="ssn"
                                name="ssn_last4"
                                maxlength="4"
                                pattern="\d{4}"
                                placeholder="Last 4 digits only"
                                value="<?= e(old('ssn_last4')) ?>"
                                required
                            >
                            <small>Required for credit check. We only need the last 4 digits.</small>
                        </div>
                        <div class="form-group-full">
                            <label for="dob" class="required">Date of Birth</label>
                            <input
                                type="date"
                                id="dob"
                                name="date_of_birth"
                                value="<?= e(old('date_of_birth')) ?>"
                                required
                            >
                        </div>
                    </div>
                </div>

                <!-- Employment & Income Information -->
                <div class="form-section">
                    <h3><i class="fas fa-briefcase"></i> Employment & Income Information</h3>
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="employer" class="required">Current Employer</label>
                            <input
                                type="text"
                                id="employer"
                                name="employer"
                                value="<?= e(old('employer')) ?>"
                                required
                            >
                        </div>
                        <div class="form-group-full">
                            <label for="jobTitle" class="required">Job Title/Position</label>
                            <input
                                type="text"
                                id="jobTitle"
                                name="job_title"
                                value="<?= e(old('job_title')) ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="employmentLength" class="required">Length of Employment (Months)</label>
                            <input
                                type="number"
                                id="employmentLength"
                                name="employment_length"
                                min="0"
                                value="<?= e(old('employment_length')) ?>"
                                required
                            >
                        </div>
                        <div class="form-group-full">
                            <label for="monthlyIncome" class="required">Gross Monthly Income ($)</label>
                            <input
                                type="number"
                                id="monthlyIncome"
                                name="monthly_income"
                                min="0"
                                step="100"
                                value="<?= e(old('monthly_income')) ?>"
                                required
                            >
                            <small>Must be at least 2.5 times monthly rent to qualify</small>
                        </div>
                    </div>
                </div>

                <!-- Rental History -->
                <div class="form-section">
                    <h3><i class="fas fa-history"></i> Rental History</h3>
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="currentAddress" class="required">Current Address</label>
                            <input
                                type="text"
                                id="currentAddress"
                                name="current_address"
                                value="<?= e(old('current_address')) ?>"
                                required
                            >
                        </div>
                        <div class="form-group-full">
                            <label for="currentLandlord">Current Landlord/Property Manager</label>
                            <input
                                type="text"
                                id="currentLandlord"
                                name="current_landlord"
                                value="<?= e(old('current_landlord')) ?>"
                            >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="landlordPhone">Landlord Phone Number</label>
                            <input
                                type="tel"
                                id="landlordPhone"
                                name="landlord_phone"
                                value="<?= e(old('landlord_phone')) ?>"
                            >
                        </div>
                        <div class="form-group-full">
                            <label for="monthsAtAddress">Months at Current Address</label>
                            <input
                                type="number"
                                id="monthsAtAddress"
                                name="months_at_address"
                                min="0"
                                value="<?= e(old('months_at_address')) ?>"
                            >
                        </div>
                    </div>
                </div>

                <!-- Occupants Information -->
                <div class="form-section">
                    <h3><i class="fas fa-users"></i> Occupants Information</h3>
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="totalOccupants" class="required">Total Number of Occupants</label>
                            <input
                                type="number"
                                id="totalOccupants"
                                name="total_occupants"
                                min="1"
                                value="<?= e(old('total_occupants')) ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="occupants-section">
                        <h4 style="color: #2c5aa0; margin-top: 0;">Additional Occupants</h4>
                        <div id="occupantsContainer"></div>
                        <button type="button" class="add-occupant-btn" id="addOccupantBtn">
                            <i class="fas fa-plus"></i> Add Occupant
                        </button>
                    </div>
                </div>

                <!-- Pet Information -->
                <div class="form-section">
                    <h3><i class="fas fa-paw"></i> Pet Information</h3>
                    <div class="form-row">
                        <div class="form-group-full">
                            <label for="hasPets" class="required">Do you have any pets?</label>
                            <select
                                id="hasPets"
                                name="has_pets"
                                required
                            >
                                <option value="">Select...</option>
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div id="petDetailsSection" style="display: none;">
                        <div class="form-row">
                            <div class="form-group-full">
                                <label for="petType">Type of Pet</label>
                                <input
                                    type="text"
                                    id="petType"
                                    name="pet_type"
                                    placeholder="e.g., Dog, Cat, Bird"
                                    value="<?= e(old('pet_type')) ?>"
                                >
                            </div>
                            <div class="form-group-full">
                                <label for="petBreed">Breed</label>
                                <input
                                    type="text"
                                    id="petBreed"
                                    name="pet_breed"
                                    value="<?= e(old('pet_breed')) ?>"
                                >
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group-full">
                                <label for="petWeight">Weight (lbs)</label>
                                <input
                                    type="number"
                                    id="petWeight"
                                    name="pet_weight"
                                    min="0"
                                    step="0.1"
                                    value="<?= e(old('pet_weight')) ?>"
                                >
                            </div>
                            <div class="form-group-full">
                                <label for="petAge">Age (Years)</label>
                                <input
                                    type="number"
                                    id="petAge"
                                    name="pet_age"
                                    min="0"
                                    step="0.1"
                                    value="<?= e(old('pet_age')) ?>"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Required Disclosures -->
                <div class="disclosure-section">
                    <h3><i class="fas fa-file-contract"></i> Required Disclosures</h3>
                    <div class="disclosure-content">
                        <p>
                            <strong>Megan's Law Disclosure:</strong> Pursuant to Section 290.46 of the California Penal Code, information about specified registered sex offenders is available to the public via an Internet Web site maintained by the Department of Justice at www.meganslaw.ca.gov.
                        </p>

                        <p>
                            <strong>Credit Check Authorization:</strong> By submitting this application, you authorize us to obtain your credit report and verify the information provided. Application fee: $52.46 (California maximum).
                        </p>

                        <p>
                            <strong>Fair Housing:</strong> We comply with all federal, state, and local fair housing laws. We do not discriminate based on race, color, religion, sex, national origin, familial status, disability, sexual orientation, gender identity, source of income, or any other protected class.
                        </p>
                    </div>

                    <div class="terms-agreement">
                        <input
                            type="checkbox"
                            id="agreeDisclosures"
                            name="agree_disclosures"
                            required
                        >
                        <label for="agreeDisclosures" class="required">
                            I have read and understand all disclosures above.
                        </label>
                    </div>

                    <div class="terms-agreement">
                        <input
                            type="checkbox"
                            id="agreeCertify"
                            name="agree_certify"
                            required
                        >
                        <label for="agreeCertify" class="required">
                            I certify that all information provided in this application is true and complete. I authorize verification of all information provided, including credit, criminal, employment, and rental history checks.
                        </label>
                    </div>

                    <div class="terms-agreement">
                        <input
                            type="checkbox"
                            id="agreeFee"
                            name="agree_fee"
                            required
                        >
                        <label for="agreeFee" class="required">
                            I understand there is a non-refundable application fee of $52.46 (California maximum) that will be required if my application proceeds to the screening stage.
                        </label>
                    </div>
                </div>

                <!-- Hidden field for property_id -->
                <input type="hidden" id="propertyIdField" name="property_id" value="">

                <?= csrf_field() ?>
                <input type="hidden" name="action" value="submit_application">

                <button type="submit" class="submit-application-btn" id="submitApplicationBtn">
                    <i class="fas fa-paper-plane"></i> Submit Rental Application
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Properties data from PHP
    const propertiesData = <?= $propertyPayload ?>;

    // Modal elements
    const portalLoginModal = document.getElementById('portalLoginModal');
    const closePortalModalBtn = document.getElementById('closePortalModal');
    const applicationModal = document.getElementById('applicationModal');
    const closeApplicationModalBtn = document.getElementById('closeApplicationModal');
    const propertyInputForm = document.getElementById('propertyInputForm');
    const openApplicationBtn = document.getElementById('openApplicationBtn');
    const propertyInfoSection = document.getElementById('propertyInfoSection');

    // Form elements
    const propertyAddressInput = document.getElementById('propertyAddress');
    const listingNumberInput = document.getElementById('listingNumber');
    let selectedProperty = null;

    // Close portal login modal
    if (closePortalModalBtn) {
        closePortalModalBtn.addEventListener('click', function() {
            portalLoginModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    }

    // Close application modal
    if (closeApplicationModalBtn) {
        closeApplicationModalBtn.addEventListener('click', function() {
            applicationModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    }

    // Close modal when clicking outside
    [portalLoginModal, applicationModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            [portalLoginModal, applicationModal].forEach(modal => {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        }
    });

    // Property search function
    function searchProperty() {
        const address = propertyAddressInput.value.toLowerCase().trim();
        const listing = listingNumberInput.value.toLowerCase().trim();

        if (!address && !listing) {
            alert('Please enter a property address or listing number');
            return false;
        }

        // Search in properties data
        selectedProperty = propertiesData.find(prop => {
            const propAddress = (prop.address + ', ' + prop.city + ', ' + prop.state).toLowerCase();
            const propListing = prop.listing_number.toLowerCase();

            return (address && propAddress.includes(address)) ||
                   (listing && propListing === listing);
        });

        if (selectedProperty) {
            displayPropertyInfo();
            return true;
        } else {
            alert('Property not found. Please check your address or listing number and try again.');
            return false;
        }
    }

    function displayPropertyInfo() {
        if (!selectedProperty) return;

        // Update property display
        document.getElementById('propertyAddressDisplay').textContent =
            `${selectedProperty.address}, ${selectedProperty.city}, ${selectedProperty.state}`;
        document.getElementById('propertyRentDisplay').textContent =
            `$${parseFloat(selectedProperty.monthly_rent).toLocaleString()}/month`;
        document.getElementById('propertyType').textContent = selectedProperty.type || '-';
        document.getElementById('propertyBedrooms').textContent = selectedProperty.bedrooms || '-';
        document.getElementById('propertyBathrooms').textContent = selectedProperty.bathrooms || '-';
        document.getElementById('propertySqft').textContent = selectedProperty.sqft ? selectedProperty.sqft.toLocaleString() : '-';
        document.getElementById('propertyListing').textContent = selectedProperty.listing_number;
        document.getElementById('propertyStatus').textContent = selectedProperty.status;
        document.getElementById('displayMonthlyRent').textContent =
            `$${parseFloat(selectedProperty.monthly_rent).toLocaleString()}`;
        document.getElementById('displayDeposit').textContent =
            `$${parseFloat(selectedProperty.deposit).toLocaleString()}`;

        const totalCost = parseFloat(selectedProperty.monthly_rent) + parseFloat(selectedProperty.deposit);
        document.getElementById('totalCost').textContent =
            `$${totalCost.toLocaleString()}`;

        // Show description if available
        if (selectedProperty.description) {
            document.getElementById('propertyDescription').textContent = selectedProperty.description;
            document.getElementById('propertyDescriptionItem').style.display = 'block';
        }

        // Set property ID in hidden field
        document.getElementById('propertyIdField').value = selectedProperty.id;

        // Update modal title
        document.getElementById('applicationModalTitle').textContent =
            `Rental Application - ${selectedProperty.address}`;

        // Show property info section
        propertyInfoSection.classList.add('visible');
    }

    // Property input form submission
    propertyInputForm.addEventListener('submit', function(e) {
        e.preventDefault();
        searchProperty();
    });

    // Open application form
    openApplicationBtn.addEventListener('click', function() {
        if (selectedProperty) {
            applicationModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });

    // Client portal button - find and add event listener
    const clientPortalBtn = document.querySelector('.portal-btn') ||
                           document.querySelector('[data-action="open-login"]');
    if (clientPortalBtn) {
        clientPortalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            portalLoginModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // Pet information conditional display
    const hasPetsSelect = document.getElementById('hasPets');
    const petDetailsSection = document.getElementById('petDetailsSection');

    if (hasPetsSelect) {
        hasPetsSelect.addEventListener('change', function() {
            if (this.value === 'yes') {
                petDetailsSection.style.display = 'block';
            } else {
                petDetailsSection.style.display = 'none';
            }
        });
    }

    // Occupants add/remove functionality
    let occupantCount = 0;
    const addOccupantBtn = document.getElementById('addOccupantBtn');
    const occupantsContainer = document.getElementById('occupantsContainer');

    if (addOccupantBtn) {
        addOccupantBtn.addEventListener('click', function(e) {
            e.preventDefault();
            addOccupant();
        });
    }

    function addOccupant() {
        occupantCount++;
        const occupantRow = document.createElement('div');
        occupantRow.className = 'form-row';
        occupantRow.id = 'occupant-' + occupantCount;
        occupantRow.style.marginTop = '1rem';

        occupantRow.innerHTML = `
            <div class="form-group-full">
                <label>Full Name</label>
                <input type="text" name="occupant_names[]" value="">
            </div>
            <div class="form-group-full">
                <label>Relationship</label>
                <input type="text" name="occupant_relationships[]" value="">
            </div>
            <div class="form-group-full">
                <label>Age</label>
                <input type="number" name="occupant_ages[]" value="" min="0">
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="button" class="remove-occupant-btn" onclick="removeOccupant('occupant-${occupantCount}')">
                    &times;
                </button>
            </div>
        `;

        occupantsContainer.appendChild(occupantRow);
    }

    function removeOccupant(id) {
        const element = document.getElementById(id);
        if (element) {
            element.remove();
        }
    }

    // Handle application form submission
    document.getElementById('rentalApplicationForm').addEventListener('submit', function(e) {
        if (!selectedProperty || !selectedProperty.id) {
            e.preventDefault();
            alert('Please select a property first');
            return false;
        }

        // Verify required fields
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const email = document.getElementById('email').value.trim();

        if (!firstName || !lastName || !email) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }
    });
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/public.php';
?>
