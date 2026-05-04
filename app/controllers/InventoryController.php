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
        $medicines = $medicineModel->getAll();

        // Sending data to the inventory interface
        $this->view('inventory', [
            'medicines' => $medicines
        ]);
    }

    // 2. add new medicine
    // link: /inventory/add
    public function add() {
        // If the order is of type POST (meaning the pharmacist pressed the "save medicine" button)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $medicineModel = $this->model('Medicine');

            // Prepare the data from the form into a sorted array
            $data = [
                'name' => trim($_POST['name']),
                'barcode' => trim($_POST['barcode']),
                'dci' => trim($_POST['dci']),
                'category' => trim($_POST['category']),
                'pvp' => $_POST['pvp'],
                'pph' => $_POST['pph'],
                'is_tableau_b' => isset($_POST['is_tableau_b']) ? 1 : 0
            ];

            $medicineModel->insert($data);

            $this->redirect('inventory?status=added');
        } else {
            // If it's not POST, it means the user only wants to "see" the extension's interface.
            $this->view('add-medicine');
        }
    }

    // 3. edit medicine
    // link: /inventory/edit/5

    public function edit($id = null) {
        // If the ID are not being processed via the link, return it to the inventory.
        if (!$id) {
            $this->redirect('inventory');
        }

        $medicineModel = $this->model('Medicine');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'barcode' => trim($_POST['barcode']),
                'dci' => trim($_POST['dci']),
                'category' => trim($_POST['category']),
                'ppv' => $_POST['ppv'],
                'pph' => $_POST['pph'],
                'is_tableau_b' => isset($_POST['is_tableau_b']) ? 1 : 0
            ];

            // update the medicine by the ID selected
            $medicineModel->update($id, $data);
            $this->redirect('inventory?status=updated');
        } else {
            $medicine = $medicineModel->getById($id);

            if (!$medicine) {
                $this->redirect('inventory');
            }

            // Display the editing interface and send the drug data to it.
            $this->view('edit-medicine', ['medicine' => $medicine]);
        }
    }

    // delete medicine
    // link: inventory/delete/5
    public function delete($id = null) {
        if ($id) {
            $medicineModel = $this->model('Medicine');
            $medicineModel->delete($id);
        }
        $this->redirect('inventory?status=deleted');
    }
}

