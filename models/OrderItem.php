<?php
class OrderItem extends Model {
    
    public function create($data) {
        $order_id = (int)$data['order_id'];
        $menu_id = (int)$data['menu_id'];
        $quantity = (int)$data['quantity'];
        $price = (float)$data['price'];
        $subtotal = (float)$data['subtotal'];
        
        $sql = "INSERT INTO order_items (order_id, menu_id, quantity, price, subtotal) 
                VALUES ($order_id, $menu_id, $quantity, $price, $subtotal)";
        
        return $this->query($sql);
    }
    
    public function getByOrderId($order_id) {
        $order_id = (int)$order_id;
        $sql = "SELECT oi.*, m.name as item_name, m.category 
                FROM order_items oi
                LEFT JOIN menu_items m ON oi.menu_id = m.menu_id
                WHERE oi.order_id = $order_id";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function deleteByOrderId($order_id) {
        $order_id = (int)$order_id;
        $sql = "DELETE FROM order_items WHERE order_id = $order_id";
        return $this->query($sql);
    }
}
?>