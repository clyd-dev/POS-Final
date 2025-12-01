<!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?php echo BASE_URL; ?>/dashboard" class="brand-link">
            <i class="fas fa-utensils brand-image"></i>
            <span class="brand-text font-weight-light">Restaurant POS</span>
        </a>
        
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x text-white"></i>
                </div>
                <div class="info">
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="d-block"><?php echo $_SESSION['full_name']; ?></a>
                    <small class="text-muted"><?php echo $_SESSION['role']; ?></small>
                </div>
            </div>
            
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/dashboard" class="nav-link <?php echo (isset($page_title) && $page_title == 'Dashboard') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/order/create" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>New Order</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/order" class="nav-link <?php echo (isset($page_title) && $page_title == 'Orders') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Orders</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/menu" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'Menu') !== false) ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Menu Management</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/inventory" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'Inventory') !== false) ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Inventory</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/sales" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'Sales') !== false) ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Sales Reports</p>
                        </a>
                    </li>
                    
                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/user" class="nav-link <?php echo (isset($page_title) && strpos($page_title, 'User') !== false) ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </aside>