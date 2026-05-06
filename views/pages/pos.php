<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS — 7X Pharma Nexus</title>
    <meta name="description" content="7X Pharma Nexus Point of Sale — Fast pharmacy billing.">

    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/logo/7X-PHARMA-ICO.png">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pos.css">
</head>

<body>
    <div class="pos-wrapper">
        <div class="pos-main-layout">

            <!-- ===== NAVBAR ===== -->
            <nav class="pos-navbar">
                <div class="brand">
                    <img src="<?= BASE_URL ?>/assets/images/logo/7X-PHARMA-ICO.png" alt="7x pharma logo">
                    7X Pharma Nexus &nbsp;<span style="font-size:.75rem;font-weight:400;color:var(--color-text-secondary);">POS</span>
                </div>
                <div class="pos-nav-actions">
                    <span id="pos-clock" style="font-size:.8rem;color:var(--color-text-secondary);"></span>
                    <button id="theme-toggle" class="btn-icon" aria-label="Toggle Theme">
                        <span id="theme-icon"></span>
                    </button>
                    <a href="<?= BASE_URL ?>/dashboard" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-table-cells-large"></i> Dashboard
                    </a>
                </div>
            </nav>

            <!-- ===== MAIN SPLIT CONTENT ===== -->
            <div class="pos-content">

                <!-- ================================================
                     LEFT PANEL — MEDICINE SEARCH & GRID 
                ================================================ -->
                <section class="pos-panel-products" aria-label="Medicine selection">

                    <!-- Search Form -->
                    <form method="GET" action="<?= BASE_URL ?>/pos" class="search-wrapper" role="search">
                        <i class="fa-solid fa-magnifying-glass" style="color: var(--color-text-secondary); margin-left: 10px;"></i>
                        <input type="search" name="q" id="medicine-search"
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                            placeholder="Search by name or barcode... (Press F3)"
                            autocomplete="off" aria-label="Search medicines">
                        <?php if (isset($_GET['category'])): ?>
                            <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
                        <?php endif; ?>
                    </form>

                    <!-- Category Filter Tabs -->
                    <?php $activeCat = $_GET['category'] ?? 'all'; ?>
                    <nav class="category-tabs" aria-label="Filter by category">
                        <a href="?category=all<?= isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '' ?>" class="cat-tab <?= $activeCat === 'all' ? 'active' : '' ?>">All</a>
                        <a href="?category=analgesic<?= isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '' ?>" class="cat-tab <?= $activeCat === 'analgesic' ? 'active' : '' ?>">Analgesics</a>
                        <a href="?category=antibiotic<?= isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '' ?>" class="cat-tab <?= $activeCat === 'antibiotic' ? 'active' : '' ?>">Antibiotics</a>
                        <a href="?category=vitamin<?= isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '' ?>" class="cat-tab <?= $activeCat === 'vitamin' ? 'active' : '' ?>">Vitamins</a>
                        <a href="?category=cardiac<?= isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '' ?>" class="cat-tab <?= $activeCat === 'cardiac' ? 'active' : '' ?>">Cardiac</a>
                        <a href="?category=derma<?= isset($_GET['q']) ? '&q=' . urlencode($_GET['q']) : '' ?>" class="cat-tab <?= $activeCat === 'derma' ? 'active' : '' ?>">Dermatology</a>
                    </nav>

                    <!-- Medicine Grid -->
                    <div class="medicine-grid" id="medicine-grid" role="list">
                        <?php if (isset($medicines) && count($medicines) > 0): ?>
                            <?php foreach ($medicines as $med): ?>
                                <?php
                                $isOutOfStock = $med['current_quantity'] <= 0;
                                $isLowStock = $med['current_quantity'] > 0 && $med['current_quantity'] <= 5;
                                ?>
                                <div class="medicine-card <?= $isOutOfStock ? 'out-of-stock' : '' ?>" role="listitem" <?= $isOutOfStock ? 'aria-disabled="true"' : '' ?>>
                                    <span class="med-category-badge"><?= htmlspecialchars(ucfirst($med['category'] ?? 'General')) ?></span>
                                    <div class="med-name"><?= htmlspecialchars($med['name']) ?></div>
                                    <div class="med-dosage"><?= htmlspecialchars($med['dosage'] ?? 'Standard') ?></div>

                                    <div class="med-footer">
                                        <span class="med-price"><?= number_format($med['price'], 2) ?> DH</span>

                                        <div style="display:flex;align-items:center;gap:6px;">
                                            <?php if ($isOutOfStock): ?>
                                                <span class="med-stock" style="color:var(--color-danger);">Out of Stock</span>
                                            <?php elseif ($isLowStock): ?>
                                                <span class="med-stock low" style="color:var(--color-warning);"><i class="fa-solid fa-triangle-exclamation"></i> Low: <?= $med['current_quantity'] ?></span>
                                            <?php else: ?>
                                                <span class="med-stock">Stock: <?= $med['current_quantity'] ?></span>
                                            <?php endif; ?>

                                            <?php if (!$isOutOfStock): ?>
                                                <form method="POST" action="<?= BASE_URL ?>/pos/addToCart" style="display:contents;">
                                                    <input type="hidden" name="med_id" value="<?= $med['id'] ?>">
                                                    <button type="submit" class="add-to-cart-btn" title="Add to cart" aria-label="Add <?= htmlspecialchars($med['name']) ?> to cart">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: var(--color-text-secondary);">
                                <i class="fa-solid fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                <p>No medicines found for this search or category.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- ================================================
                     RIGHT PANEL — CART 
                ================================================ -->
                <aside class="pos-panel-cart" aria-label="Shopping cart">

                    <div class="cart-header">
                        <div class="cart-title">
                            <i class="fa-solid fa-cart-shopping"></i>
                            Current Sale
                            <span class="cart-count-badge"><?= count($_SESSION['cart'] ?? []) ?></span>
                        </div>
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <form method="POST" action="<?= BASE_URL ?>/pos/clearCart" style="display:inline;">
                                <button type="submit" class="btn-clear-cart">Clear All</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="cart-client-section">
                        <input type="text" name="client_name" form="checkout-form" class="form-control" id="client-name"
                            placeholder="Client name (optional)..." aria-label="Client name" autocomplete="off">
                    </div>

                    <div class="cart-items-list" id="cart-items-list">
                        <?php if (empty($_SESSION['cart'])): ?>
                            <div class="cart-empty-msg" style="text-align: center; padding: 3rem 1rem; color: var(--color-text-secondary);">
                                <i class="fa-solid fa-basket-shopping" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;"></i>
                                <p>Cart is empty.<br>Click <i class="fa-solid fa-plus"></i> on a medicine to add it.</p>
                            </div>
                        <?php else: ?>
                            <table class="cart-table" aria-label="Cart items">
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                        <tr>
                                            <td>
                                                <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                                                <div class="cart-item-unit-price"><?= number_format($item['price'], 2) ?> DH / unit</div>
                                            </td>
                                            <td>
                                                <div class="qty-controls">
                                                    <form method="POST" action="<?= BASE_URL ?>/pos/updateQuantity" style="display:contents;">
                                                        <input type="hidden" name="action" value="decrease">
                                                        <input type="hidden" name="med_id" value="<?= $id ?>">
                                                        <button type="submit" class="qty-btn" aria-label="Decrease qty"><i class="fa-solid fa-minus"></i></button>
                                                    </form>
                                                    <span class="qty-display"><?= $item['quantity'] ?></span>
                                                    <form method="POST" action="<?= BASE_URL ?>/pos/updateQuantity" style="display:contents;">
                                                        <input type="hidden" name="action" value="increase">
                                                        <input type="hidden" name="med_id" value="<?= $id ?>">
                                                        <button type="submit" class="qty-btn" aria-label="Increase qty"><i class="fa-solid fa-plus"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="cart-item-subtotal"><?= number_format($item['price'] * $item['quantity'], 2) ?> DH</td>
                                            <td>
                                                <form method="POST" action="<?= BASE_URL ?>/pos/removeFromCart" style="display:contents;">
                                                    <input type="hidden" name="med_id" value="<?= $id ?>">
                                                    <button type="submit" class="qty-btn remove-btn" aria-label="Remove item" style="color: var(--color-danger); border-color: rgba(239, 68, 68, 0.3);">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>

                    <!-- Cart Footer -->
                    <div class="cart-footer">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="cart-subtotal"><?= number_format($cart_subtotal ?? 0, 2) ?> DH</span>
                        </div>

                        <div class="discount-row">
                            <label for="discount-input">Discount</label>
                            <input type="number" id="discount-input" name="discount" form="checkout-form"
                                class="form-control" min="0" max="100" value="0" placeholder="0">
                            <span style="font-size:.85rem;color:var(--color-text-secondary);">%</span>
                        </div>

                        <div class="summary-row total">
                            <span>TOTAL</span>
                            <span class="amount" id="cart-total"><?= number_format($cart_total ?? 0, 2) ?> DH</span>
                        </div>

                        <div class="payment-methods" role="radiogroup" aria-label="Payment method">
                            <label>
                                <input type="radio" name="payment_method" value="cash" form="checkout-form" checked>
                                <span><i class="fa-solid fa-money-bill"></i> Cash</span>
                            </label>
                            <label>
                                <input type="radio" name="payment_method" value="card" form="checkout-form">
                                <span><i class="fa-solid fa-credit-card"></i> Card</span>
                            </label>
                            <label>
                                <input type="radio" name="payment_method" value="credit" form="checkout-form">
                                <span><i class="fa-solid fa-file-invoice-dollar"></i> Credit</span>
                            </label>
                        </div>

                        <form id="checkout-form" method="POST" action="<?= BASE_URL ?>/pos/checkout">
                            <button type="submit" class="btn-checkout" id="btn-checkout" <?= empty($_SESSION['cart']) ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '' ?>>
                                <i class="fa-solid fa-check-double"></i>
                                Checkout &nbsp;&mdash;&nbsp; <?= number_format($cart_total ?? 0, 2) ?> DH
                            </button>
                        </form>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    <!-- =====================================================
     RECEIPT MODAL 
     ===================================================== -->
    <?php if (isset($_GET['receipt'])): ?>
        <div class="modal-overlay" id="receipt-modal" style="display:flex;" role="dialog" aria-modal="true" aria-labelledby="receipt-title">
            <div class="modal-box receipt-modal">

                <div class="receipt-header">
                    <h2 id="receipt-title" class="receipt-title"><i class="fa-solid fa-circle-check"></i> Sale Completed</h2>
                    <a href="<?= BASE_URL ?>/pos" class="receipt-close" aria-label="Close">&times;</a>
                </div>

                <div class="receipt-body">
                    <div class="receipt-brand-text">
                        <h3>7X Pharma Nexus</h3>
                        <p>Thank you for your visit!</p>
                    </div>

                    <hr class="receipt-divider">

                    <div class="receipt-row"><span>Receipt #</span><span><?= htmlspecialchars($_GET['receipt_no'] ?? 'RX-XXXXXX') ?></span></div>
                    <div class="receipt-row"><span>Date</span><span><?= date('d M Y, H:i') ?></span></div>
                    <div class="receipt-row"><span>Payment</span><span style="text-transform: capitalize;"><?= htmlspecialchars($_GET['method'] ?? 'Cash') ?></span></div>

                    <hr class="receipt-divider">

                    <?php if (isset($last_sale_items)): ?>
                        <?php foreach ($last_sale_items as $item): ?>
                            <div class="receipt-row">
                                <span><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></span>
                                <span><?= number_format($item['price'] * $item['quantity'], 2) ?> DH</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align:center; font-style:italic;">Items detailed in system.</div>
                    <?php endif; ?>

                    <hr class="receipt-divider">
                    <div class="receipt-row total-row">
                        <span>TOTAL</span>
                        <span><?= htmlspecialchars($_GET['total'] ?? '0.00') ?> DH</span>
                    </div>
                </div>

                <div class="receipt-actions">
                    <button class="btn btn-outline" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
                    <a href="<?= BASE_URL ?>/pos" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Sale</a>
                </div>

            </div>
        </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/pos.js"></script>

</body>

</html>