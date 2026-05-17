<?php
class NotificationController extends Controller {
    
    public function index() {
        $notificationModel = $this->model('Notification');
        
        $lowStockAlerts = $notificationModel->getLowStockAlerts();
        $expiringAlerts = $notificationModel->getExpiringAlerts();
        
        $data = [
            'title' => 'Notifications | 7X Pharma Nexus',
            'lowStock' => $lowStockAlerts,
            'expiring' => $expiringAlerts,
            'totalAlerts' => count($lowStockAlerts) + count($expiringAlerts)
        ];
        
        $this->view('notifications', $data);
    }
}
?>
