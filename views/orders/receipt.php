<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Order Receipt</h1>
                </div>
                <div class="col-sm-6">
                    <button onclick="window.print()" class="btn btn-primary float-right">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body" id="receiptContent">
                            <div class="text-center mb-4">
                                <h2><strong>RESTAURANT POS</strong></h2>
                                <p class="mb-1">Thank you for your order!</p>
                                <p class="text-muted">Receipt #<?php echo $order['order_number']; ?></p>
                            </div>
                            
                            <hr>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Date:</strong> <?php echo date('F d, Y', strtotime($order['created_at'])); ?></p>
                                    <p class="mb-1"><strong>Time:</strong> <?php echo date('h:i A', strtotime($order['created_at'])); ?></p>
                                </div>
                                <div class="col-6 text-right">
                                    <p class="mb-1"><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                                    <p class="mb-1"><strong>Cashier:</strong> <?php echo htmlspecialchars($order['cashier_name']); ?></p>
                                </div>
                            </div>
                            
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                                        <td class="text-right">₱<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="text-right">₱<?php echo number_format($item['subtotal'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                        <td class="text-right"><h4 class="text-primary">₱<?php echo number_format($order['total_amount'], 2); ?></h4></td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                            <hr>
                            
                            <div class="text-center">
                                <p class="mb-1"><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                                <p class="mb-1"><strong>Status:</strong> <span class="badge badge-success"><?php echo $order['payment_status']; ?></span></p>
                                <p class="mt-3 text-muted">Thank you for dining with us!</p>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="<?php echo BASE_URL; ?>/order/create" class="btn btn-primary">New Order</a>
                            <a href="<?php echo BASE_URL; ?>/order" class="btn btn-secondary">View Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .main-header, .main-sidebar, .content-header, .card-footer, .btn { display: none !important; }
    .content-wrapper { margin: 0 !important; padding: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>

<?php include '../views/layouts/footer.php'; ?>