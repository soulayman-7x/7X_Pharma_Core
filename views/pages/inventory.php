<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory — 7X Pharma Nexus</title>
    <meta name="description" content="Medicine inventory, batches, and expiry date management.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/inventory.css">
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
                <a href="<?= BASE_URL ?>/dashboard" class="nav-item" aria-current="page">
                    <i class="fa-solid fa-table-cells-large fa-fw"></i>
                    <span class="nav-text">Dashboard</span>
                </a>

                <a href="<?= BASE_URL ?>/pos" class="nav-item">
                    <i class="fa-solid fa-cash-register fa-fw"></i>
                    <span class="nav-text">Point of Sale</span>
                </a>

                <p class="nav-section-label">Management</p>

                <a href="<?= BASE_URL ?>/inventory" class="nav-item active">
                    <i class="fa-solid fa-boxes-stacked fa-fw"></i>
                    <span class="nav-text">Inventory</span>
                    <span class="nav-badge"><?= $low_stock_count ?? 0 ?></span>
                </a>

                <a href="<?= BASE_URL ?>/credit" class="nav-item">
                    <i class="fa-solid fa-hand-holding-dollar fa-fw"></i>
                    <span class="nav-text">Client Credit</span>
                </a>

                <p class="nav-section-label">System</p>

                <a href="<?= BASE_URL ?>/auth/logout" class="nav-item" style="color: var(--color-danger);">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-fw"></i>
                    <span class="nav-text">Logout</span>
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
                        <div class="page-title">Inventory</div>
                        <div class="breadcrumb">7X Pharma Nexus &rsaquo; <span>Medicines & Batches</span></div>
                    </div>
                </div>
                <div class="navbar-right">
                    <span class="navbar-clock" id="navbar-clock"></span>
                    <button id="theme-toggle" class="btn-icon" aria-label="Toggle Theme">
                        <span id="theme-icon"></span>
                    </button>
                    <button class="btn btn-primary btn-sm" id="btn-open-add-modal" aria-label="Add new medicine">
                        <i class="fa-solid fa-plus"></i>
                        Add Medicine
                    </button>
                </div>
            </header>

            <main class="page-body">

                <div class="panel">
                    <div class="panel-body" style="padding: var(--space-3);">
                        <form method="GET" action="<?= BASE_URL ?>/inventory" class="inv-filter-bar" id="filter-form" role="search">
                            <div class="search-input-wrap">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="search" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search by name, barcode..." aria-label="Search medicines">
                            </div>
                            <select name="category" aria-label="Filter by category">
                                <option value="all">All Categories</option>
                                <option value="analgesic" <?= (isset($_GET['category']) && $_GET['category'] == 'analgesic') ? 'selected' : '' ?>>Analgesics</option>
                                <option value="antibiotic" <?= (isset($_GET['category']) && $_GET['category'] == 'antibiotic') ? 'selected' : '' ?>>Antibiotics</option>
                                <option value="vitamin" <?= (isset($_GET['category']) && $_GET['category'] == 'vitamin') ? 'selected' : '' ?>>Vitamins</option>
                                <option value="cardiac" <?= (isset($_GET['category']) && $_GET['category'] == 'cardiac') ? 'selected' : '' ?>>Cardiac</option>
                                <option value="derma" <?= (isset($_GET['category']) && $_GET['category'] == 'derma') ? 'selected' : '' ?>>Dermatology</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            <a href="<?= BASE_URL ?>/inventory" class="btn btn-outline btn-sm">Reset</a>
                        </form>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title">
                            <i class="fa-solid fa-pills"></i>
                            Medicine Inventory
                        </h2>
                        <div class="panel-actions">
                            <span style="font-size:.82rem;color:var(--color-text-secondary);"><?= count($medicines ?? []) ?> medicines</span>
                            <a href="#" class="btn btn-outline btn-sm">
                                <i class="fa-solid fa-download"></i> Export CSV
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table" aria-label="Medicine inventory table">
                            <thead>
                                <tr>
                                    <th>Barcode</th>
                                    <th>Medicine Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Status</th>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($medicines)): ?>
                                    <?php foreach ($medicines as $med): ?>
                                        <?php
                                        $qty = $med['current_quantity'];
                                        if ($qty <= 0) {
                                            $statusClass = 'badge-danger';
                                            $statusText = 'Out of Stock';
                                            $barClass = 'empty';
                                            $barWidth = 0;
                                        } elseif ($qty <= 10) {
                                            $statusClass = 'badge-warning';
                                            $statusText = 'Low Stock';
                                            $barClass = 'low';
                                            $barWidth = min(100, ($qty / 10) * 100);
                                        } else {
                                            $statusClass = 'badge-success';
                                            $statusText = 'In Stock';
                                            $barClass = '';
                                            $barWidth = min(100, ($qty / 50) * 100);
                                        }
                                        ?>
                                        <tr>
                                            <td class="barcode-cell"><?= htmlspecialchars($med['barcode'] ?? 'N/A') ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($med['name']) ?></strong><br>
                                                <small style="color:var(--color-text-secondary)"><?= htmlspecialchars($med['dci'] ?? '') ?></small>
                                            </td>
                                            <td><span class="badge badge-info"><?= htmlspecialchars(ucfirst($med['category'] ?? 'General')) ?></span></td>

                                            <td>
                                                <div class="qty-bar-wrap">
                                                    <span><?= $qty ?></span>
                                                    <div class="qty-bar-bg">
                                                        <div class="qty-bar-fill <?= $barClass ?>" style="width:<?= $barWidth ?>%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="amount-col"><?= number_format($med['price'], 2) ?> DH</td>
                                            <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                            <td>
                                                <button class="btn-table-action btn-edit"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                                <a href="<?= BASE_URL ?>/inventory/delete/<?= $med['id'] ?>"
                                                    class="btn-table-action btn-del"
                                                    style="text-decoration: none; display: inline-block;"
                                                    onclick="return confirm('Are you sure you want to delete <?= addslashes($med['name']) ?>?');">
                                                    <i class="fa-solid fa-trash-can"></i> Delete
                                                </a>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center; padding: 2rem; color:var(--text-muted);">No medicines found in the inventory.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <div class="modal-overlay" id="add-medicine-modal" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="add-med-title">
        <div class="modal-box" style="max-width:560px;">
            <div class="modal-header">
                <h2 id="add-med-title">Add New Medicine</h2>
                <button class="modal-close" id="close-add-modal" aria-label="Close">&times;</button>
            </div>
            <form id="add-medicine-form" method="POST" action="<?= BASE_URL ?>/inventory/add">
                <div class="modal-form">
                    <div class="modal-form-row">
                        <div class="form-group">
                            <label class="form-label" for="med-name">Medicine Name *</label>
                            <input type="text" id="med-name" name="name" class="form-control" placeholder="e.g. Paracetamol 500mg" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="med-barcode">Barcode</label>
                            <input type="text" id="med-barcode" name="barcode" class="form-control" placeholder="Scan or type barcode">
                        </div>
                    </div>

                    <div class="modal-form-row">
                        <div class="form-group">
                            <label class="form-label" for="med-category">Category *</label>
                            <select id="med-category" name="category" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="analgesic">Analgesic</option>
                                <option value="antibiotic">Antibiotic</option>
                                <option value="vitamin">Vitamin</option>
                                <option value="cardiac">Cardiac</option>
                                <option value="derma">Dermatology</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="med-dci">DCI / Dosage Form</label>
                            <input type="text" id="med-dci" name="dci" class="form-control" placeholder="e.g. 500mg Tabs">
                        </div>
                    </div>

                    <div class="modal-form-row">
                        <div class="form-group">
                            <label class="form-label" for="med-batch">Batch Number *</label>
                            <input type="text" id="med-batch" name="batch" class="form-control" placeholder="e.g. BT-2026-001" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="med-expiry">Expiry Date *</label>
                            <input type="month" id="med-expiry" name="expiry_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-form-row">
                        <div class="form-group">
                            <label class="form-label" for="med-qty">Initial Quantity *</label>
                            <input type="number" id="med-qty" name="quantity" class="form-control" min="0" placeholder="0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="med-price">Unit Price (DH) *</label>
                            <input type="number" id="med-price" name="price" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="modal-form-row">
                        <div class="form-group">
                            <label class="form-label" for="med-cost-price">Cost Price (DH)</label>
                            <input type="number" id="med-cost-price" name="cost_price" class="form-control" min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-add-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Medicine</button>
                </div>
            </form>
        </div>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/toast.js"></script>

    <?php if (isset($_GET['status'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if ($_GET['status'] === 'added'): ?>
                    showToast('Medicine added successfully!', 'success');
                <?php elseif ($_GET['status'] === 'updated'): ?>
                    showToast('Medicine updated successfully!', 'success');
                <?php elseif ($_GET['status'] === 'deleted'): ?>
                    showToast('Medicine deleted successfully!', 'success');
                <?php elseif ($_GET['status'] === 'barcode_exists'): ?>
                    showToast('Error: This barcode already exists. Please use a different barcode.', 'error');
                <?php endif; ?>

                if (window.history.replaceState) {
                    const url = new URL(window.location);
                    url.searchParams.delete('status');
                    window.history.replaceState(null, null, url);
                }
            });
        </script>
    <?php endif; ?>
    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>

    <script>
        // Add Medicine Modal Logic
        const modal = document.getElementById('add-medicine-modal');
        document.getElementById('btn-open-add-modal')?.addEventListener('click', () => modal.style.display = 'flex');
        document.getElementById('close-add-modal')?.addEventListener('click', () => modal.style.display = 'none');
        document.getElementById('cancel-add-modal')?.addEventListener('click', () => modal.style.display = 'none');
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) modal.style.display = 'none';
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') modal.style.display = 'none';
        });
    </script>

</body>

</html>