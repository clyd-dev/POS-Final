<?php
class App {
    protected $controller = 'DashboardController';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct() {
        session_start();
        
        $url = $this->parseUrl();
        
        // Check if user is logged in for protected routes
        if (!isset($_SESSION['user_id']) && (!isset($url[0]) || $url[0] !== 'auth') && (!isset($url[0]) || $url[0] !== 'customer')) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit();
        }
        
        // Controller
        if (isset($url[0]) && file_exists('../controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }
        
        require_once '../controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        
        // Method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }
        
        // Parameters
        $this->params = $url ? array_values($url) : [];
        
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}
?>