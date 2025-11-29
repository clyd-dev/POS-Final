<?php
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost/POS-Final/public');

// Autoload core files
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/App.php';

// Start application
$app = new App();
?>