# Inventory Management System with AdminLTE 4

A complete **Inventory Management System** built with PHP OOP principles and AdminLTE 4 Dashboard v2, featuring Category, Supplier, and Product management with dynamic relationships and modern UI.

## 🎯 Features

### 1. **Category Management**
- ✅ Add new product categories
- ✅ Edit or delete existing categories
- ✅ Search categories by name or description
- ✅ Display all categories in a table
- **Fields**: Category ID (auto-generated), Category Name, Description

### 2. **Supplier Management**
- ✅ Add, edit, and delete supplier information
- ✅ Search suppliers by name, contact person, or number
- ✅ Display suppliers in a table view
- **Fields**: Supplier ID (auto-generated), Supplier Name, Contact Person, Contact Number, Address

### 3. **Product Management**
- ✅ Add new products with dropdown selections for:
  - **Category** (dynamically fetched from Category table)
  - **Supplier** (dynamically fetched from Supplier table)
- ✅ Edit, view, and delete products
- ✅ Search products by name, category, supplier, or description
- **Fields**: Product ID (auto-generated), Product Name, Category, Supplier, Quantity, Price, Description

## 📁 Project Structure

```
inventory_system/
│
├── config/
│   └── database.php          # Database connection class
│
├── includes/
│   ├── header.php             # AdminLTE header with navbar
│   ├── sidebar.php            # AdminLTE sidebar navigation
│   └── footer.php             # AdminLTE footer with scripts
│
├── dist/                      # AdminLTE 4 assets
│   ├── css/
│   │   └── adminlte.css
│   ├── js/
│   │   └── adminlte.js
│   └── assets/
│       └── img/
│
├── dashboard.php              # Dashboard with statistics
├── category.php               # Category management module
├── supplier.php               # Supplier management module
├── product.php                # Product management module
├── database_setup.sql         # SQL schema and sample data
└── README.md                  # This file
```

## 🚀 Installation & Setup

### Step 1: Database Setup

1. Open **phpMyAdmin** or your MySQL client
2. Import the `database_setup.sql` file or run the SQL commands manually
3. This will create:
   - Database: `inventory_system`
   - Tables: `tbl_category`, `tbl_supplier`, `tbl_product`
   - Sample data for testing

### Step 2: Install AdminLTE 4

1. **Download AdminLTE 4** from the official website or CDN
2. Extract the `dist/` folder to your project root
3. Ensure you have these folders:
   - `dist/css/adminlte.css`
   - `dist/js/adminlte.js`
   - `dist/assets/img/` (for images)

### Step 3: Configure Database Connection

Edit `config/database.php` and update the connection details:

```php
private $host = "localhost";
private $username = "root";      // Your MySQL username
private $password = "";          // Your MySQL password
private $database = "inventory_system";
```

### Step 4: Project Setup

1. Place all files in your web server directory:
   - XAMPP: `C:/xampp/htdocs/inventory_system/`
   - WAMP: `C:/wamp64/www/inventory_system/`

2. Start your web server (Apache) and MySQL

3. Access the system in your browser:
   - Dashboard: `http://localhost/inventory_system/dashboard.php`
   - Categories: `http://localhost/inventory_system/category.php`
   - Suppliers: `http://localhost/inventory_system/supplier.php`
   - Products: `http://localhost/inventory_system/product.php`

## 🎨 AdminLTE 4 Integration

### Features Implemented:
- **Modern Dashboard** with real-time statistics
- **Responsive Sidebar** with navigation menu
- **Card-based Forms** for better UX
- **Data Tables** with striped rows and hover effects
- **Alert Messages** with Bootstrap dismissible alerts
- **Badge Components** for status indicators
- **Icon Integration** with Bootstrap Icons
- **Mobile-Friendly** responsive design

### Color Scheme:
- **Categories** - Primary (Blue)
- **Suppliers** - Success (Green)
- **Products** - Warning (Yellow/Orange)
- **Dashboard** - Mixed colors for stats

### Layout Components:
1. **Header** (`includes/header.php`)
   - Top navigation bar
   - User dropdown menu
   - Fullscreen toggle
   
2. **Sidebar** (`includes/sidebar.php`)
   - Main navigation menu
   - Category, Supplier, Product links
   - Reports section (placeholder)
   
3. **Footer** (`includes/footer.php`)
   - Copyright information
   - JavaScript includes
   - OverlayScrollbars configuration

## 🎯 OOP Principles Applied (Backend Logic Intact)
### 1. **Inheritance**
All entity classes (`Category`, `Supplier`, `Product`, `Dashboard`) extend the `Database` class:
```php
class Product extends Database {
    public function __construct() {
        parent::__construct();
        $this->connect();
    }
}
```

### 2. **Encapsulation**
Database connection details are private and accessed through protected properties:
```php
private $host = "localhost";
protected $conn;
```

