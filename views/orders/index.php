<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Orders</h1>
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
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order History</h3>
                    <div class="card-tools">
                        <form class="form-inline" method="GET">
                            <input type="text" name="order_number" class="form-control form-control-sm mr-2" placeholder="Order #" value="<?php echo $filters['order_number']; ?>">
                            <input type="date" name="start_date" class="form-control form-control-sm mr-2" value="<?php echo $filters['start_date']; ?>">
                            <input type="date" name="end_date" class="form-control form-control-sm mr-2" value="<?php echo $filters['end_date']; ?>">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong><?php echo $order['order_number']; ?></strong></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['cashier_name']); ?></td>
                                <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo $order['payment_method']; ?></td>
                                <td>
                                    <?php if ($order['order_status'] == 'Completed'): ?>
                                        <span class="badge badge-success">Completed</span>
                                    <?php elseif ($order['order_status'] == 'Cancelled'): ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/order/receipt/<?php echo $order['order_id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                    <?php if ($order['order_status'] == 'Completed' && $_SESSION['role'] === 'Admin'): ?>
                                    <a href="<?php echo BASE_URL; ?>/order/cancel/<?php echo $order['order_id']; ?>" class="btn btn-sm btn-danger delete-btn">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                    <?php endif; ?>
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