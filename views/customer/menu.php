<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Restaurant Menu - Our Delicious Food</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Owl Carousel -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <!-- ----- -->
   <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <style>
    /* Feane Template Styles */
    :root {
      --primary-color: #ffbe33;
      --secondary-color: #222831;
      --light-bg: #f8f9fa;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      color: #333;
      background-color: #fff;
    }

    .hero_area {
      position: relative;
      min-height: 400px;
      background: #222831;
    }
    
    .sub_page .hero_area {
    min-height: auto;
    }
    
    .sub_page .hero_area .bg-box {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    }

    .sub_page .hero_area .bg-box img {
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
        object-fit: cover;
    -o-object-position: right top;
        object-position: right top;
    }

    .header_section {
      position: relative;
      z-index: 10;
      padding: 20px 0;
    }
    
    .custom_nav-container {
      padding: 10px 0;
    }
    
    .navbar-brand {
      font-size: 28px;
      font-weight: 700;
      color: #fff !important;
      text-transform: uppercase;
      letter-spacing: 2px;
    }
    
    .navbar-nav .nav-link {
      color: #fff !important;
      font-size: 16px;
      font-weight: 500;
      padding: 10px 20px;
      transition: all 0.3s;
    }
    
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: var(--primary-color) !important;
    }
    
    .user_option {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .user_option a {
      color: #fff;
      font-size: 18px;
      transition: all 0.3s;
    }
    
    .user_option a:hover {
      color: var(--primary-color);
    }
    
    .order_online {
      background-color: var(--primary-color);
      color: #fff !important;
      padding: 10px 30px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s;
    }
    
    .order_online:hover {
      background-color: #e6aa2e;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255,190,51,0.3);
    }
    
    .food_section {
      padding: 80px 0;
      background-color: var(--light-bg);
    }
    
    .heading_container {
      text-align: center;
      margin-bottom: 50px;
    }
    
    .heading_container h2 {
      font-size: 42px;
      font-weight: 700;
      color: var(--secondary-color);
      position: relative;
      display: inline-block;
      padding-bottom: 15px;
    }
    
    .heading_container h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background-color: var(--primary-color);
      border-radius: 2px;
    }
    
    .filters_menu {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 50px;
      padding: 0;
      list-style: none;
    }
    
    .filters_menu li {
      cursor: pointer;
    }
    
    .filters_menu li a {
      display: inline-block;
      padding: 12px 30px;
      background-color: #fff;
      color: var(--secondary-color);
      border: 2px solid var(--primary-color);
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s;
    }
    
    .filters_menu li.active a,
    .filters_menu li a:hover {
      background-color: var(--primary-color);
      color: #fff;
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(255,190,51,0.3);
    }
    
    .box {
      background-color: #fff;
      border-radius: 15px;
      overflow: hidden;
      margin-bottom: 30px;
      transition: all 0.3s;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .box:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .img-box {
      position: relative;
      overflow: hidden;
      height: 250px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .img-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s;
    }
    
    .box:hover .img-box img {
      transform: scale(1.1);
    }
    
    .detail-box {
      padding: 25px;
    }
    
    .detail-box h5 {
      font-size: 22px;
      font-weight: 700;
      color: var(--secondary-color);
      margin-bottom: 10px;
    }
    
    .detail-box p {
      color: #666;
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 20px;
    }
    
    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .options h6 {
      font-size: 24px;
      font-weight: 700;
      color: var(--primary-color);
      margin: 0;
    }
    
    .options a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background-color: var(--primary-color);
      border-radius: 50%;
      transition: all 0.3s;
    }
    
    .options a:hover {
      background-color: var(--secondary-color);
      transform: rotate(360deg);
    }
    
    .options a svg {
      width: 20px;
      height: 20px;
      fill: #fff;
    }
    
    .badge-danger {
      background-color: #dc3545;
      color: #fff;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    
    .footer_section {
      background-color: var(--secondary-color);
      color: #fff;
      padding: 60px 0 20px;
    }
    
    .footer-col {
      margin-bottom: 30px;
    }
    
    .footer_section h4 {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 20px;
      color: var(--primary-color);
    }
    
    .contact_link_box a {
      display: flex;
      align-items: center;
      color: #ccc;
      text-decoration: none;
      margin-bottom: 15px;
      transition: all 0.3s;
    }
    
    .contact_link_box a:hover {
      color: var(--primary-color);
      padding-left: 10px;
    }
    
    .contact_link_box a i {
      margin-right: 10px;
      color: var(--primary-color);
    }
    
    .footer-logo {
      font-size: 28px;
      font-weight: 700;
      color: #fff;
      text-decoration: none;
    }
    
    .footer_detail p {
      color: #ccc;
      line-height: 1.8;
      margin-top: 15px;
    }
    
    .footer_social {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }
    
    .footer_social a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background-color: var(--primary-color);
      color: #fff;
      border-radius: 50%;
      transition: all 0.3s;
    }
    
    .footer_social a:hover {
      background-color: #fff;
      color: var(--secondary-color);
      transform: translateY(-5px);
    }
    
    .footer-info {
      text-align: center;
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.1);
      color: #999;
    }
    
    .footer-info a {
      color: var(--primary-color);
      text-decoration: none;
    }
    
    @media (max-width: 768px) {
      .heading_container h2 {
        font-size: 32px;
      }
      
      .filters_menu {
        gap: 5px;
      }
      
      .filters_menu li a {
        padding: 8px 15px;
        font-size: 14px;
      }
      
      .box {
        margin-bottom: 20px;
      }
    }
  </style>
