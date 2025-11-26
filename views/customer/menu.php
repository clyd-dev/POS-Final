<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="restaurant, menu, food" />
  <meta name="description" content="Restaurant Menu" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="<?php echo BASE_URL; ?>/assets/feane/images/favicon.png" type="">

  <title>Feane - Restaurant Menu</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/feane/css/bootstrap.css" />
  <!-- owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
  <!-- font awesome style -->
  <link href="<?php echo BASE_URL; ?>/assets/feane/css/font-awesome.min.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="<?php echo BASE_URL; ?>/assets/feane/css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="<?php echo BASE_URL; ?>/assets/feane/css/responsive.css" rel="stylesheet" />

</head>

<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box">
      <img src="<?php echo BASE_URL; ?>/assets/feane/images/hero-bg.jpg" alt="">
    </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="<?php echo BASE_URL; ?>/customer/menu">
            <span>Feane</span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  mx-auto ">
              <li class="nav-item active">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/customer/menu">Menu <span class="sr-only">(current)</span> </a>
              </li>
            </ul>
            <div class="user_option">
              <a href="<?php echo BASE_URL; ?>/auth/login" class="user_link">
                <i class="fa fa-user" aria-hidden="true"></i>
              </a>
              <a href="<?php echo BASE_URL; ?>/auth/login" class="order_online">
                Staff Login
              </a>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <!-- food section -->

  <section class="food_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Our Menu</h2>
      </div>

      <ul class="filters_menu">
        <li class="<?php echo !$current_category ? 'active' : ''; ?>" data-filter="*">
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
        <div class="row grid">
          <?php foreach ($menu_items as $item): ?>
          <div class="col-sm-6 col-lg-4 all <?php echo strtolower($item['category']); ?>">
            <div class="box">
              <div>
                <div class="img-box">
                  <img src="<?php echo BASE_URL; ?>/assets/feane/images/<?php echo $item['image'] ?: 'f1.png'; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                </div>
                <div class="detail-box">
                  <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                  <p><?php echo htmlspecialchars(substr($item['description'], 0, 80)); ?>...</p>
                  <div class="options">
                    <h6>₱<?php echo number_format($item['price'], 2); ?></h6>
                    <?php if ($item['stock'] > 0): ?>
                      <a href="#" class="add-to-cart" data-id="<?php echo $item['menu_id']; ?>">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                          <g>
                            <g>
                              <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                           c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                            </g>
                          </g>
                          <g>
                            <g>
                              <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                           C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                           c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                           C457.728,97.71,450.56,86.958,439.296,84.91z" />
                            </g>
                          </g>
                          <g>
                            <g>
                              <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                           c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                            </g>
                          </g>
                        </svg>
                      </a>
                    <?php else: ?>
                      <span class="badge badge-danger">Out of Stock</span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- end food section -->

  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>Contact Us</h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>Location</span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>Call +01 1234567890</span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>demo@gmail.com</span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <div class="footer_detail">
            <a href="" class="footer-logo">Feane</a>
            <p>Best restaurant in town. Serving delicious food since 2024.</p>
            <div class="footer_social">
              <a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a>
              <a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a>
              <a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a>
              <a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>Opening Hours</h4>
          <p>Everyday</p>
          <p>10.00 AM - 10.00 PM</p>
        </div>
      </div>
      <div class="footer-info">
        <p>&copy; 2024 All Rights Reserved By Restaurant POS</p>
      </div>
    </div>
  </footer>
  <!-- footer section -->

  <!-- jQery -->
  <script src="<?php echo BASE_URL; ?>/assets/feane/js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <!-- bootstrap js -->
  <script src="<?php echo BASE_URL; ?>/assets/feane/js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- isotope js -->
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <!-- custom js -->
  <script src="<?php echo BASE_URL; ?>/assets/feane/js/custom.js"></script>

</body>

</html>