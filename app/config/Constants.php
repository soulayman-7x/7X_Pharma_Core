<?php 
// 1. Paths
define('BASE_URL', $_ENV['APP_URL'] ?? 'http://localhost/7X_Pharma_Core/public');
define('ROOT_DIR', dirname(__DIR__, 2));

// 2. Financial constants of the Moroccan market
define('CURRENCY', 'MAD');
define('TVA_MEDICINE', 0.07); // 7% = الضريبة المغربية على الادوية 
define('TVA_PARA', 0.20); // 20% = الضريبة المغربية على مواد التجميل

// 3. UI Settings
define('PAGINATION_LIMIT', 50); // عدد العناصر الافتراضي في الجداول 
define('APP_NAME', '7X Pharma Core');

// 4. Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_PHARMACIST', 'pharmacist');

?>