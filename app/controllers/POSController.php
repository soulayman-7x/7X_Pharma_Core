<?php 
class POSController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    //  1. Displaying the POS screen (Medicines, Search, Filtering)
    public function index() {
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
   // 3. Checkout Process
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
            
            $payment_method = strtolower($_POST['payment_method'] ?? 'cash');
            $discount_percentage = floatval($_POST['discount'] ?? 0);
            
            // 🌟 الجديد: استقبال معرف العميل إذا تم اختياره
            $client_id = !empty($_POST['client_id']) ? intval($_POST['client_id']) : null;

            $subtotal = 0;
            foreach ($_SESSION['cart'] as $item) {
                $subtotal += ($item['price'] * $item['quantity']);
            }
            $discount_amount = $subtotal * ($discount_percentage / 100);
            $total_amount = $subtotal - $discount_amount;

            $saleModel = $this->model('Sale');
            $saleData = [
                'user_id' => $_SESSION['user_id'],
                'client_id' => $client_id, // 🌟 الآن أصبح ديناميكياً
                'total_amount' => $total_amount,
                'payment_method' => $payment_method
            ];

            // 1. General Invoice Entry
            $sale_id = $saleModel->insert($saleData);

            if ($sale_id) {
                $medicineModel = $this->model('Medicine');
                $saleDetailModel = $this->model('SaleDetail'); 
                
                foreach ($_SESSION['cart'] as $med_id => $item) {
                    // 2. Fetch oldest batch
                    $batch_id = $medicineModel->getOldestBatchId($med_id);

                    $snapshot = json_encode([
                        'name' => $item['name'], 
                        'price' => $item['price']
                    ]);

                    // 3. Record item details
                    $saleDetailModel->insert([
                        'sale_id' => $sale_id,
                        'batch_id' => $batch_id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'snapshot_data' => $snapshot
                    ]);

                    // 4. Stock discount
                    $medicineModel->deductStock($med_id, $item['quantity']);
                }

                // 🌟 الجديد: تسجيل الدين في حساب العميل إذا كان الدفع "كريدي"
                if ($payment_method === 'credit' && $client_id) {
                    $clientModel = $this->model('Client');
                    $clientModel->addDebt($client_id, $total_amount);
                }

                $_SESSION['last_sale_id'] = $sale_id;
                $_SESSION['cart'] = []; 

                // Generate a fake invoice number for display purposes only
                $receipt_no = 'RX-' . str_pad($sale_id, 6, '0', STR_PAD_LEFT);
                
                $redirectUrl = "pos?receipt=1&receipt_no={$receipt_no}&method={$payment_method}&total=" . number_format($total_amount, 2);
                $this->redirect($redirectUrl);
            } else {
                die("Critical Error: Could not save the sale to the database.");
            }
        } else {
            $this->redirect('pos');
        }
    }
}