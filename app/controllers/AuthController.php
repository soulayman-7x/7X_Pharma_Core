<?php
class AuthController extends Controller {
    // default function display login page
    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        $toast = $this->getFlash('toast_alert');
        $this->view('login', ['toast' => $toast]);
    }

    // Function for processing data coming from the form
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            // Calling the user model
            $userModel = $this->model('User');

            $user = $userModel->verifyUser($username, $password);
            if($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                $this->redirect('dashboard');
            } else {
                $this->setFlash('toast_alert', 'Incorrect username or password', 'error');
                $this->redirect('auth');
            }
        }
    }

    // logout function
    public function logout() {
        session_destroy();
        $this->redirect('auth');
    }
}