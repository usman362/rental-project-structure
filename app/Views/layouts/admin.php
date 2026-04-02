<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SOTELO MANAGEMENT LLC - Admin Dashboard">
    <meta name="csrf-token" content="<?= e(CSRF::generate()) ?>">
    <title><?= e($title) ?> - SOTELO MANAGEMENT</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= route('assets.css', 'app.css') ?>">
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="logo-container">
            <div class="logo-circle">SM</div>
            <div class="company-name">SOTELO MANAGEMENT LLC</div>
        </div>
        <div class="admin-user">
            <span><?php
                $adminDisplayName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                echo e($adminDisplayName ?: 'Admin User');
            ?></span>
            <div class="admin-avatar"><?php
                $adminInitial = strtoupper(substr($user['first_name'] ?? 'A', 0, 1));
                echo e($adminInitial);
            ?></div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <nav class="admin-sidebar">
            <div class="sidebar-title">Main Navigation</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= route('admin.dashboard') ?>" class="<?= $active === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= route('admin.renters') ?>" class="<?= $active === 'renters' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> Renters
                    </a>
                </li>
                <li>
                    <a href="<?= route('admin.applications') ?>" class="<?= $active === 'applications' ? 'active' : '' ?>">
                        <i class="fas fa-file-alt"></i> Applications
                    </a>
                </li>
                <li>
                    <a href="<?= route('admin.properties') ?>" class="<?= $active === 'properties' ? 'active' : '' ?>">
                        <i class="fas fa-home"></i> Properties
                    </a>
                </li>
                <li>
                    <a href="<?= route('admin.payments') ?>" class="<?= $active === 'payments' ? 'active' : '' ?>">
                        <i class="fas fa-credit-card"></i> Payments
                    </a>
                </li>
                <li>
                    <a href="<?= route('admin.maintenance') ?>" class="<?= $active === 'maintenance' ? 'active' : '' ?>">
                        <i class="fas fa-tools"></i> Maintenance
                    </a>
                </li>
                <li>
                    <a href="<?= route('admin.reports') ?>" class="<?= $active === 'reports' ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </li>
            </ul>

            <div class="sidebar-title">System</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= route('admin.settings') ?>" class="<?= $active === 'settings' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
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
