<?php
class Controller {
    
    protected function model($model) {
        require_once '../models/' . $model . '.php';
        return new $model();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        require_once '../views/' . $view . '.php';
    }
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit();
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    protected function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
    }
    
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = 'Please login to continue';
            $this->redirect('/auth/login');
        }
    }
    
    protected function requireAdmin() {
        $this->requireAuth();
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            $this->redirect('/dashboard');
        }
    }
    
    protected function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    protected function validateRequired($fields) {
        $errors = [];
        foreach ($fields as $field => $value) {
            if (empty($value)) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }
        return $errors;
    }
}
?>