<?php
$nameParts = explode(' ', $client['name']);
$initials = strtoupper(mb_substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? mb_substr($nameParts[1], 0, 1) : ''));

$balance = floatval($client['credit_balance']);
$statusClass = $balance > 0 ? 'badge-danger' : 'badge-success';
$statusText = $balance > 0 ? 'Has Debt' : 'Cleared';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($client['name']) ?> - Credit History — 7X</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/credit.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/credit-history.css">
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

                <a href="<?= BASE_URL ?>/inventory" class="nav-item">
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
                    <button class="btn-sidebar-toggle" id="btn-sidebar-toggle"><i class="fa-solid fa-bars"></i></button>
                    <div>
                        <div class="page-title">Credit History</div>
                        <div class="breadcrumb">
                            7X Pharma Nexus &rsaquo;
                            <a href="<?= BASE_URL ?>/credit" style="color: inherit; text-decoration: none;">Credit Management</a> &rsaquo;
                            <span><?= htmlspecialchars($client['name']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="navbar-right">
                    <span class="navbar-clock" id="navbar-clock"></span>
                    <button id="theme-toggle" class="btn-icon" aria-label="Toggle Theme">
                        <span id="theme-icon"></span>
                    </button>
                    <a href="<?= BASE_URL ?>/credit" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-chevron-left"></i> Back to Ledger
                    </a>
                </div>
            </header>

            <main class="page-body">
                <div class="ch-profile-card">
                    <div class="ch-profile-avatar"><?= $initials ?></div>
                    <div class="ch-profile-info">
                        <h1 class="ch-profile-name"><?= htmlspecialchars($client['name']) ?></h1>
                        <div class="ch-profile-meta"><?= htmlspecialchars($client['phone'] ?? 'No Phone') ?></div>
                        <div class="ch-profile-meta">Client since <?= date('M Y', strtotime($client['created_at'])) ?></div>
                    </div>
                    <div class="ch-profile-stats">
                        <div class="ch-profile-stat">
                            <div class="ch-stat-val <?= $balance > 0 ? 'danger' : 'success' ?>"><?= number_format($balance, 2) ?> DH</div>
                            <div class="ch-stat-lbl">Outstanding Balance</div>
                        </div>
                        <div class="ch-profile-stat">
                            <div class="ch-stat-val"><?= count($transactions) ?></div>
                            <div class="ch-stat-lbl">Total Payments</div>
                        </div>
                        <div class="ch-profile-stat">
                            <div class="ch-stat-val success"><?= number_format($total_paid, 2) ?> DH</div>
                            <div class="ch-stat-lbl">Total Paid</div>
                        </div>
                    </div>
                    <div class="ch-profile-badge">
                        <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title"><i class="fa-regular fa-clock"></i> Transaction History</h2>
                        <div class="panel-actions">
                            <button class="btn btn-primary btn-sm" id="btn-add-payment-history"><i class="fa-solid fa-plus"></i> Add Payment</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table ch-history-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount (DH)</th>
                                    <th>Method</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--color-text-secondary);">No payments recorded yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $index => $tx): ?>
                                        <?php $isDebt = ($tx['type'] ?? 'payment') === 'debt'; ?>
                                        <tr class="<?= $isDebt ? 'row-debt' : 'row-payment' ?>">
                                            <td style="color:var(--color-text-secondary);"><?= $index + 1 ?></td>
                                            <td>
                                                <div class="tx-date"><?= date('d M Y', strtotime($tx['payment_date'])) ?></div>
                                                <div class="tx-time"><?= date('H:i', strtotime($tx['payment_date'])) ?></div>
                                            </td>
                                            <td>
                                                <?php if ($isDebt): ?>
                                                    <span class="tx-type-badge debt">
                                                        <i class="fa-solid fa-arrow-up"></i> Credit
                                                    </span>
                                                <?php else: ?>
                                                    <span class="tx-type-badge payment">
                                                        <i class="fa-solid fa-arrow-down"></i> Payment
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($isDebt): ?>
                                                    <span class="tx-amount debit">+ <?= number_format($tx['amount'], 2) ?></span>
                                                <?php else: ?>
                                                    <span class="tx-amount credit">- <?= number_format($tx['amount'], 2) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                // 🌟 موزع الأيقونات (Icon Dispatcher)
                                                $method = strtolower($tx['payment_method'] ?? 'cash');
                                                $icon = 'fa-wallet'; // الأيقونة الافتراضية

                                                if ($method === 'cash') {
                                                    $icon = 'fa-money-bill-wave';
                                                } elseif ($method === 'card') {
                                                    $icon = 'fa-credit-card';
                                                } elseif ($method === 'cheque') {
                                                    $icon = 'fa-money-check-dollar';
                                                } elseif ($method === 'transfer') {
                                                    $icon = 'fa-building-columns';
                                                } elseif ($method === 'credit') {
                                                    $icon = 'fa-file-invoice-dollar';
                                                }
                                                ?>
                                                <span class="ch-method <?= $method ?>">
                                                    <i class="fa-solid <?= $icon ?>"></i>
                                                    <?= ucfirst($method) ?>
                                                </span>
                                            </td>
                                            <td class="tx-note"><?= htmlspecialchars($tx['note'] ?? '—') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal-overlay" id="payment-modal" style="display:none;">
        <div class="modal-box" style="max-width:420px;">
            <div class="modal-header">
                <h2>Record Payment</h2>
                <button class="modal-close" id="close-payment-modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="payment-form" method="POST" action="<?= BASE_URL ?>/credit/pay">
                <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                <input type="hidden" name="redirect_to" value="history">
                <div class="pay-modal-body">
                    <div class="pay-modal-client-info">
                        <div class="client-name-lg"><?= htmlspecialchars($client['name']) ?></div>
                        <div class="client-balance">Outstanding: <span><?= number_format($balance, 2) ?></span> DH</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Amount (DH) *</label>
                        <input type="number" name="amount" class="form-control" min="0.01" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="cash">Cash</option>
                            <option value="card">Card Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Note (optional)</label>
                        <input type="text" name="note" class="form-control" placeholder="e.g. Partial payment">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-payment-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script>
        // Payment Modal Logic
        const paymentModal = document.getElementById('payment-modal');
        document.getElementById('btn-add-payment-history')?.addEventListener('click', () => paymentModal.style.display = 'flex');
        document.getElementById('close-payment-modal')?.addEventListener('click', () => paymentModal.style.display = 'none');
        document.getElementById('cancel-payment-modal')?.addEventListener('click', () => paymentModal.style.display = 'none');
        window.addEventListener('click', e => {
            if (e.target === paymentModal) paymentModal.style.display = 'none';
        });
    </script>
</body>

</html>