<?php
require_once '../core/Controller.php';

class MenuController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $menuModel = $this->model('Menu');
        
        $category = isset($_GET['category']) ? $this->sanitize($_GET['category']) : null;
        $search = isset($_GET['search']) ? $this->sanitize($_GET['search']) : null;
        
        $data = [
            'page_title' => 'Menu Management',
            'menu_items' => $menuModel->getAll($category, $search),
            'categories' => $menuModel->getCategories(),
            'current_category' => $category,
            'current_search' => $search
        ];
        
        $this->view('menu/index', $data);
    }
    
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $this->sanitize($_POST['name']),
                'category' => $this->sanitize($_POST['category']),
                'description' => $this->sanitize($_POST['description']),
                'price' => (float)$_POST['price'],
                'stock' => (int)$_POST['stock']
            ];
            
            $errors = $this->validateRequired([
                'name' => $data['name'],
                'category' => $data['category'],
                'price' => $data['price']
            ]);
            
            if (empty($errors)) {
                $menuModel = $this->model('Menu');
                
                if ($menuModel->create($data)) {
                    $_SESSION['success'] = 'Menu item added successfully';
                    $this->redirect('/menu');
                } else {
                    $_SESSION['error'] = 'Failed to add menu item';
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        
        $data = ['page_title' => 'Add Menu Item'];
        $this->view('menu/create', $data);
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $menuModel = $this->model('Menu');
        $item = $menuModel->getById($id);
        
        if (!$item) {
            $_SESSION['error'] = 'Menu item not found';
            $this->redirect('/menu');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $this->sanitize($_POST['name']),
                'category' => $this->sanitize($_POST['category']),
                'description' => $this->sanitize($_POST['description']),
                'price' => (float)$_POST['price'],
                'stock' => (int)$_POST['stock'],
                'status' => $this->sanitize($_POST['status'])
            ];
            
            if ($menuModel->update($id, $data)) {
                $_SESSION['success'] = 'Menu item updated successfully';
                $this->redirect('/menu');
            } else {
                $_SESSION['error'] = 'Failed to update menu item';
            }
        }
        
        $data = [
            'page_title' => 'Edit Menu Item',
            'item' => $item
        ];
        
        $this->view('menu/edit', $data);
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        $menuModel = $this->model('Menu');
        
        if ($menuModel->delete($id)) {
            $_SESSION['success'] = 'Menu item deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete menu item. It may be referenced in orders.';
        }
        
        $this->redirect('/menu');
    }
}
?>