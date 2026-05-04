<?php

class Controller {
    // 1. Interface display function (view)

    public function view($view, $data = [] ) {
        // استخراج المصفوفة وتحويل مفاتيحها الى متغيرات
        if (!empty($data)) {
            extract($data);
        }

        $viewFile = ROOT_DIR . '/views/' . $view .'.php';
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

    // 3. Quick Response Function (JSON)
    public function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        // hna kan9olo l browser bli data jaya ka JSON
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
        exit;
    }

    // 3. Redirect function
    public function redirect($url) {
        header("Location: /" . $url);
        exit();
    }
}
?>