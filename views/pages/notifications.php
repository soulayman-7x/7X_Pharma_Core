<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Notifications - 7X Pharma Nexus') ?></title>
    <meta name="description" content="7X Pharma Nexus - System Notifications and Alerts">

    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/logo/7X-PHARMA-ICO.png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/notifications.css">
</head>

<body>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <div class="dashboard-layout">
        <aside class="sidebar" id="sidebar" aria-label="Main navigation">
            <div class="sidebar-brand">
                <img src="<?= BASE_URL ?>/assets/images/logo/7X-PHARMA-ICO.png" alt="7x pharma logo">
                <span class="nav-text">7X Pharma Nexus</span>
            </div>
            <nav class="sidebar-nav" aria-label="Sidebar navigation">
                <p class="nav-section-label">Main</p>
                <a href="<?= BASE_URL ?>/dashboard" class="nav-item">
                    <i class="fa-solid fa-table-cells-large fa-fw"></i><span class="nav-text">Dashboard</span>
                </a>
                <a href="<?= BASE_URL ?>/pos" class="nav-item">
                    <i class="fa-solid fa-cash-register fa-fw"></i><span class="nav-text">Point of Sale</span>
                </a>
                <p class="nav-section-label">Management</p>
                <a href="<?= BASE_URL ?>/inventory" class="nav-item">
                    <i class="fa-solid fa-boxes-stacked fa-fw"></i><span class="nav-text">Inventory</span>
                </a>
                <a href="<?= BASE_URL ?>/credit" class="nav-item">
                    <i class="fa-solid fa-hand-holding-dollar fa-fw"></i><span class="nav-text">Client Credit</span>
                </a>
                <p class="nav-section-label">System</p>
                <a href="<?= BASE_URL ?>/auth/logout" class="nav-item" style="color: var(--color-danger);">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-fw"></i><span class="nav-text">Logout</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="user-avatar"><?= strtoupper(substr($_SESSION['name'] ?? 'AD', 0, 2)) ?></div>
                    <div class="user-info">
                        <div class="user-name"><?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></div>
                        <div class="user-role"><?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'Administrator')) ?></div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="main-content">
            <header class="top-navbar">
                <div class="navbar-left">
                    <button class="btn-sidebar-toggle" id="btn-sidebar-toggle" aria-label="Toggle sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <div class="page-title">System Alerts</div>
                        <div class="breadcrumb">7X Pharma Nexus &rsaquo; <span>Notifications</span></div>
                    </div>
                </div>
                <div class="navbar-right">
                    <span class="navbar-clock" id="navbar-clock"></span>
                    
                    <a href="<?= BASE_URL ?>/notification" class="btn-notification" aria-label="Notifications">
                        <i class="fa-regular fa-bell"></i>
                            <span class="notification-dot active"></span>
                    </a>

                    <button id="theme-toggle" class="btn-icon" aria-label="Toggle Theme">
                        <span id="theme-icon"></span>
                    </button>
                </div>
            </header>

            <main class="page-body" id="main-page-body">
                <div class="notifications-page-container">
                    
                    <div class="panel" style="background: transparent; border: none; box-shadow: none;">
                        <div class="panel-header" style="border: none; padding-bottom: 0;">
                            <h2 class="panel-title" style="font-size: 1.5rem; margin-bottom: 1.5rem;">
                                <i class="fa-solid fa-bell" style="color: var(--color-accent);"></i>
                                All Notifications (<?= $totalAlerts ?? 0 ?>)
                            </h2>
                        </div>
                        
                        <div class="panel-body">
                            
                            <?php if (empty($lowStock) && empty($expiring)): ?>
                                <div class="empty-notifications-card">
                                    <i class="fa-solid fa-shield-check" style="font-size: 3rem; color: var(--color-success); margin-bottom: 1rem; display: block;"></i>
                                    <h3 style="color: var(--color-text-primary); margin-bottom: 0.5rem;">System Optimal</h3>
                                    <p style="color: var(--color-text-secondary);">There are currently no active alerts for stock or expiry.</p>
                                </div>
                            <?php else: ?>

                                <?php if (!empty($lowStock)): ?>
                                    <h3 style="color: var(--color-primary); margin-bottom: 1rem; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                        <i class="fa-solid fa-boxes-stacked"></i> Low Stock Alerts
                                    </h3>
                                    <?php foreach ($lowStock as $item): ?>
                                        <div class="alert-card alert-stock">
                                            <div class="notification-icon stock">
                                                <i class="fa-solid fa-box-open"></i>
                                            </div>
                                            <div class="alert-details">
                                                <h4><?= htmlspecialchars($item['medicine_name']) ?></h4>
                                                <p>Batch: <strong><?= htmlspecialchars($item['batch_number']) ?></strong> &mdash; Only <span style="color:var(--color-primary); font-weight:700;"><?= $item['current_quantity'] ?></span> units remaining.</p>
                                            </div>
                                            <div class="alert-action">
                                                <a href="<?= BASE_URL ?>/inventory" class="btn btn-outline btn-sm">Restock</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if (!empty($lowStock) && !empty($expiring)) echo '<div style="height: 2rem;"></div>'; ?>

                                <?php if (!empty($expiring)): ?>
                                    <h3 style="color: var(--color-accent); margin-bottom: 1rem; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Expiry Alerts
                                    </h3>
                                    <?php foreach ($expiring as $item): ?>
                                        <div class="alert-card alert-expiry">
                                            <div class="notification-icon expiry">
                                                <i class="fa-solid fa-hourglass-end"></i>
                                            </div>
                                            <div class="alert-details">
                                                <h4><?= htmlspecialchars($item['medicine_name']) ?></h4>
                                                <p>Batch: <strong><?= htmlspecialchars($item['batch_number']) ?></strong> &mdash; Expires on <span style="color:var(--color-danger); font-weight:700;"><?= date('M d, Y', strtotime($item['expiry_date'])) ?></span>.</p>
                                            </div>
                                            <div class="alert-action">
                                                <a href="<?= BASE_URL ?>/inventory" class="btn btn-outline btn-sm">Manage</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            <?php endif; ?>

                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>

</body>
</html>
