<?php 
class DashboardController extends Controller {
    public function __construct() {
        $this->requireRoles(['admin']);
    }

    public function index() {
        // 1. CALL MODELS
        $saleModel = $this->model('Sale');
        $medicineModel = $this->model('Medicine');
        $clientModel = $this->model('Client');

        // 2. Get daily sales statistics
        $daily_revenue = $saleModel->getTodayRevenue();
        $daily_transactions = $saleModel->getTodayTransactionsCount();

        // 3. git low inventory alerts
        $low_stock_threshold = 10;
        $low_stock_items = $medicineModel->getLowStockAlerts($low_stock_threshold);
        $low_stock_count = count($low_stock_items);

        // 4. get unpaid credit
        $unpaid_credit = $clientModel->getTotalUnpaidCredit();

        // 5. get latest sales to the lower table
        $recent_sales = $saleModel->getRecentSales(5);

        // 6. send all this data to dashboard
        $this->view('dashboard', [
            'daily_revenue'      => $daily_revenue,
            'daily_transactions' => $daily_transactions,
            'low_stock_count'    => $low_stock_count,
            'unpaid_credit'      => $unpaid_credit,
            'low_stock_items'    => $low_stock_items,
            'recent_sales'       => $recent_sales
        ]);
    }
}