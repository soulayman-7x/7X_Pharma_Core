<?php
class CreditController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
    }

    // 1. default function : Debt ledger display
    // link: /credit
    public function index() {
        $clientModal = $this->model('Client');
        $clients = $clientModal->getAll();

        // send data to credit interface
        $this->view('credit', ['clients' => $clients]);
    }

    // 2. Adding a new customer to the debt ledger
    // link: /credit/add
    public function add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $clientModal = $this->model('Client');

        // Preparing new customer data
        $data = [
            'name' => trim($_POST['name']),
            'phone' => trim($_POST['phone']),
            'balance' => 0 // The balance starts at 0 when adding a new customer.
        ];

        $clientModal->insert($data);
        $this->redirect('credit?status=client_added');
        }
    }

    //  3. Recording a financial payment (paying part or all of the debt)
    // link: /credit/pay
    public function pay() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clientModal = $this->model('Client');
            $clientId = $_POST['client_id'];
            $amount = floatval($_POST['amount']); // تحويل المبلغ إلى رقم عشري لضمان الدقة
            $clientModal->makePayment($clientId, $amount); // had function khas nzidha f class Client

            $this->redirect('credit?status=payment_success');
        }
    }
}