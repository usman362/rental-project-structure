<?php
$title = 'Home';
ob_start();
?>

<style>
    /* Home Page Styles */
    .hero-content {
        width: 100%;
    }

    .company-hero {
        margin-bottom: 3rem;
    }

    .company-name-hero {
        font-size: 4rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1rem;
        line-height: 1.1;
        letter-spacing: 1px;
        font-family: 'Montserrat', sans-serif;
    }

    .company-slogan-hero {
        font-size: 1.8rem;
        color: #666;
        font-weight: 400;
        letter-spacing: 0.5px;
        margin-top: 0;
    }

    .contact-section {
        background-color: #f9f9f9;
        padding: 3rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        max-width: 800px;
        margin: 0 auto;
        text-align: left;
    }

    .contact-section h2 {
        color: #2c5aa0;
        margin-bottom: 1.5rem;
        font-size: 1.8rem;
        font-weight: 600;
        text-align: center;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .contact-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
    }

    .contact-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #2c5aa0, #3a6bc5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    .contact-details {
        display: flex;
        flex-direction: column;
    }

    .contact-label {
        font-size: 14px;
        color: #777;
        margin-bottom: 4px;
    }

    .contact-value {
        font-size: 16px;
        color: #333;
        font-weight: 500;
    }

    .contact-value a {
        color: #2c5aa0;
        text-decoration: none;
        transition: color 0.3s;
    }

    .contact-value a:hover {
        color: #1d4a8a;
        text-decoration: underline;
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
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease-out;
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
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #444;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: border 0.3s, box-shadow 0.3s;
    }

    .form-group input:focus {
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

    /* Main content wrapper */
    main {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 60px 20px 60px;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .company-name-hero {
            font-size: 3.2rem;
        }

        .company-slogan-hero {
            font-size: 1.5rem;
        }

        .contact-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .company-name-hero {
            font-size: 2.5rem;
        }

        .company-slogan-hero {
            font-size: 1.3rem;
        }

        .contact-section {
            padding: 2rem;
        }

        .contact-item {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }

        .contact-details {
            align-items: center;
        }

        main {
            padding: 40px 15px;
        }
    }

    @media (max-width: 480px) {
        .company-name-hero {
            font-size: 2rem;
        }

        .company-slogan-hero {
            font-size: 1.1rem;
        }

        .contact-section {
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }
    }
</style>

<main>
    <div class="hero-content">
        <!-- Company Hero Section -->
        <div class="company-hero">
            <h1 class="company-name-hero">SOTELO MANAGEMENT LLC</h1>
            <p class="company-slogan-hero">Wealth. Realty. Management Servicer</p>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h2>Contact Information</h2>
            <div class="contact-grid">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <div class="contact-label">Email</div>
                        <div class="contact-value">
                            <a href="mailto:support@sotelomanage.com">support@sotelomanage.com</a>
                        </div>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="contact-details">
                        <div class="contact-label">Website</div>
                        <div class="contact-value">
                            <a href="https://www.sotelomanage.com/" target="_blank">www.sotelomanage.com</a>
                        </div>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-details">
                        <div class="contact-label">Phone</div>
                        <div class="contact-value">307-228-4667</div>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-details">
                        <div class="contact-label">Address</div>
                        <div class="contact-value">130 N. Gould St. STE 100<br>Sheridan, WY 82801</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Client Portal Login Modal -->
<div class="modal-overlay" id="loginModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Client Portal Login</h2>
            <button class="close-modal" id="closeModal">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= route('login') ?>">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username or email"
                        value="<?= e(old('username')) ?>"
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
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
            <div style="margin-top: 1rem; padding: 1rem; background: #f0f7ff; border-radius: 8px; font-size: 13px;">
                <strong>Demo credentials:</strong><br>
                Renter: username: <strong>test</strong><br>
                Admin: username: <strong>admin</strong><br>
                Password for both: <strong>password</strong>
            </div>
        </div>
    </div>
</div>

<script>
    // Get modal elements
    const openModalBtn = document.getElementById('openModal') || document.querySelector('.client-portal-btn') || document.querySelector('.portal-btn') || document.querySelector('[data-open-login]');
    const closeModalBtn = document.getElementById('closeModal');
    const loginModal = document.getElementById('loginModal');

    // Open modal when Client Portal button is clicked
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close modal
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            loginModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    }

    // Close modal when clicking outside
    loginModal.addEventListener('click', function(e) {
        if (e.target === loginModal) {
            loginModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && loginModal.classList.contains('active')) {
            loginModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/app/Views/layouts/public.php';
?>
