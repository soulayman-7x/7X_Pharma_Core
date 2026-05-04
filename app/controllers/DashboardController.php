<?php 
class DashboardController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
    }

    public function index() {
        // Here we can retrieve statistics from Models.
        // like: Fetching expired medication alerts
        $batchModel = $this->model('batch');
        $expiringAlerts = $batchModel->getExpiringSoon(90); // الادوية التي ستنتهي خلال 90 يوم 

        $this->view('dashboard', [
            'admin_name' => $_SESSION['name'],
            'alerts' => $expiringAlerts
        ]);
    }
}