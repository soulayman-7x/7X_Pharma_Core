<?php
class CreditController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
    }

    // 1. default function : Debt ledger display
    public function index() {
        $clientModel = $this->model('Client');
        $clients = $clientModel->getAll();

        // send data to credit interface
        $this->view('credit', ['clients' => $clients]);
    }

    // 2. Adding a new customer to the debt ledger
    public function add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clientModel = $this->model('Client');

            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');


            if(!empty($name)) {
                $data = [
                    'name' => $name,
                    'phone' => $phone,
                    'credit_balance' => 0 
                ];

                $clientModel->insert($data);
                $this->redirect('credit?status=client_added');
            } else {
                $this->redirect('credit?status=error');
            }
        } else {
            $this->redirect('credit');
        }
    }

    //  3. Recording a financial payment
    public function pay() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clientModel = $this->model('Client');
            
            $clientId = intval($_POST['client_id']);
            $amount = floatval($_POST['amount']);
            $userId = $_SESSION['user_id']; 

            $method = strtolower($_POST['payment_method'] ?? 'cash');
            $note = trim($_POST['note'] ?? '');
            $note = $note === '' ? null : $note;

            if($clientId > 0 && $amount > 0) {
                $clientModel->makePayment($clientId, $amount, $userId, $method, $note); 
                
                $redirectTo = $_POST['redirect_to'] ?? 'credit';
                if ($redirectTo === 'history') {
                    $this->redirect("credit/history/{$clientId}?status=payment_success");
                } else {
                    $this->redirect('credit?status=payment_success');
                }
            } else {
                $this->redirect('credit?status=invalid_amount');
            }
        }
    }

    // 4. View client credit history
    // link: /credit/history/1
    public function history($id = null) {
        if (!$id) {
            $this->redirect('credit');
        }

        $clientModel = $this->model('Client');
        $client = $clientModel->getById($id);

        if (!$client) {
            $this->redirect('credit');
        }

        // جلب المدفوعات
        $transactions = $clientModel->getClientTransactions($id);

        // حساب إجمالي ما دفعه العميل (فقط المدفوعات وليس الديون)
        $total_paid = 0;
        foreach ($transactions as $tx) {
            if (($tx['type'] ?? 'payment') === 'payment') {
                $total_paid += floatval($tx['amount']);
            }
        }

        $this->view('credit-history', [
            'client' => $client,
            'transactions' => $transactions,
            'total_paid' => $total_paid
        ]);
    }
}
?>