</head>

<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box">
        <div class="bg-box">
            <div style="width:100%; height:100%; background: linear-gradient(135deg, rgba(102,126,234,0.8) 0%, rgba(118,75,162,0.8) 100%);"></div>
            <!-- <img src="POS/view/customer/images/hero-bg.jpg" alt=""> -->
        </div>    
    </div>
    
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="<?php echo BASE_URL; ?>/customer/menu">
            <span>🍽️ Feane</span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2730%27 height=%2730%27 viewBox=%270 0 30 30%27%3e%3cpath stroke=%27rgba%28255, 255, 255, 0.9%29%27 stroke-linecap=%27round%27 stroke-miterlimit=%2710%27 stroke-width=%272%27 d=%27M4 7h22M4 15h22M4 23h22%27/%3e%3c/svg%3e');"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
              <li class="nav-item active">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/customer/menu">Menu</a>
              </li>
            </ul>
            <div class="user_option">
              <a href="<?php echo BASE_URL; ?>/auth/login" title="Staff Login">
                <i class="fa fa-user"></i>
              </a>
              <a href="<?php echo BASE_URL; ?>/auth/login" class="order_online">
                Staff Login
              </a>
            </div>
          </div>
        </nav>
      </div>
    </header>
  </div>

  <!-- food section -->
  <section class="food_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Our Menu</h2>
      </div>

      <ul class="filters_menu">
        <li class="<?php echo !$current_category ? 'active' : ''; ?>">
          <a href="<?php echo BASE_URL; ?>/customer/menu">All</a>
        </li>
        <?php foreach ($categories as $cat): ?>
        <li class="<?php echo ($current_category == $cat['category']) ? 'active' : ''; ?>">
          <a href="<?php echo BASE_URL; ?>/customer/menu?category=<?php echo $cat['category']; ?>">
            <?php echo $cat['category']; ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>

      <div class="filters-content">
        <div class="row">
          <?php if (!empty($menu_items)): ?>
            <?php foreach ($menu_items as $item): ?>
            <div class="col-sm-6 col-lg-4">
              <div class="box">
                <div class="img-box">
                  <img src="https://via.placeholder.com/400x300/667eea/ffffff?text=<?php echo urlencode($item['name']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                </div>
                <div class="detail-box">
                  <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                  <p><?php echo htmlspecialchars(substr($item['description'], 0, 80)); ?>...</p>
                  <div class="options">
                    <h6>₱<?php echo number_format($item['price'], 2); ?></h6>
                    <?php if ($item['stock'] > 0): ?>
                      <a href="#" onclick="alert('Item added to cart!'); return false;">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 456.029 456.029">
                          <g><path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z"/></g>
                          <g><path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4C457.728,97.71,450.56,86.958,439.296,84.91z"/></g>
                          <g><path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z"/></g>
                        </svg>
                      </a>
                    <?php else: ?>
                      <span class="badge badge-danger">Out of Stock</span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-info text-center">
                <h4>No menu items available at the moment.</h4>
                <p>Please check back later or contact us for more information.</p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>Contact Us</h4>
            <div class="contact_link_box">
              <a href="#">
                <i class="fa fa-map-marker"></i>
                <span>123 Restaurant Street, Food City</span>
              </a>
              <a href="tel:+01234567890">
                <i class="fa fa-phone"></i>
                <span>Call +01 1234567890</span>
              </a>
              <a href="mailto:info@restaurant.com">
                <i class="fa fa-envelope"></i>
                <span>info@restaurant.com</span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <div class="footer_detail">
            <a href="#" class="footer-logo">Feane</a>
            <p>Best restaurant in town. Serving delicious food with passion since 2024. Quality ingredients, amazing taste!</p>
            <div class="footer_social">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>Opening Hours</h4>
          <p><strong>Monday - Sunday</strong></p>
          <p>10:00 AM - 10:00 PM</p>
          <p class="mt-3"><strong>Kitchen Closes:</strong> 9:30 PM</p>
        </div>
      </div>
      <div class="footer-info">
        <p>&copy; <?php echo date('Y'); ?> All Rights Reserved By Restaurant POS System</p>
      </div>
    </div>
  </footer>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Owl Carousel -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

</body>
</html>