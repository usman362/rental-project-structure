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
        .public-header {
            background: #ffffff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .public-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .public-nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .public-nav a:hover {
            color: #2c5aa0;
        }

        .portal-btn {
            background: #2c5aa0;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .portal-btn:hover {
            background: #1d4a8a;
        }

        .public-footer {
            background: #1a1a1a;
            color: #fff;
            padding: 2rem;
            margin-top: 3rem;
            text-align: center;
        }

        .public-footer p {
            margin: 0.5rem 0;
            font-size: 14px;
        }

        .public-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 200px);
        }
    </style>
</head>
<body>
    <!-- Public Header -->
    <header class="public-header">
        <div class="logo-container">
            <div class="logo-circle">SM</div>
            <div>
                <div class="company-name">SOTELO MANAGEMENT</div>
                <div style="font-size: 12px; color: #666;">Property Management Solutions</div>
            </div>
        </div>
        <nav class="public-nav">
            <a href="<?= route('home') ?>">HOME</a>
            <a href="<?= route('rental-application') ?>">RENTAL APPLICATION</a>
            <a href="<?= route('auth.login') ?>" class="portal-btn">CLIENT PORTAL</a>
        </nav>
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
        <p><strong>SOTELO MANAGEMENT LLC</strong></p>
        <p>Professional Property Management Services</p>
        <p>&copy; <?= date('Y') ?> SOTELO MANAGEMENT LLC. All rights reserved.</p>
    </footer>

</body>
</html>
