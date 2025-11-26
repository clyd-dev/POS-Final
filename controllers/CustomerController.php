<?php
require_once '../core/Controller.php';

class CustomerController extends Controller {
    
    public function menu() {
        $menuModel = $this->model('Menu');
        
        $category = isset($_GET['category']) ? $this->sanitize($_GET['category']) : null;
        
        $data = [
            'page_title' => 'Our Menu',
            'menu_items' => $menuModel->getAvailable($category),
            'categories' => $menuModel->getCategories(),
            'current_category' => $category
        ];
        
        $this->view('customer/menu', $data);
    }
    
    public function home() {
        $this->redirect('/customer/menu');
    }
}
?>