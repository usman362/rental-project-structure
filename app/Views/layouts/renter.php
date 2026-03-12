<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SOTELO MANAGEMENT - Renter Portal">
    <meta name="csrf-token" content="<?= e(CSRF::generate()) ?>">
    <title><?= e($title) ?> - SOTELO MANAGEMENT</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= route('assets.css', 'app.css') ?>">

    <!-- Renter Sidebar Styles -->
    <style>
        .nav-section h3 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            padding: 0 1.5rem;
            margin: 1.5rem 0 0.5rem;
        }
        .badge {
            background: #ef4444;
            color: white;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 11px;
            margin-left: auto;
        }
        .emergency-section {
            margin: 2rem 1rem;
            padding: 1rem;
            border: 2px solid #fee2e2;
            border-radius: 8px;
            background: #fff5f5;
        }
        .emergency-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ef4444;
            margin-bottom: 0.75rem;
        }
        .emergency-header h4 {
            margin: 0;
            font-size: 14px;
        }
        .emergency-header i {
            font-size: 16px;
        }
        .emergency-contact {
            margin-bottom: 0.5rem;
        }
        .emergency-contact .contact-label {
            font-size: 12px;
            color: #666;
        }
        .emergency-contact .contact-value {
            font-weight: 600;
            color: #333;
        }
        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .nav-links li a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
        }
        .nav-links li a:hover {
            background: #f0f7ff;
            color: #2c5aa0;
        }
        .nav-links li a.active {
            background: #2c5aa0;
            color: white;
            border-radius: 6px;
            margin: 0 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Public Header -->
    <header class="admin-header">
        <div class="logo-container">
            <div class="logo-circle">SM</div>
            <div>
                <div class="company-name">SOTELO MANAGEMENT</div>
                <div style="font-size: 12px; color: #666;">Property Management Solutions</div>
            </div>
        </div>
        <nav style="display: flex; gap: 2rem; align-items: center;">
            <a href="<?= route('renter.portal') ?>" style="text-decoration: none; color: #333; font-weight: 500;">Home</a>
            <a href="<?= route('application') ?>" style="text-decoration: none; color: #333; font-weight: 500;">Application</a>
            <a href="<?= route('renter.portal') ?>" style="text-decoration: none; color: #333; font-weight: 500;">Portal</a>
            <a href="<?= route('logout') ?>" style="text-decoration: none; color: #ef4444; font-weight: 500;">Logout</a>
        </nav>
    </header>

    <!-- Main Container -->
    <div class="admin-container">
        <!-- Renter Sidebar -->
        <nav class="admin-sidebar">
            <!-- Dashboard Section -->
            <div class="nav-section">
                <h3>Dashboard</h3>
                <ul class="nav-links">
                    <li><a href="<?= route('renter.portal') ?>" class="<?= ($active ?? '') === 'portal' ? 'active' : '' ?>"><i class="fas fa-home"></i> Overview</a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=payments" class="<?= ($active ?? '') === 'payments' ? 'active' : '' ?>"><i class="fas fa-credit-card"></i> Payments</a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=maintenance" class="<?= ($active ?? '') === 'maintenance' ? 'active' : '' ?>"><i class="fas fa-tools"></i> Maintenance</a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=documents" class="<?= ($active ?? '') === 'documents' ? 'active' : '' ?>"><i class="fas fa-file-alt"></i> Documents</a></li>
                </ul>
            </div>

            <!-- Communication Section -->
            <div class="nav-section">
                <h3>Communication</h3>
                <ul class="nav-links">
                    <li><a href="<?= route('renter.portal') ?>?tab=messages"><i class="fas fa-envelope"></i> Messages <span class="badge">3</span></a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=notifications"><i class="fas fa-bell"></i> Notifications</a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=emergency"><i class="fas fa-exclamation-triangle"></i> Emergency</a></li>
                </ul>
            </div>

            <!-- Account Section -->
            <div class="nav-section">
                <h3>Account</h3>
                <ul class="nav-links">
                    <li><a href="<?= route('renter.profile') ?>" class="<?= ($active ?? '') === 'profile' ? 'active' : '' ?>"><i class="fas fa-user"></i> My Profile</a></li>
                    <li><a href="<?= route('renter.settings') ?>" class="<?= ($active ?? '') === 'settings' ? 'active' : '' ?>"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="<?= route('renter.help') ?>" class="<?= ($active ?? '') === 'help' ? 'active' : '' ?>"><i class="fas fa-question-circle"></i> Help Center</a></li>
                </ul>
            </div>

            <!-- Emergency Contact -->
            <div class="emergency-section">
                <div class="emergency-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>Emergency Contact</h4>
                </div>
                <div class="emergency-contacts">
                    <div class="emergency-contact">
                        <div class="contact-label">Property Manager</div>
                        <div class="contact-value">(307) 228-4667</div>
                    </div>
                    <div class="emergency-contact">
                        <div class="contact-label">24/7 Emergency</div>
                        <div class="contact-value">(307) 228-4667</div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="admin-content">
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

            <!-- Page Content -->
            <?= $content ?>
        </main>
    </div>

</body>
</html>
