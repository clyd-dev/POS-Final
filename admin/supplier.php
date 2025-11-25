<?php
require_once __DIR__ . "/config/database.php";

session_start();

// ✅ Supplier class extends Database - BACKEND LOGIC INTACT
class Supplier extends Database {
    public function __construct() {
        parent::__construct();
        $this->connect();
    }

    public function addSupplier($supplier_name, $contact_person, $contact_number, $address) {
        $check = $this->conn->prepare("SELECT * FROM tbl_supplier WHERE supplier_id = ? AND supplier_name = ? AND contact_person = ? AND contact_number = ? AND address = ? LIMIT 1");
        $check->bind_param("issss", $supplier_id, $supplier_name, $contact_person, $contact_number, $address);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Supplier already exists!'); window.location='supplier.php';</script>";
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO tbl_supplier (supplier_name, contact_person, contact_number, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $supplier_name, $contact_person, $contact_number, $address);
        return $stmt->execute();
    }

    public function getSuppliers() {
        $result = $this->conn->query("SELECT * FROM tbl_supplier ORDER BY supplier_id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSupplierById($supplier_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_supplier WHERE supplier_id = ?");
        $stmt->bind_param("i", $supplier_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function searchSupplier($keyword) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_supplier WHERE supplier_name LIKE ? OR contact_person LIKE ? OR contact_number LIKE ? ORDER BY supplier_id DESC");
        $searchTerm = "%" . $keyword . "%";
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateSupplier($supplier_id, $supplier_name, $contact_person, $contact_number, $address) {
        $stmt = $this->conn->prepare("UPDATE tbl_supplier SET supplier_name = ?, contact_person = ?, contact_number = ?, address = ? WHERE supplier_id = ?");
        $stmt->bind_param("ssssi", $supplier_name, $contact_person, $contact_number, $address, $supplier_id);
        return $stmt->execute();
    }

    public function deleteSupplier($supplier_id) {
        $check = $this->conn->prepare("SELECT COUNT(*) FROM tbl_product WHERE supplier_id = ?");
        $check->bind_param("i", $supplier_id);
        $check->execute();
        $check->bind_result($count);
        $check->fetch();
        $check->close();

        if ($count > 0) {
            echo "<script>alert('Cannot delete this supplier. There are products linked to this supplier.');</script>";
        return false;        
        } 
        else {
            $stmt = $this->conn->prepare("DELETE FROM tbl_supplier WHERE supplier_id = ?");
            $stmt->bind_param("i", $supplier_id);
            return $stmt->execute();
        }
    }
}

// ✅ BACKEND OPERATIONS - UNCHANGED
$message = "";
$supplier = new Supplier();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    if ($supplier->addSupplier($_POST['supplier_name'], $_POST['contact_person'], $_POST['contact_number'], $_POST['address'])) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Supplier added successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error adding supplier<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    if ($supplier->updateSupplier($_POST['supplier_id'], $_POST['supplier_name'], $_POST['contact_person'], $_POST['contact_number'], $_POST['address'])) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Supplier updated successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error updating supplier<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    header("Location: supplier.php");
    exit();
}

if (isset($_GET['delete'])) {
    if ($supplier->deleteSupplier($_GET['delete'])) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Supplier deleted successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error deleting supplier<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchResults = $supplier->searchSupplier(trim($_POST['keyword']));
}

$allSuppliers = $supplier->getSuppliers();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Supplier Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Suppliers</li>
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
                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-plus-circle me-2"></i>Add Supplier</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Supplier Name</label>
                                    <input type="text" name="supplier_name" class="form-control" placeholder="e.g., ABC Trading Corp." required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" placeholder="e.g., Juan Dela Cruz" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" placeholder="e.g., 09171234567" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3" placeholder="Supplier address" required></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="add" class="btn btn-success"><i class="bi bi-save me-2"></i>Save</button>
                                <a href="category.php" class="btn btn-primary"><i class="bi bi-tag me-2"></i>Categories</a>
                                <a href="product.php" class="btn btn-warning"><i class="bi bi-box me-2"></i>Products</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-success card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-search me-2"></i>Search Supplier</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Search by name, contact person, or number..." required>
                                    <button type="submit" name="search" class="btn btn-success"><i class="bi bi-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($searchResults)): ?>
                    <div class="card mb-4">
                        <div class="card-header bg-warning">
                            <h3 class="card-title"><i class="bi bi-funnel me-2"></i>Search Results</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier Name</th>
                                        <th>Contact Person</th>
                                        <th>Contact Number</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($searchResults as $sup): ?>
                                    <tr>
                                        <td><?php echo $sup['supplier_id']; ?></td>
                                        <td><?php echo htmlspecialchars($sup['supplier_name']); ?></td>
                                        <td><?php echo htmlspecialchars($sup['contact_person']); ?></td>
                                        <td><?php echo htmlspecialchars($sup['contact_number']); ?></td>
                                        <td><?php echo htmlspecialchars($sup['address']); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $sup['supplier_id']; ?>" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                            <a href="?delete=<?php echo $sup['supplier_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this supplier?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php elseif (isset($_POST['search'])): ?>
                        <div class="alert alert-warning"><i class="bi bi-info-circle me-2"></i>No results found</div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-list-ul me-2"></i>All Suppliers</h3>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($allSuppliers)): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier Name</th>
                                        <th>Contact Person</th>
                                        <th>Contact Number</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allSuppliers as $sup): ?>
                                    <tr>
                                        <td><?php echo $sup['supplier_id']; ?></td>
                                        <td><?php echo htmlspecialchars($sup['supplier_name']); ?></td>
                                        <td><?php echo htmlspecialchars($sup['contact_person']); ?></td>
                                        <td><?php echo htmlspecialchars($sup['contact_number']); ?></td>
                                        <td><?php echo htmlspecialchars($sup['address']); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $sup['supplier_id']; ?>" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                            <a href="?delete=<?php echo $sup['supplier_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this supplier?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <p class="text-center p-3">No suppliers found</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (isset($_GET['edit'])):
                        $editSupplier = $supplier->getSupplierById($_GET['edit']);
                        if ($editSupplier): ?>
                    <div class="card card-warning mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-pencil-square me-2"></i>Edit Supplier</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <input type="hidden" name="supplier_id" value="<?php echo $editSupplier['supplier_id']; ?>">
                                <div class="mb-3">
                                    <label class="form-label">Supplier Name</label>
                                    <input type="text" name="supplier_name" class="form-control" value="<?php echo htmlspecialchars($editSupplier['supplier_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" value="<?php echo htmlspecialchars($editSupplier['contact_person']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($editSupplier['contact_number']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($editSupplier['address']); ?></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="update" class="btn btn-warning"><i class="bi bi-check-circle me-2"></i>Update</button>
                                <a href="supplier.php" class="btn btn-secondary">Cancel</a>
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