<?php
class Sale extends Model {
    
    public function getDailySales($start_date = null, $end_date = null) {
        $sql = "SELECT * FROM sales_summary WHERE 1=1";
        
        if ($start_date) {
            $start_date = $this->escape($start_date);
            $sql .= " AND sale_date >= '$start_date'";
        }
        
        if ($end_date) {
            $end_date = $this->escape($end_date);
            $sql .= " AND sale_date <= '$end_date'";
        }
        
        $sql .= " ORDER BY sale_date DESC";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getTodaySales() {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM sales_summary WHERE sale_date = '$today' LIMIT 1";
        $result = $this->query($sql);
        return $this->fetch($result);
    }
    
    public function getWeeklySales() {
        $sql = "SELECT 
                    COALESCE(SUM(total_orders), 0) as total_orders,
                    COALESCE(SUM(total_revenue), 0) as total_revenue
                FROM sales_summary 
                WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        
        $result = $this->query($sql);
        return $this->fetch($result);
    }
    
    public function getMonthlySales() {
        $sql = "SELECT 
                    COALESCE(SUM(total_orders), 0) as total_orders,
                    COALESCE(SUM(total_revenue), 0) as total_revenue
                FROM sales_summary 
                WHERE MONTH(sale_date) = MONTH(CURDATE()) 
                AND YEAR(sale_date) = YEAR(CURDATE())";
        
        $result = $this->query($sql);
        return $this->fetch($result);
    }
    
    public function getBestSellingItems($limit = 10) {
        $limit = (int)$limit;
        $sql = "SELECT 
                    m.name,
                    m.category,
                    m.price,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.subtotal) as total_revenue
                FROM order_items oi
                LEFT JOIN menu_items m ON oi.menu_id = m.menu_id
                GROUP BY oi.menu_id
                ORDER BY total_sold DESC
                LIMIT $limit";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getSalesByCategory() {
        $sql = "SELECT 
                    m.category,
                    SUM(oi.quantity) as total_quantity,
                    SUM(oi.subtotal) as total_revenue
                FROM order_items oi
                LEFT JOIN menu_items m ON oi.menu_id = m.menu_id
                GROUP BY m.category
                ORDER BY total_revenue DESC";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getSalesTrend($days = 7) {
        $days = (int)$days;
        $sql = "SELECT 
                    sale_date,
                    total_orders,
                    total_revenue
                FROM sales_summary
                WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
                ORDER BY sale_date ASC";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getTotalRevenue() {
        $sql = "SELECT COALESCE(SUM(total_revenue), 0) as total FROM sales_summary";
        $result = $this->query($sql);
        $data = $this->fetch($result);
        return $data['total'];
    }
}
?>