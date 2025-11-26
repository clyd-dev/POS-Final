<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Menu Item</h1>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="<?php echo BASE_URL; ?>/menu/create" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Item Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <option value="Burger">Burger</option>
                                        <option value="Pizza">Pizza</option>
                                        <option value="Pasta">Pasta</option>
                                        <option value="Fries">Fries</option>
                                        <option value="Drinks">Drinks</option>
                                        <option value="Dessert">Dessert</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price *</label>
                                    <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Initial Stock</label>
                            <input type="number" name="stock" class="form-control" value="0" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Item</button>
                        <a href="<?php echo BASE_URL; ?>/menu" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>