<?php
// Include the database configuration file (contains database connection setup)
require_once __DIR__ . "/config/database.php";

// Start PHP session to store temporary messages (e.g., success/error alerts)
session_start();

// ✅ Product class extends Database - BACKEND LOGIC INTACT
class Product extends Database {
    // Constructor automatically connects to database using parent class
    public function __construct() {
        parent::__construct();
        $this->connect();
    }

    // Insert new product record into the database
    public function addProduct($product_name, $category_id, $supplier_id, $quantity, $price, $description) {
        $check = $this->conn->prepare("SELECT * FROM tbl_product WHERE product_name = ? AND supplier_id = ? LIMIT 1");
        $check->bind_param("si", $product_name, $supplier_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Product already exists for this supplier! Update the Product instead!'); window.location='product.php';</script>";
            return false;
        }

        // Prepare SQL query to prevent SQL injection
        $stmt = $this->conn->prepare("INSERT INTO tbl_product (product_name, category_id, supplier_id, quantity, price, description) VALUES (?, ?, ?, ?, ?, ?)");
        // Bind user input values to the prepared statement (type-safe binding)
        $stmt->bind_param("siiids", $product_name, $category_id, $supplier_id, $quantity, $price, $description);    
        // Execute query and return boolean result
        return $stmt->execute();
    }

