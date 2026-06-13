<?php 
class POSController extends Controller {

    public function __construct() {
        $this->requireRoles(['admin', 'pharmacist']);

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    //  1. Displaying the POS screen (Medicines, Search, Filtering)
    public function index() {
        // Call the Models
        $medicineModel = $this->model('Medicine');
        $clientModel = $this->model('Client');

        $keyword = trim($_GET['q'] ?? '');
        $category = trim($_GET['category'] ?? 'all');

        $medicines = $medicineModel->searchAndFilter($keyword, $category);
        $clients = $clientModel->getAll();

        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $last_sale_items = [];
        if (isset($_GET['receipt']) && isset($_SESSION['last_sale_id']) ) {
            // Bring sales details to the invoice
            $saleDetailModel = $this->model('SaleDetail');
            $last_sale_items = $saleDetailModel->getItemsBySaleId($_SESSION['last_sale_id']);
        }

        $this->view('pos', [
            'medicines' => $medicines,
            'clients' => $clients,
            'cart_subtotal' =>$subtotal,
            'cart_total' => $subtotal,
            'last_sale_items' => $last_sale_items
        ]);
    }

    // 2. cart operations (add, update, remove, clear)

    // add
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['med_id'])) {
            $med_id = $_POST['med_id'];
            $medicineModel = $this->model('Medicine');

            $med = $medicineModel->getById($med_id);

            if ($med && $med['current_quantity'] > 0) {
                if (isset($_SESSION['cart'][$med_id])) {
                    if ($_SESSION['cart'][$med_id]['quantity'] < $med['current_quantity']) {
                        $_SESSION['cart'][$med_id]['quantity']++;
                    } else {
                        $this->setFlash('error', 'Not enough stock available!');
                    }
                } else {
                    $_SESSION['cart'][$med_id] = [
                        'name' => $med['name'],
                        'price' => $med['price'],
                        'quantity' => 1,
                        'max_stock' => $med['current_quantity']
                    ];
                }
            }
            $this->redirect('pos');
        }
    }

    // update
    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['med_id']) && isset($_POST['action'])) {
            $med_id = $_POST['med_id'];
            $action = $_POST['action'];

            if (isset($_SESSION['cart'][$med_id])) {
                if ($action === 'increase') {
                    if ($_SESSION['cart'][$med_id]['quantity'] < $_SESSION['cart'][$med_id]['max_stock']) {
                        $_SESSION['cart'][$med_id]['quantity']++;
                    }
                } elseif ($action === 'decrease') {
                    if ($_SESSION['cart'][$med_id]['quantity'] > 1) {
                        $_SESSION['cart'][$med_id]['quantity']--;
                    } else {
                        unset($_SESSION['cart'][$med_id]);
                    }
                }
            }
            $this->redirect('pos');
        }
    }

    // remove
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['med_id'])) {
            $med_id = $_POST['med_id'];

            if (isset($_SESSION['cart'][$med_id])) {
                unset($_SESSION['cart'][$med_id]);
            }
            $this->redirect('pos');
        }
    }

    // clear
    public function clearCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['cart'] = [];
            $this->redirect('pos');
        }
    }

    // 3. Checkout Process
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {

            $payment_method      = strtolower($_POST['payment_method'] ?? 'cash');
            $discount_percentage = floatval($_POST['discount'] ?? 0);
            $client_id           = !empty($_POST['client_id']) ? intval($_POST['client_id']) : null;

            // Validate: credit payment requires a client
            if ($payment_method === 'credit' && !$client_id) {
                $this->setFlash('error', 'Please select a client for credit payments.');
                $this->redirect('pos');
                return;
            }

            $subtotal = 0;
            foreach ($_SESSION['cart'] as $item) {
                $subtotal += ($item['price'] * $item['quantity']);
            }
            $discount_amount = $subtotal * ($discount_percentage / 100);
            $total_amount = $subtotal - $discount_amount;

            // Use ONE shared model/connection for everything inside the transaction
            $medicineModel = $this->model('Medicine');
            $db = $medicineModel->getDb(); // single PDO connection

            // I will perform several interconnected operations. Either they all succeed together, or they all fail together.
            $db->beginTransaction();

            try {
                // 1. Insert the sale header
                $saleSql = "INSERT INTO sales (user_id, client_id, total_amount, payment_method)
                            VALUES (?, ?, ?, ?)";
                $db->prepare($saleSql)->execute([
                    $_SESSION['user_id'],
                    $client_id,
                    $total_amount,
                    $payment_method
                ]);
                $sale_id = $db->lastInsertId();

                if (!$sale_id) {
                    throw new Exception('Failed to create sale record.');
                }

                foreach ($_SESSION['cart'] as $med_id => $item) {
                    // 2. Fetch medicine info for the snapshot (read only — same connection, in transaction)
                    $med = $medicineModel->getById($med_id);
                    $batch_id = $medicineModel->getOldestBatchId($med_id);

                    if (!$batch_id) {
                        throw new Exception("No available batch for medicine: {$item['name']}.");
                    }

                    // 3. Unified snapshot
                    $snapshot = json_encode([
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'barcode' => $med['barcode'] ?? '',
                        'dci' => $med['dci'] ?? '',
                    ]);

                    // 4. Insert sale item — same connection = sale_id is visible, no FK violation
                    $itemSql = "INSERT INTO sale_items (sale_id, batch_id, quantity, unit_price, snapshot_data)
                                VALUES (?, ?, ?, ?, ?)";
                    $db->prepare($itemSql)->execute([
                        $sale_id,
                        $batch_id,
                        $item['quantity'],
                        $item['price'],
                        $snapshot
                    ]);

                    // 5. Deduct stock (FIFO) — uses same connection, auto-visible inside transaction
                    $deducted = $medicineModel->deductStock($med_id, $item['quantity']);
                    if (!$deducted) {
                        throw new Exception("Insufficient stock for: {$item['name']}.");
                    }

                    // 6. Record inventory movement
                    $moveSql = "INSERT INTO inventory_movements
                                    (batch_id, movement_type, quantity, user_id, reference_id)
                                VALUES (?, 'sale', ?, ?, ?)";
                    $db->prepare($moveSql)->execute([
                        $batch_id,
                        $item['quantity'],
                        $_SESSION['user_id'],
                        $sale_id
                    ]);
                }

                // 7. Register credit debt if applicable
                if ($payment_method === 'credit' && $client_id) {
                    $db->prepare("UPDATE clients SET credit_balance = credit_balance + ? WHERE id = ?")
                       ->execute([$total_amount, $client_id]);
                }

                // All good — commit
                $db->commit();

                $_SESSION['last_sale_id'] = $sale_id;
                $_SESSION['cart'] = [];

                $receipt_no  = 'RX-' . str_pad($sale_id, 6, '0', STR_PAD_LEFT);
                $redirectUrl = "pos?receipt=1&receipt_no={$receipt_no}&method={$payment_method}&total=" . number_format($total_amount, 2);
                $this->redirect($redirectUrl);

            } catch (Exception $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }

                // Log error
                $logFile = ROOT_DIR . '/logs/checkout_errors.log';
                $msg     = '[' . date('Y-m-d H:i:s') . '] CHECKOUT ERROR: ' . $e->getMessage() . PHP_EOL;
                @file_put_contents($logFile, $msg, FILE_APPEND);

                $this->setFlash('error', 'Sale failed: ' . $e->getMessage());
                $this->redirect('pos');
            }

        } else {
            $this->redirect('pos');
        }
    }
}
