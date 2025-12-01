<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Register New User</h1>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Registration Form</h3>
                        </div>
                        <form action="<?php echo BASE_URL; ?>/auth/register" method="POST">
                            <div class="card-body">
                                
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (isset($_SESSION['errors'])): ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <ul class="mb-0">
                                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                                <li><?php echo $error; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php unset($_SESSION['errors']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="form-group">
                                    <label>Full Name *</label>
                                    <input type="text" name="full_name" class="form-control" placeholder="Enter full name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Username *</label>
                                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter email">
                                </div>
                                
                                <div class="form-group">
                                    <label>Password *</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password" minlength="6" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Role *</label>
                                    <select name="role" class="form-control" required>
                                        <option value="">Select Role</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Cashier">Cashier</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Register User</button>
                                <a href="<?php echo BASE_URL; ?>/user" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>