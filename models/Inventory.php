<?php
class Inventory extends Model {
    
    public function getAll($search = null) {
        $sql = "SELECT * FROM inventory WHERE 1=1";
        
        if ($search) {
            $search = $this->escape($search);
            $sql .= " AND item_name LIKE '%$search%'";
        }
        
        $sql .= " ORDER BY item_name ASC";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM inventory WHERE inventory_id = $id LIMIT 1";
        $result = $this->query($sql);
        return $this->fetch($result);
    }
    
    public function create($data) {
        $item_name = $this->escape($data['item_name']);
        $quantity = (int)$data['quantity'];
        $unit = $this->escape($data['unit']);
        $reorder_level = (int)$data['reorder_level'];
        
        $sql = "INSERT INTO inventory (item_name, quantity, unit, reorder_level) 
                VALUES ('$item_name', $quantity, '$unit', $reorder_level)";
        
        return $this->query($sql);
    }
    
    public function update($id, $data) {
        $id = (int)$id;
        $item_name = $this->escape($data['item_name']);
        $quantity = (int)$data['quantity'];
        $unit = $this->escape($data['unit']);
        $reorder_level = (int)$data['reorder_level'];
        
        $sql = "UPDATE inventory SET 
                item_name = '$item_name',
                quantity = $quantity,
                unit = '$unit',
                reorder_level = $reorder_level
                WHERE inventory_id = $id";
        
        return $this->query($sql);
    }
    
    public function delete($id) {
        $id = (int)$id;
        $sql = "DELETE FROM inventory WHERE inventory_id = $id";
        return $this->query($sql);
    }
    
    public function updateQuantity($id, $quantity, $operation = 'add') {
        $id = (int)$id;
        $quantity = (int)$quantity;
        
        if ($operation === 'add') {
            $sql = "UPDATE inventory SET quantity = quantity + $quantity WHERE inventory_id = $id";
        } else {
            $sql = "UPDATE inventory SET quantity = quantity - $quantity WHERE inventory_id = $id AND quantity >= $quantity";
        }
        
        return $this->query($sql);
    }
    
    public function getLowStock() {
        $sql = "SELECT * FROM inventory WHERE quantity <= reorder_level ORDER BY quantity ASC";
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getTotalItems() {
        $sql = "SELECT COUNT(*) as total FROM inventory";
        $result = $this->query($sql);
        $data = $this->fetch($result);
        return $data['total'];
    }
}
?>