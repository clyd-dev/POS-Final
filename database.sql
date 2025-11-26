-- Database: db_pos

CREATE DATABASE IF NOT EXISTS db_pos;
USE db_pos;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    role ENUM('Admin', 'Cashier') NOT NULL DEFAULT 'Cashier',
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu Items Table
CREATE TABLE menu_items (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category ENUM('Burger', 'Pizza', 'Pasta', 'Fries', 'Drinks', 'Dessert') NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    status ENUM('Available', 'Unavailable') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (price >= 0),
    CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders Table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    customer_name VARCHAR(100),
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('Cash', 'Card', 'Digital Wallet') NOT NULL,
    payment_status ENUM('Paid', 'Pending') DEFAULT 'Paid',
    order_status ENUM('Completed', 'Cancelled', 'Pending') DEFAULT 'Completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    CHECK (total_amount >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items Table
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu_items(menu_id) ON DELETE RESTRICT,
    CHECK (quantity > 0),
    CHECK (price >= 0),
    CHECK (subtotal >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inventory Table
CREATE TABLE inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    unit VARCHAR(20) NOT NULL,
    reorder_level INT DEFAULT 10,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (quantity >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sales Summary Table (for reporting)
CREATE TABLE sales_summary (
    summary_id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATE NOT NULL,
    total_orders INT DEFAULT 0,
    total_revenue DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (sale_date),
    CHECK (total_orders >= 0),
    CHECK (total_revenue >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, full_name, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@pos.com', 'Admin');

-- Sample menu items
INSERT INTO menu_items (name, category, description, price, stock, image) VALUES
('Delicious Pizza', 'Pizza', 'A flavorful pizza made with fresh ingredients and rich cheese.', 20.00, 50, 'f1.png'),
('Delicious Burger', 'Burger', 'A juicy burger topped with fresh vegetables and special sauce.', 15.00, 40, 'f2.png'),
('Delicious Pizza', 'Pizza', 'A classic cheesy pizza with a crispy crust and savory toppings.', 17.00, 45, 'f3.png'),
('Delicious Pasta', 'Pasta', 'Creamy and delicious pasta cooked with quality herbs and spices.', 18.00, 35, 'f4.png'),
('French Fries', 'Fries', 'Crispy and golden fries served fresh and hot.', 10.00, 60, 'f5.png'),
('Delicious Pizza', 'Pizza', 'A well-seasoned pizza with a rich tomato base and melted cheese.', 15.00, 50, 'f6.png'),
('Tasty Burger', 'Burger', 'A tasty grilled burger packed with flavor and freshness.', 12.00, 40, 'f7.png'),
('Tasty Burger', 'Burger', 'A flavorful burger prepared with tender meat and tasty toppings.', 14.00, 35, 'f8.png'),
('Delicious Pasta', 'Pasta', 'A delightful pasta dish served with a creamy savory sauce.', 10.00, 30, 'f9.png');

```
