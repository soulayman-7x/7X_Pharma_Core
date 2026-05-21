<?php

class Controller {
    public function requireRoles($allowedRoles = []) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth');
        }
        if (!empty($allowedRoles)) {
            if (!in_array($_SESSION['role'], $allowedRoles)){
                $this->redirect('pos');
            }
        }
    }
    // 1. Interface display function (view)

    public function view($view, $data = [] ) {
        // استخراج المصفوفة وتحويل مفاتيحها الى متغيرات
        if (!empty($data)) {
            extract($data); // like : ['users' => $data] = $users
        }

        $viewFile = ROOT_DIR . '/views/pages/' . $view .'.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: View [{$view}] does not exist.");
        }
    }

    // 2. Form call function
    public function model($model) {
        $modelFile = ROOT_DIR . '/app/models/' . $model . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model;
        } else {
            die("Error: Model [{$model}] does not exist.");
        }
    }

    // 3. Redirect function
    public function redirect($url) {
        header("Location: " . BASE_URL . "/" . $url);
        exit();
    }

    // toasts
    public function setFlash($key, $message, $type = 'error') {
        $_SESSION[$key] = [
            'message' => $message,
            'type'   => $type
        ];
    }

    public function getFlash($key) {
        if (isset($_SESSION[$key])) {
            $flashData = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $flashData;
        }
        return null;
    }
}
?>