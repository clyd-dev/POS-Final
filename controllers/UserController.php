<?php
require_once '../core/Controller.php';

class UserController extends Controller {
    
    public function index() {
        $this->requireAdmin();
        
        $userModel = $this->model('User');
        
        $data = [
            'page_title' => 'User Management',
            'users' => $userModel->getAll()
        ];
        
        $this->view('users/index', $data);
    }
    
    public function create() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $this->sanitize($_POST['username']),
                'password' => $_POST['password'],
                'full_name' => $this->sanitize($_POST['full_name']),
                'email' => $this->sanitize($_POST['email']),
                'role' => $this->sanitize($_POST['role'])
            ];
            
            $userModel = $this->model('User');
            
            if ($userModel->usernameExists($data['username'])) {
                $_SESSION['error'] = 'Username already exists';
            } elseif ($userModel->register($data)) {
                $_SESSION['success'] = 'User created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create user';
            }
        }
        
        $this->redirect('/user');
    }
    
    public function update($id) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $this->sanitize($_POST['full_name']),
                'email' => $this->sanitize($_POST['email']),
                'role' => $this->sanitize($_POST['role']),
                'status' => $this->sanitize($_POST['status'])
            ];
            
            $userModel = $this->model('User');
            
            if ($userModel->update($id, $data)) {
                $_SESSION['success'] = 'User updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update user';
            }
        }
        
        $this->redirect('/user');
    }
    
    public function delete($id) {
        $this->requireAdmin();
        
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'You cannot delete your own account';
            $this->redirect('/user');
        }
        
        $userModel = $this->model('User');
        
        if ($userModel->delete($id)) {
            $_SESSION['success'] = 'User deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete user';
        }
        
        $this->redirect('/user');
    }
    
    public function changePassword($id) {
        $this->requireAuth();
        
        if ($id != $_SESSION['user_id'] && !$this->isAdmin()) {
            $_SESSION['error'] = 'Access denied';
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password !== $confirm_password) {
                $_SESSION['error'] = 'Passwords do not match';
            } elseif (strlen($new_password) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters';
            } else {
                $userModel = $this->model('User');
                
                if ($userModel->changePassword($id, $new_password)) {
                    $_SESSION['success'] = 'Password changed successfully';
                } else {
                    $_SESSION['error'] = 'Failed to change password';
                }
            }
        }
        
        $this->redirect('/user');
    }
}
?>