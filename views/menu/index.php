<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Menu Management</h1>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo BASE_URL; ?>/menu/create" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Add Menu Item
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Menu Items</h3>

                    <div class="card-tools">
                        <form class="form-inline" method="GET">
                            <select name="category" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['category']; ?>" 
                                    <?php echo ($current_category == $cat['category']) ? 'selected' : ''; ?>>
                                    <?php echo $cat['category']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>

                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Search..." value="<?php echo $current_search; ?>">
                            <button type="submit" class="btn btn-sm btn-primary ml-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($menu_items as $item): ?>
                        <tr class="<?php echo ($item['stock'] == 0) ? 'out-of-stock' : (($item['stock'] <= 10) ? 'low-stock' : ''); ?>">

                            <td><?php echo $item['menu_id']; ?></td>

                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="<?php echo $item['image']; ?>" 
                                        alt="Menu Image" 
                                       style="width:50px;height:50px;object-fit:cover;">
                                <?php else: ?>
                                    <span>No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                            <td><span class="badge badge-info"><?php echo $item['category']; ?></span></td>
                            <td>₱<?php echo number_format($item['price'], 2); ?></td>

                            <td>
                                <?php if ($item['stock'] == 0): ?>
                                    <span class="badge badge-danger">Out of Stock</span>
                                <?php elseif ($item['stock'] <= 10): ?>
                                    <span class="badge badge-warning"><?php echo $item['stock']; ?></span>
                                <?php else: ?>
                                    <?php echo $item['stock']; ?>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($item['status'] == 'Available'): ?>
                                    <span class="badge badge-success">Available</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Unavailable</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="<?php echo BASE_URL; ?>/menu/edit/<?php echo $item['menu_id']; ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="<?php echo BASE_URL; ?>/menu/delete/<?php echo $item['menu_id']; ?>" 
                                   class="btn btn-sm btn-danger delete-btn">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>
