<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SOTELO MANAGEMENT - Property Rental Application">
    <meta name="csrf-token" content="<?= e(CSRF::generate()) ?>">
    <title><?= e($title) ?> - SOTELO MANAGEMENT</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= route('assets.css', 'app.css') ?>">

    <style>
        /* Public page specific styles */
        body {
            padding-top: 80px;
        }

        .public-header {
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2c5aa0 0%, #1d3a6e 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .company-name-header {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .company-slogan-header {
            font-size: 12px;
            color: #666;
            font-weight: 400;
        }

        .public-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #2c5aa0;
        }

        .client-portal-btn {
            background: #2c5aa0;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
        }

        .client-portal-btn:hover {
            background: #1d4a8a;
        }

        .client-portal-btn i {
            font-size: 18px;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            padding: 0.5rem;
        }

        .mobile-menu-toggle:hover {
            color: #2c5aa0;
        }

        .public-footer {
            background: #1a1a1a;
            color: #fff;
            padding: 2rem;
            margin-top: 3rem;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .footer-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-company-name {
            font-size: 22px;
            font-weight: 700;
            color: white;
        }

        .footer-slogan {
            font-size: 14px;
            color: #aaa;
        }

        .copyright {
            font-size: 14px;
            color: #aaa;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .social-links a {
            color: #aaa;
            text-decoration: none;
            font-size: 20px;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: #2c5aa0;
        }

        .public-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 200px);
        }

        /* Mobile menu toggle responsive */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
            }

            body {
                padding-top: 70px;
            }

            .public-header {
                padding: 0.75rem 1.5rem;
            }

            .footer-container {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Public Header -->
    <header class="public-header">
        <div class="header-container">
            <div class="logo-container">
                <div class="logo-circle">SM</div>
                <div class="logo-text">
                    <div class="company-name-header">SOTELO MANAGEMENT LLC</div>
                    <div class="company-slogan-header">Wealth. Realty. Management Servicer</div>
                </div>
            </div>
            <button class="mobile-menu-toggle" id="mobileMenuToggle"><i class="fas fa-bars"></i></button>
            <nav class="public-nav" id="mainNav">
                <ul class="nav-links">
                    <li><a href="<?= route('home') ?>">HOME</a></li>
                    <li><a href="<?= route('rental-application') ?>">RENTAL APPLICATION</a></li>
                    <li><button class="client-portal-btn" id="openModal"><i class="fas fa-user-circle"></i> Client Portal</button></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php $flash = $_SESSION['_flash'] ?? []; unset($_SESSION['_flash']); ?>
    <?php if (!empty($flash)): ?>
        <?php foreach ($flash as $type => $messages): ?>
            <?php if (is_array($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="flash-message flash-<?= e($type) ?>">
                        <i class="fas fa-<?= $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
                        <span><?= e($message) ?></span>
                        <button class="flash-close" onclick="this.parentElement.style.display='none';">&times;</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="flash-message flash-<?= e($type) ?>">
                    <i class="fas fa-<?= $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
                    <span><?= e($messages) ?></span>
                    <button class="flash-close" onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="public-content">
        <?= $content ?>
    </div>

    <!-- Footer -->
    <footer class="public-footer">
        <div class="footer-container">
            <div class="footer-logo">
                <div class="footer-company-name">SOTELO MANAGEMENT LLC</div>
                <div class="footer-slogan">Wealth. Realty. Management Servicer</div>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> SOTELO MANAGEMENT LLC. All rights reserved.
            </div>
            <div class="social-links">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>
