<?php
require_once '../core/Controller.php';

class OrderController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $orderModel = $this->model('Order');
        
        $filters = [
            'order_number' => isset($_GET['order_number']) ? $this->sanitize($_GET['order_number']) : null,
            'start_date' => isset($_GET['start_date']) ? $this->sanitize($_GET['start_date']) : null,
            'end_date' => isset($_GET['end_date']) ? $this->sanitize($_GET['end_date']) : null,
            'payment_status' => isset($_GET['payment_status']) ? $this->sanitize($_GET['payment_status']) : null
        ];
        
        $data = [
            'page_title' => 'Orders',
            'orders' => $orderModel->getAll($filters),
            'filters' => $filters
        ];
        
        $this->view('orders/index', $data);
    }
    
    public function create() {
        $this->requireAuth();
        
        $menuModel = $this->model('Menu');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_data = [
                'user_id' => $_SESSION['user_id'],
                'customer_name' => $this->sanitize($_POST['customer_name']),
                'total_amount' => (float)$_POST['total_amount'],
                'payment_method' => $this->sanitize($_POST['payment_method']),
                'payment_status' => 'Paid'

            ];
            
            $items = [];
            if (isset($_POST['items']) && is_array($_POST['items'])) {
                foreach ($_POST['items'] as $item) {
                    $items[] = [
                        'menu_id' => (int)$item['menu_id'],
                        'quantity' => (int)$item['quantity'],
                        'price' => (float)$item['price'],
                        'subtotal' => (float)$item['subtotal']
                    ];
                }
            }
            
            if (empty($items)) {
                $_SESSION['error'] = 'Please add at least one item to the order';
            } else {
                $orderModel = $this->model('Order');
                $order_id = $orderModel->create($order_data, $items);
                
                if ($order_id) {
                    $_SESSION['success'] = 'Order created successfully';
                    $this->redirect('/order/receipt/' . $order_id);
                } else {
                    $_SESSION['error'] = 'Failed to create order. Please check stock availability.';
                }
            }
        }
        
        $data = [
            'page_title' => 'New Order',
            'menu_items' => $menuModel->getAvailable()
        ];
        
        $this->view('orders/create', $data);
    }
    
    public function receipt($id) {
        $this->requireAuth();
        
        $orderModel = $this->model('Order');
        $order = $orderModel->getById($id);
        
    // In receipt() method
if (!$order) {
    $_SESSION['error'] = 'Order not found';  // Fixed typo
    $this->redirect('/order');
}
        
        $data = [
            'page_title' => 'Order Receipt',
            'order' => $order,
            'items' => $orderModel->getOrderItems($id)
        ];
        
        $this->view('orders/receipt', $data);
    }
    
    public function cancel($id) {
        $this->requireAdmin();
        
        $orderModel = $this->model('Order');
        
        if ($orderModel->cancelOrder($id)) {
            $_SESSION['success'] = 'Order cancelled and stock restored';
        } else {
            $_SESSION['error'] = 'Failed to cancel order';
        }
        
        $this->redirect('/order');
    }
}
?>