    // Retrieve all products with related category and supplier names
    public function getProducts() {
        // LEFT JOIN ensures category/supplier data is included even if missing
        $query = "SELECT p.*, c.category_name, s.supplier_name FROM tbl_product p LEFT JOIN tbl_category c ON p.category_id = c.category_id LEFT JOIN tbl_supplier s ON p.supplier_id = s.supplier_id ORDER BY p.product_id DESC";
        $result = $this->conn->query($query);
        // Return all rows as associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch a single product based on its ID
    public function getProductById($product_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_product WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Search products using keyword in product, description, category, or supplier
    public function searchProduct($keyword) {
        $query = "SELECT p.*, c.category_name, s.supplier_name FROM tbl_product p LEFT JOIN tbl_category c ON p.category_id = c.category_id LEFT JOIN tbl_supplier s ON p.supplier_id = s.supplier_id WHERE p.product_name LIKE ? OR p.description LIKE ? OR c.category_name LIKE ? OR s.supplier_name LIKE ? ORDER BY p.product_id DESC";
        $stmt = $this->conn->prepare($query);
        // Wildcard search pattern
        $searchTerm = "%" . $keyword . "%";
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        // Return matching results as array
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Update product details based on ID
    public function updateProduct($product_id, $product_name, $category_id, $supplier_id, $quantity, $price, $description) {
        $stmt = $this->conn->prepare("UPDATE tbl_product SET product_name = ?, category_id = ?, supplier_id = ?, quantity = ?, price = ?, description = ? WHERE product_id = ?");
        $stmt->bind_param("siiidsi", $product_name, $category_id, $supplier_id, $quantity, $price, $description, $product_id);
        return $stmt->execute();
    }

    // Delete a product record using its ID
    public function deleteProduct($product_id) {
        $stmt = $this->conn->prepare("DELETE FROM tbl_product WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }

    // Retrieve all categories for dropdown selection
    public function getAllCategories() {
        $result = $this->conn->query("SELECT * FROM tbl_category ORDER BY category_name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Retrieve all suppliers for dropdown selection
    public function getAllSuppliers() {
        $result = $this->conn->query("SELECT * FROM tbl_supplier ORDER BY supplier_name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// ✅ BACKEND OPERATIONS (handles form submissions and database actions)

// Variable to store feedback messages
$message = "";

// Create an instance of Product class (establishes DB connection)
$product = new Product();

// Handle "Add Product" form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    // Add product and show success or error message in session
    if ($product->addProduct($_POST['product_name'], $_POST['category_id'], $_POST['supplier_id'], $_POST['quantity'], $_POST['price'], $_POST['description'])) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Product added successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error adding product<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

// Handle "Update Product" form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Update product and set success/error message
    if ($product->updateProduct($_POST['product_id'], $_POST['product_name'], $_POST['category_id'], $_POST['supplier_id'], $_POST['quantity'], $_POST['price'], $_POST['description'])) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Product updated successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error updating product<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    // Redirect back to avoid form resubmission
    header("Location: product.php");
    exit();
}

// Handle product deletion through GET request
if (isset($_GET['delete'])) {
    if ($product->deleteProduct($_GET['delete'])) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Product deleted successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error deleting product<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

// Handle product search functionality
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    // Trim keyword to remove extra spaces before searching
    $searchResults = $product->searchProduct(trim($_POST['keyword']));
}

// Fetch all products, categories, and suppliers for initial page load
$allProducts = $product->getProducts();
$categories = $product->getAllCategories();
$suppliers = $product->getAllSuppliers();

// Include frontend layout components (header and sidebar)
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Product Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <?php
                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                }
            ?>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-plus-circle me-2"></i>Add Product</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="product_name" class="form-control" placeholder="" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- Select Category --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Supplier</label>
                                    <select name="supplier_id" class="form-select" required>
                                        <option value="">-- Select Supplier --</option>
                                        <?php foreach ($suppliers as $sup): ?>
                                            <option value="<?php echo $sup['supplier_id']; ?>"><?php echo htmlspecialchars($sup['supplier_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" placeholder="" min="0" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price (₱)</label>
                                    <input type="number" name="price" class="form-control" placeholder="" step="0.01" min="0" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Optional description"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="add" class="btn btn-warning"><i class="bi bi-save me-2"></i>Save</button>
                                <a href="category.php" class="btn btn-primary"><i class="bi bi-tag me-2"></i>Categories</a>
                                <a href="supplier.php" class="btn btn-success"><i class="bi bi-truck me-2"></i>Suppliers</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-warning card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-search me-2"></i>Search Product</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Search by product, category, or supplier..." required>
                                    <button type="submit" name="search" class="btn btn-warning"><i class="bi bi-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($searchResults)): ?>
                    <div class="card mb-4">
                        <div class="card-header bg-info">
                            <h3 class="card-title"><i class="bi bi-funnel me-2"></i>Search Results</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Supplier</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($searchResults as $prod): ?>
                                        <tr>
                                            <td><?php echo $prod['product_id']; ?></td>
                                            <td><?php echo htmlspecialchars($prod['product_name']); ?></td>
                                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($prod['category_name']); ?></span></td>
                                            <td><?php echo htmlspecialchars($prod['supplier_name']); ?></td>
                                            <td><span class="badge <?php echo $prod['quantity'] < 20 ? 'bg-danger' : 'bg-success'; ?>"><?php echo $prod['quantity']; ?></span></td>
                                            <td>₱<?php echo number_format($prod['price'], 2); ?></td>
                                            <td>
                                                <a href="?edit=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                                <a href="?delete=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php elseif (isset($_POST['search'])): ?>
                        <div class="alert alert-warning"><i class="bi bi-info-circle me-2"></i>No results found</div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-list-ul me-2"></i>All Products</h3>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($allProducts)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Supplier</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allProducts as $prod): ?>
                                        <tr>
                                            <td><?php echo $prod['product_id']; ?></td>
                                            <td><?php echo htmlspecialchars($prod['product_name']); ?></td>
                                            <td><span class="badge bg-primary"><?php echo htmlspecialchars($prod['category_name']); ?></span></td>
                                            <td><?php echo htmlspecialchars($prod['supplier_name']); ?></td>
                                            <td><span class="badge <?php echo $prod['quantity'] < 20 ? 'bg-danger' : 'bg-success'; ?>"><?php echo $prod['quantity']; ?></span></td>
                                            <td>₱<?php echo number_format($prod['price'], 2); ?></td>
                                            <td><?php echo htmlspecialchars(substr($prod['description'], 0, 30)); ?></td>
                                            <td>
                                                <a href="?edit=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                                <a href="?delete=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <p class="text-center p-3">No products found</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (isset($_GET['edit'])):
                        $editProduct = $product->getProductById($_GET['edit']);
                        if ($editProduct): ?>
                    <div class="card card-info mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-pencil-square me-2"></i>Edit Product</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <input type="hidden" name="product_id" value="<?php echo $editProduct['product_id']; ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" name="product_name" class="form-control" value="<?php echo htmlspecialchars($editProduct['product_name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category</label>
                                        <select name="category_id" class="form-select" required>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?php echo $cat['category_id']; ?>" <?php echo ($cat['category_id'] == $editProduct['category_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['category_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Supplier</label>
                                        <select name="supplier_id" class="form-select" required>
                                            <?php foreach ($suppliers as $sup): ?>
                                                <option value="<?php echo $sup['supplier_id']; ?>" <?php echo ($sup['supplier_id'] == $editProduct['supplier_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($sup['supplier_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" name="quantity" class="form-control" value="<?php echo $editProduct['quantity']; ?>" min="0" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Price (₱)</label>
                                        <input type="number" name="price" class="form-control" value="<?php echo $editProduct['price']; ?>" step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($editProduct['description']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="update" class="btn btn-info"><i class="bi bi-check-circle me-2"></i>Update</button>
                                <a href="product.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>