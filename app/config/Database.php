<?php 
require_once 'Constants.php';

class Database {
    private $conn;

    public function __construct() {
        $host = '127.0.0.1';
        $port = '3307'; 
        $db_name = 'pharma_core';
        $username = 'root';
        $password = '';

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn = new PDO($dsn, $username, $password, $options);
    
        } catch(PDOException $e) {
            $this->logError($e);

            $error_view = ROOT_DIR . '/views/components/db_error.php';

            if (file_exists($error_view)) {
                include $error_view;
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                echo "Database Connection Error. Design file missing.";
            }
            exit();
        }
    } 

    private function logError($e) {
        $logDir = ROOT_DIR . '/logs/';
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . 'db.errors.log';

        // error data
        $timestamp = date("Y-m-d H:i");
        $logMessage = "--------------------------------------------------\n";
        $logMessage .= "[{$timestamp}] 7X PHARMA CORE ERROR\n"; 
        $logMessage .= "Message : " . $e->getMessage() . "\n";
        $logMessage .= "File    : " . $e->getFile() . "(Line : " . $e->getLine() . ")\n";
        $logMessage .= "--------------------------------------------------\n\n";

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }  

    public function getConnection() {
        return $this->conn;
    }
}

?>