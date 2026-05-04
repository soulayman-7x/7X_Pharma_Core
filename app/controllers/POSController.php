<?php 
class POSController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
    }

    // ===========================
    // Displaying the sales screen and processing the search.
    // ========================================================
    public function index() {
        // 1. call medicines model
        $medicineModel = $this->model('Medicine');
        $medicineList = [];

        // 2. Check if the pharmacist has typed anything into the search bar.
        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
            $keyword = trim($_GET['search']);
            $medicineList = $medicineModel->searchByName($keyword);
        } else {
            $medicineList = $medicineModel->getAll();
        }

        // Send data to the pos.php interface
        $this->view('pos', [
            'medicine' => $medicineList,
            'pharmacist_name' => $_SESSION['name']
        ]);
    }

    /**
     * دالة استلام الفاتورة (Checkout)
     * ستستقبل البيانات عندما يضغط الصيدلي على زر "Pay Now"
     */
    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // سنقوم لاحقاً ببرمجة منطق استلام مصفوفة السلة (Cart)
            // وحفظها في جدول المبيعات (sales)
            // وخصم الكميات من جدول المخزون (batches)
            
            // حالياً، سنكتفي بإعادة توجيهه إلى شاشة الـ POS بعد البيع
            $this->redirect('pos?status=success');
        }
    }
}