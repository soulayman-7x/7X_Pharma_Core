<?php
$total_debt = 0;
$clients_with_debt = 0;
if (!empty($clients)) {
    foreach ($clients as $client) {
        if ($client['credit_balance'] > 0) {
            $total_debt += $client['credit_balance'];
            $clients_with_debt++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Credit — 7X Pharma Nexus</title>
    <meta name="description" content="Client credit and debt ledger for 7X Pharma Nexus.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/credit.css">
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
                <a href="<?= BASE_URL ?>/dashboard" class="nav-item " aria-current="page">
                    <i class="fa-solid fa-table-cells-large fa-fw"></i>
                    <span class="nav-text">Dashboard</span>
                </a>

                <a href="<?= BASE_URL ?>/pos" class="nav-item">
                    <i class="fa-solid fa-cash-register fa-fw"></i>
                    <span class="nav-text">Point of Sale</span>
                </a>

                <p class="nav-section-label">Management</p>

                <a href="<?= BASE_URL ?>/inventory" class="nav-item">
                    <i class="fa-solid fa-boxes-stacked fa-fw"></i>
                    <span class="nav-text">Inventory</span>
                    <span class="nav-badge"><?= $low_stock_count ?? 0 ?></span>
                </a>

                <a href="<?= BASE_URL ?>/credit" class="nav-item active">
                    <i class="fa-solid fa-hand-holding-dollar fa-fw"></i>
                    <span class="nav-text">Client Credit</span>
                </a>

                <p class="nav-section-label">Reports</p>

                <a href="<?= BASE_URL ?>/reports/sales" class="nav-item">
                    <i class="fa-solid fa-chart-column fa-fw"></i>
                    <span class="nav-text">Sales Reports</span>
                </a>


                <p class="nav-section-label">System</p>

                <a href="<?= BASE_URL ?>/settings" class="nav-item">
                    <i class="fa-solid fa-gear fa-fw"></i>
                    <span class="nav-text">Settings</span>
                </a>

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
                        <div class="page-title">Client Credit Ledger</div>
                        <div class="breadcrumb">7X Pharma Nexus &rsaquo; <span>Credit Management</span></div>
                    </div>
                </div>
                <div class="navbar-right">
                    <span class="navbar-clock" id="navbar-clock"></span>
                    <button id="theme-toggle" class="btn-icon" aria-label="Toggle Theme">
                        <span id="theme-icon"></span>
                    </button>
                    <button class="btn btn-primary btn-sm btn-glow" id="btn-open-add-client" aria-label="Add new client">
                        <i class="fa-solid fa-user-plus"></i>
                        Add Client
                    </button>
                </div>
            </header>

            <main class="page-body">

                <div class="credit-stats">
                    <div class="credit-stat-card glass-panel">
                        <div class="credit-stat-icon blue neon-glow-blue"><i class="fa-solid fa-users"></i></div>
                        <div class="credit-stat-body">
                            <div class="stat-value"><?= $clients_with_debt ?></div>
                            <div class="stat-label">Clients with Debt</div>
                        </div>
                    </div>
                    <div class="credit-stat-card glass-panel">
                        <div class="credit-stat-icon purple neon-glow-purple"><i class="fa-solid fa-sack-dollar"></i></div>
                        <div class="credit-stat-body">
                            <div class="stat-value"><?= number_format($total_debt, 2) ?> DH</div>
                            <div class="stat-label">Total Outstanding Credit</div>
                        </div>
                    </div>
                    <div class="credit-stat-card glass-panel">
                        <div class="credit-stat-icon red neon-glow-red"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="credit-stat-body">
                            <div class="stat-value">0</div>
                            <div class="stat-label">Overdue (+30 days)</div>
                        </div>
                    </div>
                </div>

                <div class="panel glass-panel">
                    <div class="panel-body" style="padding:var(--space-3);">
                        <form method="GET" action="<?= BASE_URL ?>/credit" class="credit-filter-bar" role="search">
                            <div class="search-input-wrap">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="search" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search by client name or phone..." aria-label="Search clients">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            <a href="<?= BASE_URL ?>/credit" class="btn btn-outline btn-sm">Reset</a>
                        </form>
                    </div>
                </div>

                <div class="panel glass-panel">
                    <div class="panel-header">
                        <h2 class="panel-title">
                            <i class="fa-solid fa-book-open"></i>
                            Client Debt Ledger
                        </h2>
                        <div class="panel-actions">
                            <a href="#" class="btn btn-outline btn-sm"><i class="fa-solid fa-download"></i> Export CSV</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table" aria-label="Client credit ledger">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Phone</th>
                                    <th>Credit Balance (DH)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($clients)): ?>
                                    <?php foreach ($clients as $index => $client): ?>
                                        <?php
                                        $balance = floatval($client['credit_balance']);
                                        $debtClass = $balance > 500 ? 'high' : ($balance > 0 ? 'medium' : 'low');
                                        // Changed from green/yellow to custom strictly blue/purple logic
                                        $statusBadge = $balance > 0 ? '<span class="badge badge-debt">Has Debt</span>' : '<span class="badge badge-cleared">Cleared</span>';
                                        ?>
                                        <tr>
                                            <td style="color:var(--color-text-secondary);"><?= $index + 1 ?></td>
                                            <td>
                                                <strong class="client-name-text"><?= htmlspecialchars($client['name']) ?></strong>
                                            </td>
                                            <td><span class="phone-text"><?= htmlspecialchars($client['phone'] ?? 'N/A') ?></span></td>
                                            <td><span class="debt-amount <?= $debtClass ?>"><?= number_format($balance, 2) ?> DH</span></td>
                                            <td><?= $statusBadge ?></td>
                                            <td>
                                                <?php if ($balance > 0): ?>
                                                    <button class="btn-pay" data-client-id="<?= $client['id'] ?>" data-client-name="<?= htmlspecialchars($client['name'], ENT_QUOTES) ?>" data-balance="<?= $balance ?>" id="pay-btn-<?= $client['id'] ?>">
                                                        <i class="fa-solid fa-plus"></i> Payment
                                                    </button>
                                                <?php endif; ?>
                                                <a href="<?= BASE_URL ?>/credit/history/<?= $client['id'] ?>" class="btn-table-action" style="margin-left:4px; text-decoration:none; display:inline-block;">
                                                    <i class="fa-solid fa-clock-rotate-left"></i> History
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="empty-state-cell">
                                            <div class="empty-state-container">
                                                <div class="empty-state-icon"><i class="fa-solid fa-folder-open"></i></div>
                                                <h3>No Data Found</h3>
                                                <p>No clients found in the ledger at this moment.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <div class="modal-overlay" id="payment-modal" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="payment-modal-title">
        <div class="modal-box glass-modal" style="max-width:420px;">
            <div class="modal-header">
                <h2 id="payment-modal-title">Record Payment</h2>
                <button class="modal-close" id="close-payment-modal" aria-label="Close">&times;</button>
            </div>
            <form id="payment-form" method="POST" action="<?= BASE_URL ?>/credit/pay">
                <input type="hidden" name="client_id" id="modal-client-id" value="">

                <div class="pay-modal-body">
                    <div class="pay-modal-client-info">
                        <div class="client-name-lg" id="modal-client-name">Client Name</div>
                        <div class="client-balance">Outstanding: <span id="modal-client-balance">0.00</span> DH</div>
                    </div>

                    <div class="form-group input-cyber-group">
                        <label for="payment-amount" class="form-label">Payment Amount (DH) *</label>
                        <input type="number" id="payment-amount" name="amount" class="form-control cyber-input" min="0.01" step="0.01" placeholder="0.00" required>
                    </div>

                    <div class="form-group input-cyber-group">
                        <label for="payment-method" class="form-label">Payment Method</label>
                        <select id="payment-method" name="payment_method" class="form-control cyber-input">
                            <option value="cash">Cash</option>
                            <option value="card">Card Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-payment-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-glow"><i class="fa-solid fa-check"></i> Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="add-client-modal" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="add-client-title">
        <div class="modal-box glass-modal" style="max-width:420px;">
            <div class="modal-header">
                <h2 id="add-client-title">Add New Client</h2>
                <button class="modal-close" id="close-add-client-modal" aria-label="Close">&times;</button>
            </div>
            <form id="add-client-form" method="POST" action="<?= BASE_URL ?>/credit/add">
                <div class="pay-modal-body">
                    <div class="form-group input-cyber-group">
                        <label for="client-name-input" class="form-label">Full Name *</label>
                        <input type="text" id="client-name-input" name="name" class="form-control cyber-input" placeholder="Client full name" required>
                    </div>
                    <div class="form-group input-cyber-group">
                        <label for="client-phone" class="form-label">Phone Number *</label>
                        <input type="tel" id="client-phone" name="phone" class="form-control cyber-input" placeholder="+212 6 XX XX XX XX" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-add-client">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-glow"><i class="fa-solid fa-save"></i> Save Client</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/toast.js"></script>
    <script>
        // Payment Modal Logic
        const paymentModal = document.getElementById('payment-modal');
        document.querySelectorAll('.btn-pay').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('modal-client-id').value = btn.dataset.clientId;
                document.getElementById('modal-client-name').textContent = btn.dataset.clientName;
                document.getElementById('modal-client-balance').textContent = btn.dataset.balance;
                document.getElementById('payment-amount').value = '';
                paymentModal.style.display = 'flex';
            });
        });
        document.getElementById('close-payment-modal')?.addEventListener('click', () => paymentModal.style.display = 'none');
        document.getElementById('cancel-payment-modal')?.addEventListener('click', () => paymentModal.style.display = 'none');

        // Add Client Modal Logic
        const addClientModal = document.getElementById('add-client-modal');
        document.getElementById('btn-open-add-client')?.addEventListener('click', () => addClientModal.style.display = 'flex');
        document.getElementById('close-add-client-modal')?.addEventListener('click', () => addClientModal.style.display = 'none');
        document.getElementById('cancel-add-client')?.addEventListener('click', () => addClientModal.style.display = 'none');

        // General Modal Close on Click Outside or Escape
        window.addEventListener('click', (e) => {
            if (e.target === paymentModal) paymentModal.style.display = 'none';
            if (e.target === addClientModal) addClientModal.style.display = 'none';
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                paymentModal.style.display = 'none';
                addClientModal.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                const status = urlParams.get('status');
                if (status === 'client_added') showToast('Client added successfully!', 'success');
                else if (status === 'payment_success') showToast('Payment recorded successfully!', 'success');
                else if (status === 'invalid_amount') showToast('Invalid amount entered.', 'error');
                else if (status === 'error') showToast('Error processing request.', 'error');

                if (window.history.replaceState) {
                    const url = new URL(window.location);
                    url.searchParams.delete('status');
                    window.history.replaceState(null, null, url);
                }
            }
        });
    </script>
</body>

</html>