### 3. **Single Responsibility Principle**
- `Database` class: Handles connection only
- Entity classes: Handle their specific CRUD operations
- PHP files: Handle both class definition and UI presentation

### 4. **Code Reusability**
All classes reuse the `Database` connection through inheritance, avoiding code duplication.

**IMPORTANT**: All backend PHP logic (CRUD operations, database queries, OOP structure) remains **100% intact**. Only the frontend presentation layer was updated to use AdminLTE 4 components.

## 📊 Database Schema

### tbl_category
| Field | Type | Description |
|-------|------|-------------|
| category_id | INT (PK, AI) | Auto-generated ID |
| category_name | VARCHAR(100) | Category name |
| description | TEXT | Optional description |
| created_at | TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | Last update time |

### tbl_supplier
| Field | Type | Description |
|-------|------|-------------|
| supplier_id | INT (PK, AI) | Auto-generated ID |
| supplier_name | VARCHAR(150) | Supplier company name |
| contact_person | VARCHAR(100) | Contact person name |
| contact_number | VARCHAR(20) | Phone number |
| address | TEXT | Full address |
| created_at | TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | Last update time |

### tbl_product
| Field | Type | Description |
|-------|------|-------------|
| product_id | INT (PK, AI) | Auto-generated ID |
| product_name | VARCHAR(150) | Product name |
| category_id | INT (FK) | References tbl_category |
| supplier_id | INT (FK) | References tbl_supplier |
| quantity | INT | Stock quantity |
| price | DECIMAL(10,2) | Product price |
| description | TEXT | Optional description |
| created_at | TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | Last update time |

## 🔧 Key Features Implementation

### Dynamic Dropdowns
Products automatically fetch categories and suppliers:
```php
public function getAllCategories() {
    $result = $this->conn->query("SELECT * FROM tbl_category ORDER BY category_name ASC");
    return $result->fetch_all(MYSQLI_ASSOC);
}
```

### Search Functionality
All modules include search across relevant fields:
```php
public function searchProduct($keyword) {
    $query = "SELECT p.*, c.category_name, s.supplier_name 
              FROM tbl_product p
              LEFT JOIN tbl_category c ON p.category_id = c.category_id
              LEFT JOIN tbl_supplier s ON p.supplier_id = s.supplier_id
              WHERE p.product_name LIKE ? OR c.category_name LIKE ?";
    // ... implementation
}
```

### Foreign Key Relationships
Products have referential integrity with categories and suppliers:
```sql
FOREIGN KEY (category_id) REFERENCES tbl_category(category_id) ON DELETE CASCADE
```

## 🔐 Security Features

- ✅ Prepared statements for all database queries
- ✅ Parameter binding to prevent SQL injection
- ✅ `htmlspecialchars()` to prevent XSS attacks
- ✅ Confirmation dialogs for delete operations

## 📝 Sample Data Included

The system comes with pre-loaded sample data:
- 5 Categories (Electronics, Groceries, Furniture, Clothing, Sports Equipment)
- 5 Suppliers with complete contact information
- 10 Products distributed across categories and suppliers

## 🎯 Usage Examples

### Adding a Product
1. Navigate to `product.php`
2. Fill in product details
3. Select category from dropdown (e.g., "Electronics")
4. Select supplier from dropdown (e.g., "ABC Trading Corp.")
5. Enter quantity (e.g., 50) and price (e.g., 25000.00)
6. Click "Save"

### Searching
1. Use the search bar in any module
2. Enter keywords (searches across multiple fields)
3. View filtered results instantly

### Editing
1. Click "Edit" button on any record
2. Form populates with current data
3. Modify fields and click "Update"

## 🔄 Navigation

Each page includes navigation buttons:
- From Categories → Suppliers, Products
- From Suppliers → Categories, Products  
- From Products → Categories, Suppliers

## 📌 Notes

- All IDs are auto-generated by the database
- Delete operations cascade (deleting a category/supplier removes associated products)
- Timestamps are automatically managed
- Price formatting displays Philippine Peso (₱) symbol

## 🐛 Troubleshooting

**Error: "Connection failed"**
- Check database credentials in `config/database.php`
- Ensure MySQL service is running

**Error: "Table doesn't exist"**
- Import `database_setup.sql` in phpMyAdmin
- Verify database name is `inventory_system`

**Dropdown shows no options**
- Ensure categories and suppliers tables have data
- Check foreign key constraints in product table

## 📚 Technology Stack

- **Backend**: PHP 7.4+ with OOP
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3
- **Server**: Apache (XAMPP/WAMP)

## 👨‍💻 Development

This system follows the same architecture as your Student Information System but implements a complete inventory management solution with:
- Hierarchical data relationships
- Dynamic dropdown population
- Cross-table search functionality
- Referential integrity enforcement

---

**Ready to use!** Start with `category.php` to set up your categories, then add suppliers, and finally manage your product inventory. 🚀