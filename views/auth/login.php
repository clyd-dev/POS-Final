<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Restaurant POS</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
<style>
    /* CLEAN, MODERN GRADIENT BACKGROUND */
    .login-page {
        background: linear-gradient(135deg, #1f1c2c 0%, #928dab 100%);
        background-size: 400% 400%;
        animation: gradientMove 12s ease infinite;
        position: relative;
        overflow: hidden;
    }

    /* SUBTLE LIGHT GLOW SHAPES */
    .login-page::before,
    .login-page::after {
        content: "";
        position: absolute;
        width: 600px;
        height: 600px;
        filter: blur(140px);
        opacity: 0.45;
        z-index: 0;
    }

    /* PURPLE GLOW */
    .login-page::before {
        background: #9b59b6;
        top: -200px;
        left: -150px;
    }

    /* ORANGE GLOW */
    .login-page::after {
        background: #ff9800;
        bottom: -200px;
        right: -150px;
    }

    /* MOVE THE BACKGROUND SLOWLY */
    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* CARD UI ENHANCEMENT (NO CODE CHANGES — JUST LOOK IMPROVED) */
    .card {
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 15px 50px rgba(0,0,0,0.35);
    }

    /* LOGIN BOX WIDTH */
    .login-box {
        width: 400px;
        z-index: 2; /* ensure above glow */
    }

    /* LOGO REFINEMENT (but still uses your same HTML) */
    .login-logo a {
        color: #fff;
        font-weight: bold;
        font-size: 2.2rem;
        text-shadow: 0 5px 20px rgba(0,0,0,0.5);
    }
</style>

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b><i class="fas fa-utensils brand-image"></i></b> POS</a>
    </div>
    
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo BASE_URL; ?>/auth/login" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">    
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
            </form>
            
            <p class="mb-0 mt-3 text-center">
                <small class="text-muted"><i class="fas fa-utensils brand-image"></i></small>
            </p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>