<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales Reports</h1>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo BASE_URL; ?>/sales/export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="btn btn-success float-right">
                        <i class="fas fa-file-export"></i> Export to CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            
            <?php include '../views/includes/alerts.php'; ?>
            
            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $today_sales ? $today_sales['total_orders'] : 0; ?></h3>
                            <p>Today's Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>₱<?php echo number_format($today_sales ? $today_sales['total_revenue'] : 0, 2); ?></h3>
                            <p>Today's Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $weekly_sales['total_orders']; ?></h3>
                            <p>Weekly Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>₱<?php echo number_format($monthly_sales['total_revenue'], 2); ?></h3>
                            <p>Monthly Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Sales Trend Chart -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sales Trend</h3>
                            <div class="card-tools">
                                <form class="form-inline" method="GET">
                                    <input type="date" name="start_date" class="form-control form-control-sm mr-2" value="<?php echo $start_date; ?>" required>
                                    <input type="date" name="end_date" class="form-control form-control-sm mr-2" value="<?php echo $end_date; ?>" required>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="salesTrendChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Best Selling Items -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top 10 Best Sellers</h3>
                        </div>
                        <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Sold</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($best_sellers as $item): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                            <small class="text-muted"><?php echo $item['category']; ?></small>
                                        </td>
                                        <td><span class="badge badge-primary"><?php echo $item['total_sold']; ?></span></td>
                                        <td>₱<?php echo number_format($item['total_revenue'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Daily Sales Table -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daily Sales Summary</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Total Orders</th>
                                        <th>Total Revenue</th>
                                        <th>Average Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($daily_sales as $sale): ?>
                                    <tr>
                                        <td><?php echo date('F d, Y', strtotime($sale['sale_date'])); ?></td>
                                        <td><?php echo $sale['total_orders']; ?></td>
                                        <td>₱<?php echo number_format($sale['total_revenue'], 2); ?></td>
                                        <td>₱<?php echo number_format($sale['total_orders'] > 0 ? $sale['total_revenue'] / $sale['total_orders'] : 0, 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Sales by Category -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sales by Category</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
// Sales Trend Chart
const trendCtx = document.getElementById('salesTrendChart').getContext('2d');
const trendData = <?php echo json_encode($sales_trend); ?>;

const trendLabels = trendData.map(item => item.sale_date);
const trendRevenues = trendData.map(item => parseFloat(item.total_revenue));

new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'Revenue',
            data: trendRevenues,
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Category Pie Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryData = <?php echo json_encode($category_sales); ?>;

const categoryLabels = categoryData.map(item => item.category);
const categoryRevenues = categoryData.map(item => parseFloat(item.total_revenue));

new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryRevenues,
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php include '../views/layouts/footer.php'; ?>