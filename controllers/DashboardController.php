<?php
require_once '../core/Controller.php';

class DashboardController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $orderModel = $this->model('Order');
        $menuModel = $this->model('Menu');
        $saleModel = $this->model('Sale');
        $inventoryModel = $this->model('Inventory');
        
        $data = [
            'page_title' => 'Dashboard',
            'today_orders' => $orderModel->getTodayOrders(),
            'today_revenue' => $orderModel->getTodayRevenue(),
            'low_stock_items' => $menuModel->getLowStock(10),
            'recent_orders' => $orderModel->getRecentOrders(5),
            'best_sellers' => $saleModel->getBestSellingItems(5),
            'sales_trend' => $saleModel->getSalesTrend(7),
            'total_menu_items' => count($menuModel->getAll()),
            'total_inventory' => $inventoryModel->getTotalItems()
        ];
        
        $this->view('dashboard/index', $data);
    }
}
?>