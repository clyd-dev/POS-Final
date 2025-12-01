<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inventory Management</h1>
                </div>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addInventoryModal">
                        <i class="fas fa-plus"></i> Add Inventory Item
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            
            <?php include '../views/includes/alerts.php'; ?>
            
            <!-- Low Stock Alert -->
            <?php if (!empty($low_stock_items)): ?>
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Low Stock Alert!</h5>
                You have <?php echo count($low_stock_items); ?> item(s) below reorder level.
            </div>
            <?php endif; ?>
            
            <div class="row">
                <!-- Inventory Items -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Inventory Items</h3>
                            <div class="card-tools">
                                <form class="form-inline" method="GET">
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="<?php echo $current_search; ?>">
                                    <button type="submit" class="btn btn-sm btn-primary ml-2"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Reorder Level</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inventory_items as $item): ?>
                                    <tr class="<?php echo ($item['quantity'] <= $item['reorder_level']) ? 'low-stock' : ''; ?>">
                                        <td><?php echo $item['inventory_id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                        <td>
                                            <?php if ($item['quantity'] <= $item['reorder_level']): ?>
                                                <span class="badge badge-danger"><?php echo $item['quantity']; ?></span>
                                            <?php else: ?>
                                                <?php echo $item['quantity']; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['unit']); ?></td>
                                        <td><?php echo $item['reorder_level']; ?></td>
                                        <td><?php echo date('M d, Y h:i A', strtotime($item['last_updated'])); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning edit-btn" 
                                                    data-id="<?php echo $item['inventory_id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($item['item_name']); ?>"
                                                    data-quantity="<?php echo $item['quantity']; ?>"
                                                    data-unit="<?php echo htmlspecialchars($item['unit']); ?>"
                                                    data-reorder="<?php echo $item['reorder_level']; ?>"
                                                    data-toggle="modal" 
                                                    data-target="#editInventoryModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?php echo BASE_URL; ?>/inventory/delete/<?php echo $item['inventory_id']; ?>" class="btn btn-sm btn-danger delete-btn">
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
                
                <!-- Low Stock Items -->
                <div class="col-md-4">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Low Stock Items</h3>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($low_stock_items)): ?>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Reorder</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($low_stock_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                        <td><span class="badge badge-danger"><?php echo $item['quantity']; ?></span></td>
                                        <td><?php echo $item['reorder_level']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="p-3 text-center text-muted">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <p>All items are well stocked!</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>/inventory/create" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Add Inventory Item</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Item Name *</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity *</label>
                                <input type="number" name="quantity" class="form-control" value="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit *</label>
                                <input type="text" name="unit" class="form-control" placeholder="e.g., kg, pcs, liter" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Reorder Level *</label>
                        <input type="number" name="reorder_level" class="form-control" value="10" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Inventory Modal -->
<div class="modal fade" id="editInventoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editInventoryForm" action="" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Inventory Item</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Item Name *</label>
                        <input type="text" name="item_name" id="edit_item_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity *</label>
                                <input type="number" name="quantity" id="edit_quantity" class="form-control" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit *</label>
                                <input type="text" name="unit" id="edit_unit" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Reorder Level *</label>
                        <input type="number" name="reorder_level" id="edit_reorder_level" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Edit button click
$('.edit-btn').on('click', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const quantity = $(this).data('quantity');
    const unit = $(this).data('unit');
    const reorder = $(this).data('reorder');
    
    $('#editInventoryForm').attr('action', '<?php echo BASE_URL; ?>/inventory/update/' + id);
    $('#edit_item_name').val(name);
    $('#edit_quantity').val(quantity);
    $('#edit_unit').val(unit);
    $('#edit_reorder_level').val(reorder);
});
</script>

<?php include '../views/layouts/footer.php'; ?>