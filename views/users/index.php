<?php include '../views/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">User Management</h1>
                </div>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUserModal">
                        <i class="fas fa-user-plus"></i> Add New User
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
            
            <?php include '../views/includes/alerts.php'; ?>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Users</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <?php if ($user['role'] == 'Admin'): ?>
                                        <span class="badge badge-danger">Admin</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Cashier</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['status'] == 'Active'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning edit-user-btn" 
                                            data-id="<?php echo $user['user_id']; ?>"
                                            data-fullname="<?php echo htmlspecialchars($user['full_name']); ?>"
                                            data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                            data-role="<?php echo $user['role']; ?>"
                                            data-status="<?php echo $user['status']; ?>"
                                            data-toggle="modal" 
                                            data-target="#editUserModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#changePasswordModal" 
                                            onclick="$('#change_user_id').val(<?php echo $user['user_id']; ?>)">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                    <a href="<?php echo BASE_URL; ?>/user/delete/<?php echo $user['user_id']; ?>" class="btn btn-sm btn-danger delete-btn">
                                        <i class="fas fa-trash"></i>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>/user/create" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Add New User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label>Role *</label>
                        <select name="role" class="form-control" required>
                            <option value="Cashier">Cashier</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm" action="<?php echo BASE_URL; ?>/user/update/<?php echo $user['user_id']; ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <input type="hidden" name="id" id="edit_user_id" value="<?php echo $user['user_id']; ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Role *</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            <option value="Cashier">Cashier</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="changePasswordForm" action="" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="change_user_id" value="">
                    <div class="form-group">
                        <label>New Password *</label>
                        <input type="password" name="new_password" class="form-control" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-control" minlength="6" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Edit user button
$('.edit-user-btn').on('click', function() {
    const id = $(this).data('id');
    const fullname = $(this).data('fullname');
    const email = $(this).data('email');
    const role = $(this).data('role');
    const status = $(this).data('status');
    
    $('#editUserForm').attr('action', '<?php echo BASE_URL; ?>/user/update/<?php echo $user['user_id']; ?>');
    $('#edit_full_name').val(fullname);
    $('#edit_email').val(email);
    $('#edit_role').val(role);
    $('#edit_status').val(status);
});

// Change password form submit
$('#changePasswordForm').on('submit', function(e) {
    e.preventDefault();
    const userId = $('#change_user_id').val();
    $(this).attr('action', '<?php echo BASE_URL; ?>/user/changePassword/' + userId);
    this.submit();
});
</script>

<?php include '../views/layouts/footer.php'; ?>