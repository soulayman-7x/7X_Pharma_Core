<?php
class InventoryController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
    }

    // 1. View inventory table
    // link: /inventory
    public function index() {
        $medicineModel = $this->model('Medicine');
        
        $keyword = trim($_GET['q'] ?? '');
        $category = trim($_GET['category'] ?? 'all');

        $medicines = $medicineModel->searchAndFilter($keyword, $category);
        
        $low_stock_items = $medicineModel->getLowStockAlerts(10);

        // Sending data to the inventory interface
        $this->view('inventory', [
            'medicines' => $medicines,
            'low_stock_count' => count($low_stock_items)
        ]);
    }

    // 2. add new medicine (From the Modal)
    // link: /inventory/add
    public function add() {
        // If the order is of type POST (meaning the pharmacist pressed the "save medicine" button)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $medicineModel = $this->model('Medicine');
            $batchModel = $this->model('Batch');

            $barcode = trim($_POST['barcode']);

            // Check for duplicate barcode before inserting
            if ($medicineModel->barcodeExists($barcode)) {
                $this->redirect('inventory?status=barcode_exists');
                return;
            }

            // Prepare the data matching our NEW database schema
            $data = [
                'name'       => trim($_POST['name']),
                'barcode'    => $barcode,
                'dci'        => trim($_POST['dci'] ?? ''),
                'category'   => trim($_POST['category']),
                'price'      => floatval($_POST['price']),
                'cost_price' => floatval($_POST['cost_price'] ?? 0)
            ];

            $med_id = $medicineModel->insert($data);

            if ($med_id && !empty($_POST['batch'])) {
                $expiry_date = trim($_POST['expiry_date']);
                if (strlen($expiry_date) === 7) {
                    $expiry_date .= '-01';
                }

                $batchModel->insert([
                    'medicine_id' => $med_id,
                    'batch_number' => trim($_POST['batch']),
                    'expiry_date' => $expiry_date,
                    'current_quantity' => $_POST['quantity']
                ]);
            }

            $this->redirect('inventory?status=added');
        } else {
            $this->redirect('inventory');
        }
    }

    // 3. edit medicine
    // link: /inventory/edit/5
    public function edit($id = null) {
        if (!$id) {
            $this->redirect('inventory');
        }

        $medicineModel = $this->model('Medicine');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $barcode = trim($_POST['barcode']);

            // Check for duplicate barcode, excluding the current medicine being edited
            if ($medicineModel->barcodeExists($barcode, $id)) {
                $this->redirect('inventory?status=barcode_exists');
                return;
            }

            $data = [
                'name'       => trim($_POST['name']),
                'barcode'    => $barcode,
                'dci'        => trim($_POST['dci']),
                'category'   => trim($_POST['category']),
                'price'      => floatval($_POST['price']),
                'cost_price' => floatval($_POST['cost_price'] ?? 0)
            ];

            $medicineModel->update($id, $data);
            $this->redirect('inventory?status=updated');
        } else {
            $medicine = $medicineModel->getById($id);

            if (!$medicine) {
                $this->redirect('inventory');
            }

            $this->view('edit-medicine', ['medicine' => $medicine]);
        }
    }

    // 4. delete medicine
    // link: inventory/delete/5
    public function delete($id = null) {
        if ($id) {
            $medicineModel = $this->model('Medicine');
            $medicineModel->delete($id);
        }
        $this->redirect('inventory?status=deleted');
    }
    // 5. add new batch
    // link: /inventory/addBatch
    public function addBatch() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $batchModel = $this->model('Batch');
            $movementModel = $this->model('InventoryMovement');

            $medicine_id = $_POST['medicine_id'];
            $batch_number = trim($_POST['batch']);
            $expiry_date = trim($_POST['expiry_date']);
            $quantity = intval($_POST['quantity']);

            if (strlen($expiry_date) === 7) {
                $expiry_date .= '-01';
            }

            $batch_id = $batchModel->insert([
                'medicine_id' => $medicine_id,
                'batch_number' => $batch_number,
                'expiry_date' => $expiry_date,
                'current_quantity' => $quantity
            ]);

            if ($batch_id) {
                $movementModel->insert([
                    'batch_id' => $batch_id,
                    'movement_type' => 'in',
                    'quantity' => $quantity,
                    'user_id' => $_SESSION['user_id']
                ]);
            }

            $this->redirect('inventory?status=batch_added');
        } else {
            $this->redirect('inventory');
        }
    }
}
?>