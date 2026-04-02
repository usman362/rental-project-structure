<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SOTELO MANAGEMENT LLC - Renter Portal">
    <meta name="csrf-token" content="<?= e(CSRF::generate()) ?>">
    <title><?= e($title) ?> - SOTELO MANAGEMENT LLC</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #333333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
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
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.2);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .company-name-header {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.5px;
        }

        .company-slogan-header {
            font-size: 13px;
            color: #666;
            margin-top: 2px;
            letter-spacing: 0.3px;
        }

        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2c5aa0, #3a6bc5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        .user-role {
            font-size: 12px;
            color: #666;
        }

        .logout-btn {
            background-color: #f8f9fa;
            color: #2c5aa0;
            border: 1px solid #ddd;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background-color: #2c5aa0;
            color: white;
            border-color: #2c5aa0;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #444;
            cursor: pointer;
            padding: 8px;
        }

        /* Main Container */
        .main-container {
            display: flex;
            max-width: 1400px;
            margin: 100px auto 40px;
            width: 100%;
            padding: 0 20px;
            gap: 20px;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 260px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 100px;
            flex-shrink: 0;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section h3 {
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-left: 10px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-links li {
            margin-bottom: 5px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            color: #444;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
            font-size: 14px;
        }

        .nav-links a:hover {
            background-color: #f0f7ff;
            color: #2c5aa0;
        }

        .nav-links a.active {
            background-color: #2c5aa0;
            color: white;
        }

        .nav-links a i {
            width: 20px;
            text-align: center;
        }

        .badge {
            background: #ef4444;
            color: white;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 11px;
            margin-left: auto;
        }

        /* Emergency Section */
        .emergency-section {
            background-color: #fff5f5;
            border: 2px solid #e74c3c;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .emergency-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #e74c3c;
            margin-bottom: 0.75rem;
        }

        .emergency-header h4 {
            margin: 0;
            font-size: 14px;
            color: #e74c3c;
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

        /* Main Content Area */
        .main-content {
            flex: 1;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        /* Flash Messages */
        .flash-message {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 14px;
        }

        .flash-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .flash-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .flash-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
        }

        .flash-close:hover {
            opacity: 1;
        }

        /* Footer */
        footer {
            background-color: #1a1a1a;
            color: #ddd;
            padding: 2.5rem 2rem;
            text-align: center;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
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

        .portal-links {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }

        .portal-links a {
            color: #aaa;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .portal-links a:hover {
            color: #2c5aa0;
        }

        .copyright {
            font-size: 14px;
            color: #aaa;
            margin-top: 1rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: static;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .user-menu {
                display: none;
                position: absolute;
                top: 100%;
                right: 0;
                background: white;
                padding: 1rem;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                border-radius: 8px;
                flex-direction: column;
                gap: 1rem;
            }

            .user-menu.active {
                display: flex;
            }

            .main-container {
                margin-top: 80px;
                flex-direction: column;
                padding: 0 10px;
            }

            .main-content {
                padding: 1rem;
            }

            .sidebar {
                width: 100%;
                position: static;
            }

            .portal-links {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo-container">
                <div class="logo-circle">SM</div>
                <div class="logo-text">
                    <div class="company-name-header">SOTELO MANAGEMENT LLC</div>
                    <div class="company-slogan-header">Renter Portal</div>
                </div>
            </div>

            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="user-menu" id="userMenu">
                <div class="user-info">
                    <div class="user-avatar"><?php
                        $fn = $user['first_name'] ?? '';
                        $ln = $user['last_name'] ?? '';
                        if ($fn && $ln) {
                            echo e(strtoupper(substr($fn, 0, 1) . substr($ln, 0, 1)));
                        } else {
                            echo e(strtoupper(substr($user['username'] ?? 'U', 0, 2)));
                        }
                    ?></div>
                    <div class="user-details">
                        <div class="user-name"><?= e(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></div>
                        <div class="user-role">Tenant</div>
                    </div>
                </div>
                <a href="<?= route('logout') ?>" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar Navigation -->
        <?php $currentTab = $_GET['tab'] ?? ''; ?>
        <nav class="sidebar">
            <div class="nav-section">
                <h3>Dashboard</h3>
                <ul class="nav-links">
                    <li><a href="<?= route('renter.portal') ?>" class="<?= ($active ?? '') === 'portal' && $currentTab === '' ? 'active' : '' ?>"><i class="fas fa-home"></i> Overview</a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=payments" class="<?= $currentTab === 'payments' ? 'active' : '' ?>"><i class="fas fa-credit-card"></i> Payments</a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=maintenance" class="<?= $currentTab === 'maintenance' ? 'active' : '' ?>"><i class="fas fa-tools"></i> Maintenance</a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=documents" class="<?= $currentTab === 'documents' ? 'active' : '' ?>"><i class="fas fa-file-alt"></i> Documents</a></li>
                </ul>
            </div>

            <div class="nav-section">
                <h3>Communication</h3>
                <ul class="nav-links">
                    <?php
                        // Get unread count for sidebar badges
                        $sidebarUnreadCount = $unreadNotifCount ?? 0;
                        if ($sidebarUnreadCount === 0 && !isset($unreadNotifCount)) {
                            $authUser = auth();
                            if ($authUser && isset($authUser['id'])) {
                                if (!class_exists('Notification')) {
                                    require_once BASE_PATH . '/app/Models/Notification.php';
                                }
                                $sidebarUnreadCount = \Notification::unreadCount((int)$authUser['id']);
                            }
                        }
                    ?>
                    <li><a href="<?= route('renter.portal') ?>?tab=messages" class="<?= $currentTab === 'messages' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Messages <?php if ($sidebarUnreadCount > 0): ?><span class="badge"><?= (int)$sidebarUnreadCount ?></span><?php endif; ?></a></li>
                    <li><a href="#" onclick="openNotificationsModal(); return false;" class="<?= $currentTab === 'notifications' ? 'active' : '' ?>"><i class="fas fa-bell"></i> Notifications <?php if ($sidebarUnreadCount > 0): ?><span class="badge"><?= (int)$sidebarUnreadCount ?></span><?php endif; ?></a></li>
                    <li><a href="<?= route('renter.portal') ?>?tab=emergency"><i class="fas fa-exclamation-triangle"></i> Emergency</a></li>
                </ul>
            </div>

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
        <main class="main-content">
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

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <div class="footer-company-name">SOTELO MANAGEMENT LLC</div>
                <div class="footer-slogan">Wealth. Realty. Management Servicer</div>
            </div>

            <div class="portal-links">
                <a href="<?= route('renter.portal') ?>">Dashboard</a>
                <a href="mailto:support@sotelomanage.com">Contact Support</a>
                <a href="tel:3072284667">(307) 228-4667</a>
            </div>

            <div class="copyright">
                &copy; 2026 SOTELO MANAGEMENT LLC. All rights reserved. | Renter Portal v2.1
            </div>
        </div>
    </footer>

    <!-- Notifications Modal -->
    <div id="notificationsModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:12px; max-width:550px; width:90%; max-height:80vh; display:flex; flex-direction:column; position:relative;">
            <!-- Modal Header -->
            <div style="display:flex; justify-content:space-between; align-items:center; padding:1.5rem 1.5rem 1rem; border-bottom:1px solid #eee;">
                <h2 style="font-size:1.3rem; font-weight:700; color:#333; margin:0;">Notifications</h2>
                <button onclick="closeNotificationsModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#999; padding:0; line-height:1;">&times;</button>
            </div>

            <!-- Modal Body -->
            <div style="overflow-y:auto; flex:1; padding:0;">
                <?php
                $notifList = $notifications ?? [];
                // If notifications not loaded, try to load them
                if (empty($notifList) && !isset($notifications)) {
                    $authUser = auth();
                    if ($authUser && isset($authUser['id'])) {
                        if (!class_exists('Notification')) {
                            require_once BASE_PATH . '/app/Models/Notification.php';
                        }
                        $notifList = \Notification::forUser((int)$authUser['id'], 20);
                    }
                }
                if (!empty($notifList)):
                    foreach ($notifList as $notif):
                        $notifIcon = 'fa-bell';
                        $notifIconColor = '#2c5aa0';
                        switch ($notif['type'] ?? 'info') {
                            case 'payment':
                                $notifIcon = ($notif['icon'] ?? '') === 'check-circle' ? 'fa-check-circle' : 'fa-bell';
                                $notifIconColor = ($notif['icon'] ?? '') === 'check-circle' ? '#10b981' : '#2c5aa0';
                                break;
                            case 'maintenance':
                                $notifIcon = 'fa-wrench';
                                $notifIconColor = '#6366f1';
                                break;
                            case 'message':
                                $notifIcon = 'fa-envelope';
                                $notifIconColor = '#2c5aa0';
                                break;
                            case 'info':
                                $notifIcon = 'fa-info-circle';
                                $notifIconColor = '#6b7280';
                                break;
                        }

                        // Time ago
                        $notifDate = !empty($notif['created_at']) ? new DateTime($notif['created_at']) : null;
                        $timeAgo = '';
                        if ($notifDate) {
                            $now = new DateTime();
                            $diff = $now->diff($notifDate);
                            if ($diff->days === 0) {
                                if ($diff->h === 0) {
                                    $timeAgo = max(1, $diff->i) . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
                                } else {
                                    $timeAgo = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
                                }
                            } elseif ($diff->days === 1) {
                                $timeAgo = '1 day ago';
                            } elseif ($diff->days < 7) {
                                $timeAgo = $diff->days . ' days ago';
                            } elseif ($diff->days < 30) {
                                $weeks = floor($diff->days / 7);
                                $timeAgo = $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
                            } else {
                                $timeAgo = $notifDate->format('M j, Y');
                            }
                        }

                        $isUnread = empty($notif['is_read']);
                        $notifLink = $notif['link'] ?? '#';
                ?>
                <a href="/renter/notifications/read?id=<?= (int)$notif['id'] ?>" style="display:flex; align-items:flex-start; gap:15px; padding:1.2rem 1.5rem; border-bottom:1px solid #f0f0f0; text-decoration:none; transition:background 0.2s; <?= $isUnread ? 'background:#f8faff;' : '' ?>" onmouseover="this.style.background='#f0f7ff'" onmouseout="this.style.background='<?= $isUnread ? '#f8faff' : 'white' ?>'">
                    <div style="width:40px; height:40px; border-radius:50%; background:<?= e($notifIconColor) ?>15; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas <?= e($notifIcon) ?>" style="color:<?= e($notifIconColor) ?>; font-size:16px;"></i>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:<?= $isUnread ? '700' : '600' ?>; color:#333; font-size:14px; margin-bottom:3px;">
                            <?= e($notif['title'] ?? 'Notification') ?>
                            <?php if ($isUnread): ?>
                                <span style="display:inline-block; width:8px; height:8px; background:#2c5aa0; border-radius:50%; margin-left:6px; vertical-align:middle;"></span>
                            <?php endif; ?>
                        </div>
                        <div style="color:#666; font-size:13px; line-height:1.4; margin-bottom:4px;"><?= e($notif['message'] ?? '') ?></div>
                        <div style="color:#999; font-size:12px;"><?= e($timeAgo) ?></div>
                    </div>
                </a>
                <?php
                    endforeach;
                else:
                ?>
                <div style="text-align:center; padding:3rem 1.5rem; color:#999;">
                    <i class="fas fa-bell-slash" style="font-size:2.5rem; margin-bottom:1rem; opacity:0.4;"></i>
                    <p style="font-size:15px;">No notifications</p>
                    <small>You're all caught up!</small>
                </div>
                <?php endif; ?>
            </div>

            <!-- Modal Footer -->
            <div style="display:flex; gap:10px; padding:1rem 1.5rem; border-top:1px solid #eee; justify-content:center;">
                <form method="POST" action="/renter/notifications/mark-all-read" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" style="background:none; border:1px solid #ddd; padding:10px 24px; border-radius:8px; font-size:14px; cursor:pointer; color:#333; font-weight:500; transition:all 0.2s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'">
                        Mark All as Read
                    </button>
                </form>
                <button onclick="closeNotificationsModal()" style="background:#2c5aa0; color:white; border:none; padding:10px 24px; border-radius:8px; font-size:14px; cursor:pointer; font-weight:500; transition:all 0.2s;" onmouseover="this.style.background='#1d3a6e'" onmouseout="this.style.background='#2c5aa0'">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
    function openNotificationsModal() {
        document.getElementById('notificationsModal').style.display = 'flex';
    }
    function closeNotificationsModal() {
        document.getElementById('notificationsModal').style.display = 'none';
    }
    // Close modal on background click
    document.getElementById('notificationsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeNotificationsModal();
        }
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        var mobileMenuToggle = document.getElementById('mobileMenuToggle');
        var userMenu = document.getElementById('userMenu');

        if (mobileMenuToggle && userMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                userMenu.classList.toggle('active');

                var icon = this.querySelector('i');
                if (userMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (userMenu && userMenu.classList.contains('active') &&
                !userMenu.contains(e.target) &&
                !mobileMenuToggle.contains(e.target)) {
                userMenu.classList.remove('active');
                var icon = mobileMenuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    });
    </script>
</body>
</html>
