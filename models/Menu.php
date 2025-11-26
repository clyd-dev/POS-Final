<?php
class Menu extends Model {
    
    public function getAll($category = null, $search = null) {
        $sql = "SELECT * FROM menu_items WHERE 1=1";
        
        if ($category) {
            $category = $this->escape($category);
            $sql .= " AND category = '$category'";
        }
        
        if ($search) {
            $search = $this->escape($search);
            $sql .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getAvailable($category = null) {
        $sql = "SELECT * FROM menu_items WHERE status = 'Available' AND stock > 0";
        
        if ($category) {
            $category = $this->escape($category);
            $sql .= " AND category = '$category'";
        }
        
        $sql .= " ORDER BY name ASC";
        
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM menu_items WHERE menu_id = $id LIMIT 1";
        $result = $this->query($sql);
        return $this->fetch($result);
    }
    
    public function create($data) {
        $name = $this->escape($data['name']);
        $category = $this->escape($data['category']);
        $description = $this->escape($data['description']);
        $price = (float)$data['price'];
        $stock = (int)$data['stock'];
        $image = isset($data['image']) ? $this->escape($data['image']) : '';
        
        $sql = "INSERT INTO menu_items (name, category, description, price, stock, image) 
                VALUES ('$name', '$category', '$description', $price, $stock, '$image')";
        
        return $this->query($sql);
    }
    
    public function update($id, $data) {
        $id = (int)$id;
        $name = $this->escape($data['name']);
        $category = $this->escape($data['category']);
        $description = $this->escape($data['description']);
        $price = (float)$data['price'];
        $stock = (int)$data['stock'];
        $status = $this->escape($data['status']);
        
        $sql = "UPDATE menu_items SET 
                name = '$name',
                category = '$category',
                description = '$description',
                price = $price,
                stock = $stock,
                status = '$status'
                WHERE menu_id = $id";
        
        return $this->query($sql);
    }
    
    public function delete($id) {
        $id = (int)$id;
        $sql = "DELETE FROM menu_items WHERE menu_id = $id";
        return $this->query($sql);
    }
    
    public function updateStock($id, $quantity, $operation = 'decrease') {
        $id = (int)$id;
        $quantity = (int)$quantity;
        
        if ($operation === 'decrease') {
            $sql = "UPDATE menu_items SET stock = stock - $quantity WHERE menu_id = $id AND stock >= $quantity";
        } else {
            $sql = "UPDATE menu_items SET stock = stock + $quantity WHERE menu_id = $id";
        }
        
        return $this->query($sql);
    }
    
    public function getLowStock($threshold = 10) {
        $threshold = (int)$threshold;
        $sql = "SELECT * FROM menu_items WHERE stock <= $threshold AND status = 'Available' ORDER BY stock ASC";
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM menu_items ORDER BY category ASC";
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
}
?>