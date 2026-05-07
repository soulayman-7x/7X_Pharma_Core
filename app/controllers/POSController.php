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

        $keyword = trim($_GET['q'] ?? '');
        $category = trim($_GET['category'] ?? 'all');

        $medicines = $medicineModel->searchAndFilter($keyword, $category);

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

    // 3. Checkout Process (الدفع ومعالجة البيع)
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
            $client_name = $_POST['client_name'] ?? 'Walk-in Customer';
            $payment_method = $_POST['payment_method'] ?? 'Cash';
            $discount_percentage = floatval($_POST['discount'] ?? 0);

            $subtotal = 0;
            $items_count = 0;

            foreach ($_SESSION['cart'] as $item) {
                $subtotal += ($item['price'] * $item['quantity']);
                $items_count += $item['quantity'];
            }

            $discount_amount = $subtotal * ($discount_percentage / 100);
            $total_amount = $subtotal - $discount_amount;

            $receipt_no = 'RX-' . strtoupper(substr(uniqid(), -6));

            $saleModel = $this->model('Sale');
            $saleDetailModel = $this->model('SaleDetail');
            $medicineModel = $this->model('Medicine');

            $saleData = [
                'receipt_number' => $receipt_no,
                'user_id' => $_SESSION['user_id'],
                'client_name' => empty($client_name) ? 'Walk-in Customer' : $client_name,
                'items_count' => $items_count,
                'subtotal' => $subtotal,
                'discount' => $discount_amount,
                'total_amount' => $total_amount,
                'payment_method' => $payment_method,
                'status' => ($payment_method === 'credit') ? 'pending' : 'paid'
            ];

            $sale_id = $saleModel->insert($saleData);

            if ($sale_id) {
                foreach ($_SESSION['cart'] as $med_id => $item) {
                    $saleDetailModel->insert([
                        'sale_id' => $sale_id,
                        'medicine_id' => $med_id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price']
                    ]);

                    // حساب المخزون الجديد
                    $med = $medicineModel->getById($med_id);
                    $new_qty = $med['current_quantity'] - $item['quantity'];

                    $medicineModel->update($med_id, [
                        'current_quantity' => $new_qty
                    ]);
                }
                $_SESSION['last_sale_id'] = $sale_id;
                $_SESSION['cart'] = [];

                $redirectUrl = 'pos?recept=1&recept_no=' . $receipt_no . '&method' . $payment_method . '&total=' . number_format($total_amount, 2);
                $this->redirect($redirectUrl);
            } else {
                die("Critical Error: Could not save the sale to the database.");
            }
        }  else {
            $this->redirect('pos');
        } 
    }
}