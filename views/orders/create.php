<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">New Order</h1>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form id="orderForm" action="<?php echo BASE_URL; ?>/order/create" method="POST">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Select Items</h3>
                                <div class="card-tools">
                                    <input type="text" id="searchMenu" class="form-control form-control-sm" placeholder="Search menu...">
                                </div>
                            </div>
                            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                <div class="row" id="menuItems">
                                    <?php foreach ($menu_items as $item): ?>
                                    <div class="col-md-4 menu-item" data-name="<?php echo strtolower($item['name']); ?>">
                                        <div class="card mb-3">
                                            <div class="card-body text-center">
                                                <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                                <p class="mb-1"><span class="badge badge-info"><?php echo $item['category']; ?></span></p>
                                                <h4 class="text-primary">₱<?php echo number_format($item['price'], 2); ?></h4>
                                                <p class="text-muted small">Stock: <?php echo $item['stock']; ?></p>
                                                <button type="button" class="btn btn-sm btn-success add-to-order" 
                                                        data-id="<?php echo $item['menu_id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($item['name']); ?>"
                                                        data-price="<?php echo $item['price']; ?>"
                                                        data-stock="<?php echo $item['stock']; ?>">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Order Summary</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" name="customer_name" class="form-control" value="Walk-in Customer">
                                </div>
                                
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select name="payment_method" class="form-control" required>
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                        <option value="Digital Wallet">Digital Wallet</option>
                                    </select>
                                </div>
                                
                                <div id="orderItems" class="mb-3"></div>
                                
                                <div class="border-top pt-3">
                                    <h4>Total: <span id="totalAmount" class="float-right text-primary">₱0.00</span></h4>
                                    <input type="hidden" name="total_amount" id="totalInput" value="0">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" id="submitOrder" class="btn btn-success btn-block" disabled>
                                    <i class="fas fa-check"></i> Complete Order
                                </button>
                                <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-secondary btn-block">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<script>
let orderItems = [];

// Search menu
$('#searchMenu').on('keyup', function() {
    const value = $(this).val().toLowerCase();
    $('.menu-item').filter(function() {
        $(this).toggle($(this).data('name').indexOf(value) > -1);
    });
});

// Add to order
$('.add-to-order').on('click', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const price = parseFloat($(this).data('price'));
    const stock = parseInt($(this).data('stock'));
    
    const existing = orderItems.find(item => item.id === id);
    
    if (existing) {
        if (existing.quantity < stock) {
            existing.quantity++;
            existing.subtotal = existing.quantity * existing.price;
        } else {
            alert('Not enough stock available!');
            return;
        }
    } else {
        orderItems.push({
            id: id,
            name: name,
            price: price,
            quantity: 1,
            subtotal: price,
            stock: stock
        });
    }
    
    updateOrderSummary();
});

// Update order summary
function updateOrderSummary() {
    let html = '';
    let total = 0;
    
    orderItems.forEach((item, index) => {
        total += item.subtotal;
        html += `
            <div class="order-item mb-2 p-2 border-bottom">
                <div class="d-flex justify-content-between">
                    <strong>${item.name}</strong>
                    <button type="button" class="btn btn-xs btn-danger remove-item" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <div class="input-group" style="width: 100px;">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-sm btn-outline-secondary decrease-qty" data-index="${index}">-</button>
                        </div>
                        <input type="number" class="form-control form-control-sm text-center" value="${item.quantity}" readonly>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-outline-secondary increase-qty" data-index="${index}">+</button>
                        </div>
                    </div>
                    <span>₱${item.subtotal.toFixed(2)}</span>
                </div>
                <input type="hidden" name="items[${index}][menu_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${index}][price]" value="${item.price}">
                <input type="hidden" name="items[${index}][subtotal]" value="${item.subtotal}">
            </div>
        `;
    });
    
    $('#orderItems').html(html);
    $('#totalAmount').text('₱' + total.toFixed(2));
    $('#totalInput').val(total.toFixed(2));
    $('#submitOrder').prop('disabled', orderItems.length === 0);
}

// Item actions
$(document).on('click', '.remove-item', function() {
    const index = $(this).data('index');
    orderItems.splice(index, 1);
    updateOrderSummary();
});

$(document).on('click', '.increase-qty', function() {
    const index = $(this).data('index');
    if (orderItems[index].quantity < orderItems[index].stock) {
        orderItems[index].quantity++;
        orderItems[index].subtotal = orderItems[index].quantity * orderItems[index].price;
        updateOrderSummary();
    } else {
        alert('Not enough stock!');
    }
});

$(document).on('click', '.decrease-qty', function() {
    const index = $(this).data('index');
    if (orderItems[index].quantity > 1) {
        orderItems[index].quantity--;
        orderItems[index].subtotal = orderItems[index].quantity * orderItems[index].price;
        updateOrderSummary();
    }
});
</script>

<?php include '../views/layouts/footer.php'; ?>