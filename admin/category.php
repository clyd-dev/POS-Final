<?php
require_once __DIR__ . "/config/database.php";

session_start();

// ✅ Category class extends Database - BACKEND LOGIC INTACT
class Category extends Database {
    public function __construct() {
        parent::__construct();
        $this->connect();
    }

    public function addCategory($category_name, $description) {
        $check = $this->conn->prepare("SELECT * FROM tbl_category WHERE category_name = ? AND description = ? LIMIT 1");
        $check->bind_param("ss", $category_name, $description);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Category already exists!); window.location='category.php';</script>";
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO tbl_category (category_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $category_name, $description);
        return $stmt->execute();
    }

    public function getCategories() {
        $result = $this->conn->query("SELECT * FROM tbl_category ORDER BY category_id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById($category_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_category WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function searchCategory($keyword) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_category WHERE category_name LIKE ? OR description LIKE ? ORDER BY category_id DESC");
        $searchTerm = "%" . $keyword . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateCategory($category_id, $category_name, $description) {
        $stmt = $this->conn->prepare("UPDATE tbl_category SET category_name = ?, description = ? WHERE category_id = ?");
        $stmt->bind_param("ssi", $category_name, $description, $category_id);
        return $stmt->execute();
    }

    public function deleteCategory($category_id) {
        $check = $this->conn->prepare("SELECT COUNT(*) FROM tbl_product WHERE category_id = ?");
        $check->bind_param("i", $category_id);
        $check->execute();
        $check->bind_result($count);
        $check->fetch();
        $check->close();

        if ($count > 0) {
            echo "<script>alert('Cannot delete this category. There are products linked to this category.');</script>";
        return false;        
        } 
        else {
            $stmt = $this->conn->prepare("DELETE FROM tbl_category WHERE category_id = ?");
            $stmt->bind_param("i", $category_id);
            return $stmt->execute();
        }
    }
}

// ✅ BACKEND OPERATIONS - UNCHANGED
$message = "";
$category = new Category();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $category_name = $_POST['category_name'];
    $description = $_POST['description'];
    if ($category->addCategory($category_name, $description)) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Category added successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error adding category<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $description = $_POST['description'];
    if ($category->updateCategory($category_id, $category_name, $description)) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Category updated successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error updating category<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    header("Location: category.php");
    exit();
}

if (isset($_GET['delete'])) {
    $category_id = $_GET['delete'];
    if ($category->deleteCategory($category_id)) {
        $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle me-2'></i>Category deleted successfully<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-x-circle me-2'></i>Error deleting category<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $keyword = trim($_POST['keyword']);
    $searchResults = $category->searchCategory($keyword);
}

$allCategories = $category->getCategories();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<!--begin::App Main-->
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Category Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Categories</li>
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
                <!--begin::Add Form-->
                <div class="col-md-4">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-plus-circle me-2"></i>Add Category</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" name="category_name" id="category_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Optional description"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="add" class="btn btn-primary"><i class="bi bi-save me-2"></i>Save Category</button>
                                <a href="supplier.php" class="btn btn-success"><i class="bi bi-truck me-2"></i>Suppliers</a>
                                <a href="product.php" class="btn btn-warning"><i class="bi bi-box me-2"></i>Products</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!--begin::List-->
                <div class="col-md-8">
                    <!--begin::Search-->
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-search me-2"></i>Search Category</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Search by name or description..." required>
                                    <button type="submit" name="search" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!--begin::Search Results-->
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
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($searchResults as $cat): ?>
                                    <tr>
                                        <td><?php echo $cat['category_id']; ?></td>
                                        <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                                        <td><?php echo htmlspecialchars($cat['description']); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                            <a href="?delete=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php elseif (isset($_POST['search'])): ?>
                        <div class="alert alert-warning"><i class="bi bi-info-circle me-2"></i>No results found for "<?php echo htmlspecialchars($_POST['keyword']); ?>"</div>
                    <?php endif; ?>

                    <!--begin::All Categories-->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-list-ul me-2"></i>All Categories</h3>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($allCategories)): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allCategories as $cat): ?>
                                    <tr>
                                        <td><?php echo $cat['category_id']; ?></td>
                                        <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                                        <td><?php echo htmlspecialchars($cat['description']); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                            <a href="?delete=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <p class="text-center p-3">No categories found</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!--begin::Edit Form-->
                    <?php if (isset($_GET['edit'])):
                        $editCategory = $category->getCategoryById($_GET['edit']);
                        if ($editCategory): ?>
                    <div class="card card-warning mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-pencil-square me-2"></i>Edit Category</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <input type="hidden" name="category_id" value="<?php echo $editCategory['category_id']; ?>">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" name="category_name" class="form-control" value="<?php echo htmlspecialchars($editCategory['category_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($editCategory['description']); ?></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="update" class="btn btn-warning"><i class="bi bi-check-circle me-2"></i>Update</button>
                                <a href="category.php" class="btn btn-secondary">Cancel</a>
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