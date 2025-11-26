<?php
require_once '../core/Controller.php';

class InventoryController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $inventoryModel = $this->model('Inventory');
        $search = isset($_GET['search']) ? $this->sanitize($_GET['search']) : null;
        
        $data = [
            'page_title' => 'Inventory Management',
            'inventory_items' => $inventoryModel->getAll($search),
            'low_stock_items' => $inventoryModel->getLowStock(),
            'current_search' => $search
        ];
        
        $this->view('inventory/index', $data);
    }
    
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'item_name' => $this->sanitize($_POST['item_name']),
                'quantity' => (int)$_POST['quantity'],
                'unit' => $this->sanitize($_POST['unit']),
                'reorder_level' => (int)$_POST['reorder_level']
            ];
            
            $inventoryModel = $this->model('Inventory');
            
            if ($inventoryModel->create($data)) {
                $_SESSION['success'] = 'Inventory item added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add inventory item';
            }
        }
        
        $this->redirect('/inventory');
    }
    
    public function update($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'item_name' => $this->sanitize($_POST['item_name']),
                'quantity' => (int)$_POST['quantity'],
                'unit' => $this->sanitize($_POST['unit']),
                'reorder_level' => (int)$_POST['reorder_level']
            ];
            
            $inventoryModel = $this->model('Inventory');
            
            if ($inventoryModel->update($id, $data)) {
                $_SESSION['success'] = 'Inventory item updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update inventory item';
            }
        }
        
        $this->redirect('/inventory');
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        $inventoryModel = $this->model('Inventory');
        
        if ($inventoryModel->delete($id)) {
            $_SESSION['success'] = 'Inventory item deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete inventory item';
        }
        
        $this->redirect('/inventory');
    }
}
?>