$(document).ready(function() {
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
    
    // Confirm delete actions
    $('.delete-btn').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Initialize DataTables with custom options
    if ($('.datatable').length > 0) {
        $('.datatable').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "ordering": true,
            "info": true,
            "searching": true,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    }
    
    // Number formatting for currency inputs
    $('input[type="number"][step="0.01"]').on('blur', function() {
        const value = parseFloat($(this).val());
        if (!isNaN(value)) {
            $(this).val(value.toFixed(2));
        }
    });
    
    // Prevent form double submission
    $('form').on('submit', function() {
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
    });
    
    // Real-time search for menu items
    $('#searchMenu').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('.menu-item').filter(function() {
            $(this).toggle($(this).data('name').indexOf(value) > -1);
        });
    });
    
    // Format currency display
    $('.currency').each(function() {
        const value = parseFloat($(this).text());
        if (!isNaN(value)) {
            $(this).text('₱' + value.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }
    });
    
    // Sidebar toggle state persistence
    $('.nav-link[data-widget="pushmenu"]').on('click', function() {
        setTimeout(function() {
            const collapsed = $('body').hasClass('sidebar-collapse');
            localStorage.setItem('sidebar-collapsed', collapsed);
        }, 300);
    });
    
    // Restore sidebar state
    const sidebarCollapsed = localStorage.getItem('sidebar-collapsed');
    if (sidebarCollapsed === 'true') {
        $('body').addClass('sidebar-collapse');
    }
    
    // Print receipt
    window.printReceipt = function() {
        window.print();
    };
    
    // Numeric input validation
    $('input[type="number"]').on('keypress', function(e) {
        const charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
            e.preventDefault();
            return false;
        }
    });
    
    // Clear modal forms on close
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });
    
});

/**
 * Format number as currency
 */
function formatCurrency(amount) {
    return '₱' + parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

/**
 * Show loading spinner
 */
function showLoading() {
    $('body').append('<div class="loading-overlay"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
}

/**
 * Hide loading spinner
 */
function hideLoading() {
    $('.loading-overlay').remove();
}

/**
 * Show toast notification
 */
function showToast(message, type = 'success') {
    const toast = `
        <div class="toast-notification toast-${type}">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
            ${message}
        </div>
    `;
    $('body').append(toast);
    setTimeout(function() {
        $('.toast-notification').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 3000);
}