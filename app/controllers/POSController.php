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

        if (!empty($keyword)) {
            $medicineList = $medicineModel->searchByName($keyword);
        } else {
            $medicineList = $medicineModel->getAll();
        }

        if ($category !== 'all') {
            foreach ($medicineList as $med) {
                if (strtolower($med['category']) === strtolower($category)) {
                    $filteredList[] = $med;
                }
            }
            $medicineList = $filteredList;
        }

        // 
        $cart_subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $cart_subtotal += ($item['price'] * $item['quantity']);
        }
        $cart_total = $cart_subtotal;

        // send data to pos.php
        $this->view('pos', [
            'medicines' => $medicineList,
            'cart_subtotal' => $cart_subtotal,
            'cart_total' => $cart_total,
            'pharmacist_name' => $_SESSION['name']
        ]);
    }

    // 2. cart operations (add, update, remove, clear)


}