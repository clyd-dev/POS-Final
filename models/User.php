<?php
class User extends Model {
    
    public function login($username, $password) {
        $username = $this->escape($username);
        
        $sql = "SELECT * FROM users WHERE username = '$username' AND status = 'Active' LIMIT 1";
        $result = $this->query($sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = $this->fetch($result);
            
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return false;
    }
    
    public function register($data) {
        $username = $this->escape($data['username']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $full_name = $this->escape($data['full_name']);
        $email = $this->escape($data['email']);
        $role = $this->escape($data['role']);
        
        $sql = "INSERT INTO users (username, password, full_name, email, role) 
                VALUES ('$username', '$password', '$full_name', '$email', '$role')";
        
        return $this->query($sql);
    }
    
    public function getAll() {
        $sql = "SELECT user_id, username, full_name, email, role, status, created_at 
                FROM users ORDER BY created_at DESC";
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }
    
    public function getById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM users WHERE user_id = $id LIMIT 1";
        $result = $this->query($sql);
        return $this->fetch($result);
    }
    
    public function update($id, $data) {
        $id = (int)$id;
        $full_name = $this->escape($data['full_name']);
        $email = $this->escape($data['email']);
        $role = $this->escape($data['role']);
        $status = $this->escape($data['status']);
        
        $sql = "UPDATE users SET 
                full_name = '$full_name',
                email = '$email',
                role = '$role',
                status = '$status'
                WHERE user_id = $id";
        
        return $this->query($sql);
    }
    
    public function delete($id) {
        $id = (int)$id;
        $sql = "DELETE FROM users WHERE user_id = $id";
        return $this->query($sql);
    }
    
    public function changePassword($id, $new_password) {
        $id = (int)$id;
        $password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password = '$password' WHERE user_id = $id";
        return $this->query($sql);
    }
    
    public function usernameExists($username, $exclude_id = null) {
        $username = $this->escape($username);
        $sql = "SELECT user_id FROM users WHERE username = '$username'";
        
        if ($exclude_id) {
            $exclude_id = (int)$exclude_id;
            $sql .= " AND user_id != $exclude_id";
        }
        
        $result = $this->query($sql);
        return mysqli_num_rows($result) > 0;
    }
}
?>