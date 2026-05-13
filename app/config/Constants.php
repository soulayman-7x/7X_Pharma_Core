<?php 
// 1. Paths
define('BASE_URL', $_ENV['APP_URL'] ?? 'http://localhost/7X_Pharma_Core/public');
define('ROOT_DIR', dirname(__DIR__, 2));

// 3. UI Settings
define('APP_NAME', '7X Pharma Core');

// 4. Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_PHARMACIST', 'pharmacist');

?>