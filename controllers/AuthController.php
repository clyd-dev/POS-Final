<?php
require_once '../core/Controller.php';

class AuthController extends Controller {
    
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->sanitize($_POST['username']);
            $password = $_POST['password'];
            
            $errors = $this->validateRequired([
                'username' => $username,
                'password' => $password
            ]);
            
            if (empty($errors)) {
                $userModel = $this->model('User');
                $user = $userModel->login($username, $password);
                
                if ($user) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];
                    
                    $_SESSION['success'] = 'Welcome back, ' . $user['full_name'] . '!';
                    $this->redirect('/dashboard');
                } else {
                    $_SESSION['error'] = 'Invalid username or password';
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        $this->view('auth/login');
    }
    
    public function register() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $this->sanitize($_POST['username']),
                'password' => $_POST['password'],
                'full_name' => $this->sanitize($_POST['full_name']),
                'email' => $this->sanitize($_POST['email']),
                'role' => $this->sanitize($_POST['role'])
            ];
            
            $errors = $this->validateRequired($data);
            
            if (empty($errors)) {
                $userModel = $this->model('User');
                
                if ($userModel->usernameExists($data['username'])) {
                    $_SESSION['error'] = 'Username already exists';
                } elseif ($userModel->register($data)) {
                    $_SESSION['success'] = 'User registered successfully';
                    $this->redirect('/user');
                } else {
                    $_SESSION['error'] = 'Failed to register user';
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        $this->view('auth/register');
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/auth/login');
    }
}
?>