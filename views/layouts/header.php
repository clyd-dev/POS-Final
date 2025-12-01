<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>POS</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/custom">
    <style>
        /* Center brand link content */
        .custom-brand {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            flex-direction: column !important;
            padding: 22px 0 !important;
            gap: 4px;
            text-decoration: none !important;
            transition: all 0.3s ease-in-out;
        }

        /* Center inner text/icon */
        .brand-center {
            text-align: center !important;
        }

        /* Icon styling */
        .custom-brand .brand-image {
            font-size: 25px;
            padding: 14px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.09);
            color: #ffc107;
            transition: 0.3s ease;
            box-shadow: 0 0 0 rgba(255,193,7,0); /* for glow animation */
        }

        /* Text styling */
        .custom-brand .brand-text {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #fff;
            transition: 0.3s ease;
        }

        /* Slight scale + glow */
        .custom-brand:hover .brand-image {
            transform: scale(1.12);
            box-shadow: 0 0 12px rgba(255,193,7,0.7);
        }

        /* Text glow */
        .custom-brand:hover .brand-text {
            text-shadow: 0 0 8px rgba(255,193,7,0.8);
        }

        /* Slight upward animation */
        .custom-brand:hover {
            transform: translateY(-3px);
        }

        .custom-brand.active {
            background: rgba(255, 193, 7, 0.18) !important;
            box-shadow: inset 0 0 12px rgba(255,193,7,0.25);
        }

        .custom-brand.active .brand-text {
            color: #ffc107;
        }

        .custom-brand.active .brand-image {
            color: #ffc107;
            box-shadow: 0 0 12px rgba(255,193,7,0.7);
        }
        .low-stock { 
            background-color: #fff3cd; 
        }
        .out-of-stock { 
            background-color: #f8d7da; 
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" target="_blank">
                    <i class="fas fa-store"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    <?php echo $_SESSION['full_name']; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">
                        <strong><?php echo $_SESSION['role']; ?></strong>
                    </span>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo BASE_URL; ?>/user/changePassword/<?php echo $_SESSION['user_id']; ?>" class="dropdown-item">
                        <i class="fas fa-key mr-2"></i> Change Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    
    <?php include 'sidebar.php'; ?>