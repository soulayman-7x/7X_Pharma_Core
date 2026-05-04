<?php 
session_start();

// Define the project fixed path for easy file retrieval
define('ROOT_DIR', __DIR__);

require_once ROOT_DIR . '/app/config/Database.php';
require_once ROOT_DIR . '/app/core/Model.php';
require_once ROOT_DIR . '/app/core/Controller.php';
require_once ROOT_DIR . '/app/core/Router.php';

// Activate the router, which will read the link and guide us.
$router = new Router();
?>