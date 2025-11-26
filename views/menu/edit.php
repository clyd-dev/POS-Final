<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Menu Item</h1>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="<?php echo BASE_URL; ?>/menu/edit/<?php echo $item['menu_id']; ?>" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Item Name *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="category" class="form-control" required>
                                        <option value="Burger" <?php echo ($item['category'] == 'Burger') ? 'selected' : ''; ?>>Burger</option>
                                        <option value="Pizza" <?php echo ($item['category'] == 'Pizza') ? 'selected' : ''; ?>>Pizza</option>
                                        <option value="Pasta" <?php echo ($item['category'] == 'Pasta') ? 'selected' : ''; ?>>Pasta</option>
                                        <option value="Fries" <?php echo ($item['category'] == 'Fries') ? 'selected' : ''; ?>>Fries</option>
                                        <option value="Drinks" <?php echo ($item['category'] == 'Drinks') ? 'selected' : ''; ?>>Drinks</option>
                                        <option value="Dessert" <?php echo ($item['category'] == 'Dessert') ? 'selected' : ''; ?>>Dessert</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price *</label>
                                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="<?php echo $item['price']; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="number" name="stock" class="form-control" value="<?php echo $item['stock']; ?>" min="0">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Available" <?php echo ($item['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                        <option value="Unavailable" <?php echo ($item['status'] == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($item['description']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Item</button>
                        <a href="<?php echo BASE_URL; ?>/menu" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>