-- Create Database
CREATE DATABASE IF NOT EXISTS inventory_system;
USE inventory_system;

-- Table 1: Category Management
CREATE TABLE IF NOT EXISTS tbl_category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table 2: Supplier Management
CREATE TABLE IF NOT EXISTS tbl_supplier (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(150) NOT NULL,
    contact_person VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table 3: Product Management (with Foreign Keys)
CREATE TABLE IF NOT EXISTS tbl_product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(150) NOT NULL,
    category_id INT NOT NULL,
    supplier_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES tbl_category(category_id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES tbl_supplier(supplier_id) ON DELETE CASCADE
);

-- Insert Sample Categories
INSERT INTO tbl_category (category_name, description) VALUES
('Electronics', 'Electronic devices and gadgets'),
('Groceries', 'Food and daily necessities'),
('Furniture', 'Home and office furniture'),
('Clothing', 'Apparel and accessories'),
('Sports Equipment', 'Sports and fitness items');

-- Insert Sample Suppliers
INSERT INTO tbl_supplier (supplier_name, contact_person, contact_number, address) VALUES
('ABC Trading Corp.', 'Juan Dela Cruz', '09171234567', '123 Main St, Manila'),
('XYZ Electronics', 'Maria Santos', '09189876543', '456 Tech Ave, Quezon City'),
('Global Imports Inc.', 'Pedro Reyes', '09123456789', '789 Business Blvd, Makati'),
('Prime Distributors', 'Ana Garcia', '09198765432', '321 Commerce St, Pasig'),
('Metro Suppliers', 'Jose Mendoza', '09156789012', '654 Supply Rd, Mandaluyong');

-- Insert Sample Products
INSERT INTO tbl_product (product_name, category_id, supplier_id, quantity, price, description) VALUES
('Laptop', 1, 1, 50, 25000.00, 'High-performance laptop for business'),
('Rice 25kg', 2, 3, 200, 1250.00, 'Premium quality rice'),
('Office Chair', 3, 4, 75, 3500.00, 'Ergonomic office chair'),
('Smartphone', 1, 2, 100, 15000.00, 'Latest model smartphone'),
('T-Shirt', 4, 5, 300, 250.00, 'Cotton t-shirt'),
('Basketball', 5, 1, 150, 800.00, 'Official size basketball'),
('LED Monitor', 1, 2, 80, 8500.00, '24-inch LED monitor'),
('Coffee Beans 1kg', 2, 3, 120, 450.00, 'Arabica coffee beans'),
('Study Desk', 3, 4, 40, 4500.00, 'Wooden study desk'),
('Running Shoes', 5, 5, 90, 2500.00, 'Professional running shoes');

-- Create indexes for better performance
CREATE INDEX idx_category_name ON tbl_category(category_name);
CREATE INDEX idx_supplier_name ON tbl_supplier(supplier_name);
CREATE INDEX idx_product_name ON tbl_product(product_name);
CREATE INDEX idx_product_category ON tbl_product(category_id);
CREATE INDEX idx_product_supplier ON tbl_product(supplier_id);