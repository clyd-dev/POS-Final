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
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/menu/';  // Absolute server path to web root
                $web_path = '/uploads/menu/';  // Web-accessible path
                
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_name = basename($_FILES['image']['name']);
                $unique_name = time() . '_' . $file_name;
                $target_file = $upload_dir . $unique_name;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
                $max_size = 5 * 1024 * 1024; // 5MB
                
                if (in_array($imageFileType, $allowed_types) && $_FILES['image']['size'] <= $max_size) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                        $data['image'] = $web_path . $unique_name;  // Store web path (e.g., /uploads/menu/123456_image.jpg)
                    } else {
                        $errors[] = 'Failed to upload image. Check server permissions for ' . $upload_dir;
                    }
                } else {
                    $errors[] = 'Invalid image file. Allowed formats: JPG, PNG, JPEG, GIF. Max size: 5MB.';
                }
            }
            
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
                'status' => $this->sanitize($_POST['status']),
                'image' => $this->sanitize($_POST['image'])
            ];
            
            // Handle image upload (retain existing if no new upload)
            $data['image'] = $item['image']; // Default to existing image
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/menu/';  // Absolute server path
                $web_path = '/uploads/menu/';  // Web-accessible path
                
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_name = basename($_FILES['image']['name']);
                $unique_name = time() . '_' . $file_name;
                $target_file = $upload_dir . $unique_name;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
                $max_size = 5 * 1024 * 1024; // 5MB
                
                if (in_array($imageFileType, $allowed_types) && $_FILES['image']['size'] <= $max_size) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                        $data['image'] = $web_path . $unique_name;  // Update to new web path
                    } else {
                        $_SESSION['error'] = 'Failed to upload image. Check server permissions for ' . $upload_dir;
                        $this->redirect('/menu/edit/' . $id);
                    }
                } else {
                    $_SESSION['error'] = 'Invalid image file. Allowed formats: JPG, PNG, JPEG, GIF. Max size: 5MB.';
                    $this->redirect('/menu/edit/' . $id);
                }
            }
            
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