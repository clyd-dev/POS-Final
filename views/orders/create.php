<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">New Order</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form id="orderForm" action="<?php echo BASE_URL; ?>/order/create" method="POST">
                <div class="row">
                    <!-- Menu Items -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Select Items</h3>
                                <div class="card-tools">
                                    <input type="text" id="searchMenu" class="form-control form-control-sm" placeholder="Search menu...">
                                </div>
                            </div>
                            <div class="card-body" style="max-height:520px; overflow-y:auto; background-color: #f0f8ff;">
                                <div class="row">
                                    <?php foreach ($menu_items as $item): ?>
                                    <div class="col-md-4 menu-item mb-3" data-name="<?php echo strtolower($item['name']); ?>">
                                        <div class="card h-100 shadow-md border-0">
                                            <img src="<?php echo !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/150'; ?>" 
                                                 class="card-img-top" style="height:150px; object-fit:cover;" alt="">
                                            <div class="card-body text-center p-3">
                                                <h6 class="card-title font-weight-bold"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                <span class="badge badge-info"><?php echo htmlspecialchars($item['category']); ?></span>
                                                <h5 class="text-primary mt-2">₱<?php echo number_format($item['price'], 2); ?></h5>
                                                <small class="text-muted d-block">Stock: <?php echo $item['stock']; ?></small>

                                                <!-- ADD TO ORDER BUTTON WITH ICON -->
                                                <button type="button" class="btn btn-success btn-block mt-3 add-to-order"
                                                    data-id="<?php echo $item['menu_id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($item['name']); ?>"
                                                    data-price="<?php echo $item['price']; ?>"
                                                    data-stock="<?php echo $item['stock']; ?>"
                                                    data-category="<?php echo htmlspecialchars($item['category']); ?>"
                                                    data-image="<?php echo $item['image'] ?? 'https://via.placeholder.com/150'; ?>">
                                                    <i class="fas fa-cart-plus"></i> Add to Order
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title">Order Summary</h3>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" name="customer_name" class="form-control" value="Walk-in Customer" required>
                                </div>

                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select name="payment_method" class="form-control" required>
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                        <option value="Digital Wallet">Digital Wallet</option>
                                    </select>
                                </div>

                                <div id="orderItems" class="mb-3">
                                    <p class="text-center text-muted">No items added yet</p>
                                </div>

                                <div class="border-top pt-3">
                                    <h4 class="mb-0">
                                        Total: <span id="totalAmount" class="float-right text-primary font-weight-bold">₱0.00</span>
                                    </h4>
                                    <input type="hidden" name="total_amount" id="totalInput" value="0">
                                </div>
                            </div>

                            <div class="card-footer">
                                <!-- COMPLETE ORDER BUTTON WITH ICON -->
                                <button type="button" id="completeOrderBtn" class="btn btn-success btn-lg btn-block" disabled>
                                    <i class="fas fa-check-circle"></i> Complete Order
                                </button>

                                <!-- CANCEL BUTTON WITH ICON -->
                                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary btn-block mt-2">
                                    <i class="fas fa-ban"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Order Receipt - Proof of Transaction</h5>
                <button type="button" class="close text-white" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body" id="receiptBody">
                <!-- JS fills this -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmOrder">
                    <i class="fas fa-save"></i> Confirm & Save Order
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let orderItems = [];

    // Search
    document.getElementById('searchMenu').addEventListener('keyup', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.menu-item').forEach(item => {
            const name = item.getAttribute('data-name');
            item.style.display = name.includes(term) ? '' : 'none';
        });
    });

    // Add to order
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-to-order')) {
            const btn = e.target.closest('.add-to-order');
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const price = parseFloat(btn.dataset.price);
            const stock = parseInt(btn.dataset.stock);
            const category = btn.dataset.category;
            const image = btn.dataset.image;

            const existing = orderItems.find(item => item.id == id);
            if (existing) {
                if (existing.quantity < stock) {
                    existing.quantity++;
                    existing.subtotal = existing.quantity * price;
                } else {
                    alert('Not enough stock!');
                    return;
                }
            } else {
                orderItems.push({ id, name, price, quantity: 1, subtotal: price, stock, category, image });
            }
            updateOrderSummary();
        }

        // Quantity controls
        if (e.target.classList.contains('increase-qty')) {
            const idx = e.target.dataset.index;
            if (orderItems[idx].quantity < orderItems[idx].stock) {
                orderItems[idx].quantity++;
                orderItems[idx].subtotal = orderItems[idx].quantity * orderItems[idx].price;
                updateOrderSummary();
            } else alert('Not enough stock!');
        }

        if (e.target.classList.contains('decrease-qty')) {
            const idx = e.target.dataset.index;
            if (orderItems[idx].quantity > 1) {
                orderItems[idx].quantity--;
                orderItems[idx].subtotal = orderItems[idx].quantity * orderItems[idx].price;
                updateOrderSummary();
            }
        }

        if (e.target.classList.contains('remove-item')) {
            orderItems.splice(e.target.dataset.index, 1);
            updateOrderSummary();
        }
    });

    function updateOrderSummary() {
        let html = '';
        let total = 0;

        if (orderItems.length === 0) {
            html = '<p class="text-center text-muted">No items added yet</p>';
        } else {
            orderItems.forEach((item, i) => {
                total += item.subtotal;
                html += `
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <img src="${item.image}" class="rounded mr-3" style="width:60px;height:60px;object-fit:cover;">
                    <div class="flex-grow-1">
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">${item.category}</small>
                        <div class="mt-2 d-flex align-items-center justify-content-between">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary decrease-qty" data-index="${i}">-</button>
                                <input type="text" value="${item.quantity}" class="form-control form-control-sm text-center" style="width:50px;" readonly>
                                <button type="button" class="btn btn-outline-secondary increase-qty" data-index="${i}">+</button>
                            </div>
                            <strong class="text-primary">₱${item.subtotal.toFixed(2)}</strong>
                            <button type="button" class="btn btn-sm btn-danger remove-item ml-2" data-index="${i}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="items[${i}][menu_id]" value="${item.id}">
                <input type="hidden" name="items[${i}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${i}][price]" value="${item.price}">
                <input type="hidden" name="items[${i}][subtotal]" value="${item.subtotal}">
                `;
            });
        }

        document.getElementById('orderItems').innerHTML = html;
        document.getElementById('totalAmount').textContent = '₱' + total.toFixed(2);
        document.getElementById('totalInput').value = total.toFixed(2);
        document.getElementById('completeOrderBtn').disabled = orderItems.length === 0;
    }

    // Complete Order → Show Receipt
    document.getElementById('completeOrderBtn').addEventListener('click', function() {
        const customer = document.querySelector('[name="customer_name"]').value || 'Walk-in Customer';
        const payment = document.querySelector('[name="payment_method"]').value;
        const total = parseFloat(document.getElementById('totalInput').value);

        let receipt = `
            <div class="text-center mb-4">
                <h3>RESTAURANT POS</h3>
                <p>Thank you for your order!</p>
            </div>
            <p><strong>Customer:</strong> ${customer}</p>
            <p><strong>Payment Method:</strong> ${payment}</p>
            <p><strong>Total Amount:</strong> <span class="h3 text-primary">₱${total.toFixed(2)}</span></p>
            <hr>
            <div class="mt-4">`;

        orderItems.forEach(item => {
            receipt += `
                <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                    <img src="${item.image}" class="rounded mr-3" style="width:70px;height:70px;object-fit:cover;">
                    <div class="flex-grow-1">
                        <strong>${item.name}</strong> <small class="text-muted">(${item.category})</small><br>
                        <strong>${item.quantity} × ₱${item.price.toFixed(2)} = ₱${item.subtotal.toFixed(2)}</strong>
                    </div>
                </div>`;
        });
        receipt += `</div>`;

        document.getElementById('receiptBody').innerHTML = receipt;
        $('#receiptModal').modal('show');
    });

    // Confirm → Submit Form
    document.getElementById('confirmOrder').addEventListener('click', function() {
        document.getElementById('orderForm').submit();
    });
});
</script>

<?php include '../views/layouts/footer.php'; ?>