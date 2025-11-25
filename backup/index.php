<?php
require_once __DIR__ . "/config/database.php";

// Dashboard Statistics Class
class Dashboard extends Database {
    public function __construct() {
        parent::__construct();
        $this->connect();
    }
    
    public function getTotalCategories() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM tbl_category");
        return $result->fetch_assoc()['total'];
    }
    
    public function getTotalSuppliers() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM tbl_supplier");
        return $result->fetch_assoc()['total'];
    }
    
    public function getTotalProducts() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM tbl_product");
        return $result->fetch_assoc()['total'];
    }
    
    public function getTotalStock() {
        $result = $this->conn->query("SELECT SUM(quantity) as total FROM tbl_product");
        return $result->fetch_assoc()['total'] ?? 0;
    }
    
    public function getLowStockProducts() {
        $result = $this->conn->query("SELECT p.*, c.category_name, s.supplier_name FROM tbl_product p LEFT JOIN tbl_category c ON p.category_id = c.category_id LEFT JOIN tbl_supplier s ON p.supplier_id = s.supplier_id WHERE p.quantity < 20 ORDER BY p.quantity ASC LIMIT 5");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getRecentProducts() {
        $result = $this->conn->query("SELECT p.*, c.category_name, s.supplier_name FROM tbl_product p LEFT JOIN tbl_category c ON p.category_id = c.category_id LEFT JOIN tbl_supplier s ON p.supplier_id = s.supplier_id ORDER BY p.created_at DESC LIMIT 5");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

$dashboard = new Dashboard();
$totalCategories = $dashboard->getTotalCategories();
$totalSuppliers = $dashboard->getTotalSuppliers();
$totalProducts = $dashboard->getTotalProducts();
$totalStock = $dashboard->getTotalStock();
$lowStockProducts = $dashboard->getLowStockProducts();
$recentProducts = $dashboard->getRecentProducts();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<!--begin::App Main-->
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            <!--begin::Stats Row-->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?php echo $totalCategories; ?></h3>
                            <p>Categories</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6zm0 2h12v4H6V4zm0 6h12v10H6V10z"/>
                        </svg>
                        <a href="category.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?php echo $totalSuppliers; ?></h3>
                            <p>Suppliers</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm1 5h16v10a1 1 0 01-1 1H5a1 1 0 01-1-1V9z"/>
                        </svg>
                        <a href="supplier.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?php echo $totalProducts; ?></h3>
                            <p>Products</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M4 4h16v4H4V4zm0 6h16v10H4V10z"/>
                        </svg>
                        <a href="product.php" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3><?php echo number_format($totalStock); ?></h3>
                            <p>Total Stock</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M3 3h18v18H3V3zm2 2v14h14V5H5z"/>
                        </svg>
                        <a href="product.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!--begin::Content Row-->
            <div class="row">
                <!--begin::Low Stock Alert-->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Low Stock Alert</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($lowStockProducts)): ?>
                                            <?php foreach ($lowStockProducts as $prod): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prod['product_name']); ?></td>
                                                <td><?php echo htmlspecialchars($prod['category_name']); ?></td>
                                                <td><span class="badge text-bg-danger"><?php echo $prod['quantity']; ?></span></td>
                                                <td>
                                                    <a href="product.php?edit=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">All products have sufficient stock</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--begin::Recent Products-->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-clock-history me-2"></i>Recently Added Products</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Supplier</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recentProducts)): ?>
                                            <?php foreach ($recentProducts as $prod): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prod['product_name']); ?></td>
                                                <td><?php echo htmlspecialchars($prod['supplier_name']); ?></td>
                                                <td>₱<?php echo number_format($prod['price'], 2); ?></td>
                                                <td><?php echo $prod['quantity']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No products found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="product.php" class="btn btn-sm btn-primary float-end">View All Products</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>