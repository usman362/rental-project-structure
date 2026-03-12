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
            <div class="sidebar-title">Dashboard</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= route('renter.portal') ?>" class="<?= ($active === 'overview' || $active === 'portal') ? 'active' : '' ?>">
                        <i class="fas fa-chart-line"></i> Overview
                    </a>
                </li>
            </ul>

            <div class="sidebar-title">Account</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= route('renter.profile') ?>" class="<?= $active === 'profile' ? 'active' : '' ?>">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                </li>
                <li>
                    <a href="<?= route('renter.settings') ?>" class="<?= $active === 'settings' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="<?= route('renter.help') ?>" class="<?= $active === 'help' ? 'active' : '' ?>">
                        <i class="fas fa-question-circle"></i> Help Center
                    </a>
                </li>
            </ul>

            <div class="sidebar-title">Actions</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= route('auth.logout') ?>">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
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
