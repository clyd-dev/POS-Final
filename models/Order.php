<?php
class Order extends Model {
    
    public function create($order_data, $items) {
        // Start transaction
        mysqli_begin_transaction($this->conn);
        
        try {
            // Generate order number
            $order_number = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Insert order
            $user_id = (int)$order_data['user_id'];
            $customer_name = $this->escape($order_data['customer_name']);
            $total_amount = (float)$order_data['total_amount'];
            $payment_method = $this->escape($order_data['payment_method']);
            $payment_status = $this->escape($order_data['payment_status']);
            
            $sql = "INSERT INTO orders (order_number, user_id, customer_name, total_amount, payment_method, payment_status) 
                    VALUES ('$order_number', $user_id, '$customer_name', $total_amount, '$payment_method', '$payment_status')";
            
            if (!$this->query($sql)) {
                throw new Exception("Failed to create order");
            }
            
            $order_id = $this->lastInsertId();
            
            // Insert order items and update stock
            foreach ($items as $item) {
                $menu_id = (int)$item['menu_id'];
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];
                $subtotal = (float)$item['subtotal'];
                
                // Insert order item
                $sql = "INSERT INTO order_items (order_id, menu_id, quantity, price, subtotal) 
                        VALUES ($order_id, $menu_id, $quantity, $price, $subtotal)";
                
                if (!$this->query($sql)) {
                    throw new Exception("Failed to add order item");
                }
                
                // Update menu stock
                $sql = "UPDATE menu_items SET stock = stock - $quantity 
                        WHERE menu_id = $menu_id AND stock >= $quantity";
                
                if (!$this->query($sql) || $this->affectedRows() == 0) {
                    throw new Exception("Insufficient stock for item");
                }
            }
            
            // Update sales summary
            $sale_date = date('Y-m-d');
            $sql = "INSERT INTO sales_summary (sale_date, total_orders, total_revenue) 
                    VALUES ('$sale_date', 1, $total_amount)
                    ON DUPLICATE KEY UPDATE 
                    total_orders = total_orders + 1,
                    total_revenue = total_revenue + $total_amount";
            
            $this->query($sql);
            
            // Commit transaction
            mysqli_commit($this->conn);
            
            return $order_id;
            
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            error_log("Order Creation Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT o.*, u.full_name as cashier_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.user_id 
                WHERE 1=1";
        
        if (isset($filters['order_number']) && !empty($filters['order_number'])) {
            $order_number = $this->escape($filters['order_number']);
            $sql .= " AND o.order_number LIKE '%$order_number%'";
        }
        
        if (isset($filters['start_date']) && !empty($filters['start_date'])) {
            $start_date = $this->escape($filters['start_date']);
            $sql .= " AND DATE(o.created_at) >= '$start_date'";
        }
        
        if (isset($filters['end_date']) && !empty($filters['end_date'])) {
            $end_date = $this->escape($filters['end_date']);
            $sql .= " AND DATE(o.created_at) <= '$end_date'";
        }
        
        if (isset($filters['payment_status']) && !empty($filters['payment_status'])) {
            $payment_status = $this->escape($filters['payment_status']);
            $sql .= " AND o.payment_status = '$payment_status'";
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT o.*, u.full_name as cashier_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.user_id 
                WHERE o.order_id = $id LIMIT 1";
        
        $result = $this->query($sql);
        return $this->fetch($result);
    }
    
    public function getOrderItems($order_id) {
        $order_id = (int)$order_id;
        $sql = "SELECT oi.*, m.name as item_name, m.category 
                FROM order_items oi
                LEFT JOIN menu_items m ON oi.menu_id = m.menu_id
                WHERE oi.order_id = $order_id";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getTodayOrders() {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = '$today'";
        $result = $this->query($sql);
        $data = $this->fetch($result);
        return $data['total'];
    }
    
    public function getTodayRevenue() {
        $today = date('Y-m-d');
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total 
                FROM orders 
                WHERE DATE(created_at) = '$today' AND payment_status = 'Paid'";
        $result = $this->query($sql);
        $data = $this->fetch($result);
        return $data['total'];
    }
    
    public function cancelOrder($id) {
        mysqli_begin_transaction($this->conn);
        
        try {
            $id = (int)$id;
            
            // Get order items to restore stock
            $items = $this->getOrderItems($id);
            
            foreach ($items as $item) {
                $menu_id = (int)$item['menu_id'];
                $quantity = (int)$item['quantity'];
                
                $sql = "UPDATE menu_items SET stock = stock + $quantity WHERE menu_id = $menu_id";
                $this->query($sql);
            }
            
            // Update order status
            $sql = "UPDATE orders SET order_status = 'Cancelled' WHERE order_id = $id";
            $this->query($sql);
            
            mysqli_commit($this->conn);
            return true;
            
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            return false;
        }
    }
    
    public function getRecentOrders($limit = 10) {
        $limit = (int)$limit;
        $sql = "SELECT o.*, u.full_name as cashier_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.user_id 
                ORDER BY o.created_at DESC 
                LIMIT $limit";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
}
?>