<?php
require_once '../core/Controller.php';

class SalesController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $saleModel = $this->model('Sale');
        
        $start_date = isset($_GET['start_date']) ? $this->sanitize($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
        $end_date = isset($_GET['end_date']) ? $this->sanitize($_GET['end_date']) : date('Y-m-d');
        
        $data = [
            'page_title' => 'Sales Reports',
            'daily_sales' => $saleModel->getDailySales($start_date, $end_date),
            'today_sales' => $saleModel->getTodaySales(),
            'weekly_sales' => $saleModel->getWeeklySales(),
            'monthly_sales' => $saleModel->getMonthlySales(),
            'best_sellers' => $saleModel->getBestSellingItems(10),
            'category_sales' => $saleModel->getSalesByCategory(),
            'sales_trend' => $saleModel->getSalesTrend(30),
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
        
        $this->view('sales/index', $data);
    }
    
    public function export() {
        $this->requireAdmin();
        
        $saleModel = $this->model('Sale');
        
        $start_date = isset($_GET['start_date']) ? $this->sanitize($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
        $end_date = isset($_GET['end_date']) ? $this->sanitize($_GET['end_date']) : date('Y-m-d');
        
        $sales = $saleModel->getDailySales($start_date, $end_date);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sales_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['Date', 'Total Orders', 'Total Revenue']);
        
        foreach ($sales as $sale) {
            fputcsv($output, [
                $sale['sale_date'],
                $sale['total_orders'],
                number_format($sale['total_revenue'], 2)
            ]);
        }
        
        fclose($output);
        exit();
    }
}
?>