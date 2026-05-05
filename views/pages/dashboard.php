<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - 7X Pharma Nexus</title>
    <meta name="description" content="7X Pharma Nexus Admin Dashboard - Sales analytics and inventory overview">

    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/logo/7X-PHARMA-ICO.png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
</head>

<body>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="dashboard-layout">

        <aside class="sidebar" id="sidebar" aria-label="Main navigation">
            <div class="sidebar-brand">
                <img src="<?= BASE_URL ?>/assets/images/logo/7X-PHARMA-ICO.png" alt="7x pharma logo">
                7X Pharma Nexus
            </div>

            <nav class="sidebar-nav" aria-label="Sidebar navigation">
                <p class="nav-section-label">Main</p>
                <a href="<?= BASE_URL ?>/dashboard" class="nav-item active" aria-current="page">
                    <i class="fa-solid fa-table-cells-large fa-fw"></i>
                    Dashboard
                </a>

                <a href="<?= BASE_URL ?>/pos" class="nav-item">
                    <i class="fa-solid fa-cash-register fa-fw"></i>
                    Point of Sale
                </a>

                <p class="nav-section-label">Management</p>

                <a href="<?= BASE_URL ?>/inventory" class="nav-item">
                    <i class="fa-solid fa-boxes-stacked fa-fw"></i>
                    Inventory
                    <span class="nav-badge"><?= $low_stock_count ?? 0 ?></span>
                </a>

                <a href="<?= BASE_URL ?>/credit" class="nav-item">
                    <i class="fa-solid fa-hand-holding-dollar fa-fw"></i>
                    Client Credit
                </a>

                <p class="nav-section-label">Reports</p>

                <a href="<?= BASE_URL ?>/reports/sales" class="nav-item">
                    <i class="fa-solid fa-chart-column fa-fw"></i>
                    Sales Reports
                </a>

                <a href="<?= BASE_URL ?>/reports/expiry" class="nav-item">
                    <i class="fa-solid fa-hourglass-half fa-fw"></i>
                    Expiry Tracker
                </a>

                <p class="nav-section-label">System</p>

                <a href="<?= BASE_URL ?>/settings" class="nav-item">
                    <i class="fa-solid fa-gear fa-fw"></i>
                    Settings
                </a>

                <a href="<?= BASE_URL ?>/auth/logout" class="nav-item" style="color: var(--color-danger);">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-fw"></i>
                    Logout
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
                        <div class="page-title">Dashboard</div>
                        <div class="breadcrumb">7X Pharma Nexus &rsaquo; <span>Overview</span></div>
                    </div>
                </div>
                <div class="navbar-right">
                    <span class="navbar-clock" id="navbar-clock"></span>
                    <button class="btn-notification" aria-label="Notifications">
                        <i class="fa-regular fa-bell"></i>
                        <span class="notification-dot"></span>
                    </button>
                    <button id="theme-toggle" class="btn-icon" aria-label="Toggle Theme">
                        <span id="theme-icon"></span>
                    </button>
                </div>
            </header>

            <main class="page-body" id="main-page-body">
                <section aria-label="Statistics overview">
                    <div class="stats-grid">
                        <div class="stat-card cyan">
                            <div class="stat-card-header">
                                <div class="stat-card-icon">
                                    <i class="fa-solid fa-coins"></i>
                                </div>
                                <span class="stat-card-trend trend-up">↑ 12.4%</span>
                            </div>
                            <div class="stat-card-value"><?= number_format($daily_revenue ?? 0, 2) ?></div>
                            <div class="stat-card-label">Today's Revenue (DH)</div>
                            <div class="stat-card-sub">Updated dynamically</div>
                        </div>

                        <div class="stat-card blue">
                            <div class="stat-card-header">
                                <div class="stat-card-icon">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <span class="stat-card-trend trend-up">↑ 8%</span>
                            </div>
                            <div class="stat-card-value"><?= number_format($daily_transactions ?? 0) ?></div>
                            <div class="stat-card-label">Transactions Today</div>
                            <div class="stat-card-sub">Completed sales</div>
                        </div>

                        <div class="stat-card yellow">
                            <div class="stat-card-header">
                                <div class="stat-card-icon">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <span class="stat-card-trend trend-down">Action Req</span>
                            </div>
                            <div class="stat-card-value"><?= number_format($low_stock_count ?? 0) ?></div>
                            <div class="stat-card-label">Low Stock Alerts</div>
                            <div class="stat-card-sub">Requires immediate reorder</div>
                        </div>

                        <div class="stat-card red">
                            <div class="stat-card-header">
                                <div class="stat-card-icon">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                </div>
                                <span class="stat-card-trend trend-down">Pending</span>
                            </div>
                            <div class="stat-card-value"><?= number_format($unpaid_credit ?? 0, 2) ?></div>
                            <div class="stat-card-label">Unpaid Credit (DH)</div>
                            <div class="stat-card-sub">Pending client payments</div>
                        </div>
                    </div>
                </section>

                <div class="dashboard-row two-col">
                    <div class="panel">
                        <div class="panel-header">
                            <h2 class="panel-title">
                                <i class="fa-solid fa-chart-line"></i>
                                Weekly Sales Overview
                            </h2>
                            <div class="panel-actions">
                                <a href="#" class="btn btn-primary btn-sm">This Week</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="chart-wrapper">
                                <canvas id="sales-chart" aria-label="Weekly sales bar chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-header">
                            <h2 class="panel-title">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                Low Stock Alerts
                            </h2>
                            <a href="<?= BASE_URL ?>/inventory" class="btn btn-outline btn-sm">View All</a>
                        </div>
                        <div class="panel-body" style="padding: var(--space-3);">
                            <div class="stock-list">
                                <?php if (isset($low_stock_items) && count($low_stock_items) > 0): ?>
                                    <?php foreach ($low_stock_items as $item): ?>
                                        <div class="stock-item">
                                            <div class="stock-item-icon">💊</div>
                                            <div class="stock-item-info">
                                                <div class="stock-item-name"><?= htmlspecialchars($item['name']) ?></div>
                                                <div class="stock-item-category"><?= htmlspecialchars($item['category'] ?? 'Medicine') ?></div>
                                            </div>
                                            <div class="stock-qty <?= $item['current_quantity'] <= 3 ? 'critical' : '' ?>"><?= $item['current_quantity'] ?> left</div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div style="text-align: center; color: var(--text-muted); padding: 1rem 0;">All stock levels are optimal.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-row full-col">
                    <div class="panel">
                        <div class="panel-header">
                            <h2 class="panel-title">
                                <i class="fa-solid fa-clipboard-list"></i>
                                Recent Sales
                            </h2>
                            <div class="panel-actions">
                                <a href="#" class="btn btn-outline btn-sm">Export CSV</a>
                                <a href="<?= BASE_URL ?>/reports/sales" class="btn btn-primary btn-sm">View All</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="data-table" aria-label="Recent sales table">
                                <thead>
                                    <tr>
                                        <th>Receipt #</th>
                                        <th>Date & Time</th>
                                        <th>Client</th>
                                        <th>Items</th>
                                        <th>Payment</th>
                                        <th>Amount (DH)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($recent_sales) && count($recent_sales) > 0): ?>
                                        <?php foreach ($recent_sales as $sale): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($sale['receipt_number']) ?></strong></td>
                                                <td><?= htmlspecialchars($sale['created_at']) ?></td>
                                                <td><?= htmlspecialchars($sale['client_name'] ?? 'Walk-in Customer') ?></td>
                                                <td><?= htmlspecialchars($sale['items_count']) ?> items</td>
                                                <td><span class="pay-tag"><?= htmlspecialchars($sale['payment_method']) ?></span></td>
                                                <td class="amount-col"><?= number_format($sale['total_amount'], 2) ?></td>
                                                <td>
                                                    <span class="badge <?= strtolower($sale['status']) === 'paid' ? 'badge-success' : (strtolower($sale['status']) === 'pending' ? 'badge-warning' : 'badge-danger') ?>">
                                                        <?= htmlspecialchars($sale['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">No recent sales found today.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>

</body>

</html>