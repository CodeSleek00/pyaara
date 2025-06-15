<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <title>Document</title>
</head>
<body>
    <!-- News Ticker -->
  <div class="news-ticker">
    <div class="news-item" id="news-item">
      Free shipping on orders over $50! | New collection coming soon!
    </div>
  </div>

  <!-- Header Section -->
  <header>
    <!-- Desktop Search Bar -->
    <section class="search">
      <img src="images/Pyaara Site Svg.svg" alt="Logo" class="logo" />
      <input type="search" placeholder="Search..." />
      <button class="search-button">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
      <a href="test/cart.php"><img width="40" height="40" src="https://img.icons8.com/ios-glyphs/60/shopping-cart--v1.png" alt="Shopping Cart" style="margin-left: 20px;" /></a>
     <div id="navbar">
    <div id="nav-right">
      <div class="account-menu">
        <span class="account-icon">
          <img width="50" height="50" src="https://img.icons8.com/pulsar-line/96/FA5252/user.png" alt="user"/>
        </span>
        <div class="account-dropdown">
          <a href="signup.html" id="signupLink">Signup</a>
          <a href="login.html" id="loginLink">Login</a>
          <a href="profile.php" id="profileLink" style="display:none;">My Profile</a>
          <a href="logout.php" id="logoutLink" style="display:none;">Logout</a>
        </div>
      </div>
    </div>
  </div>
    </section>
<!-- Mobile Header with only Logo -->
<header class="mobile-header-logo-only">
  <img src="images/Pyaara Site Svg.svg" alt="Logo" class="logo" height="330px" />
</header>

    <!-- Navigation -->
    <section class="nav">
      <!-- Mobile Menu Button -->
      <div class="menu-toggle"></div>

      <!-- Desktop Navigation -->
      <nav class="nav-container">
        <ul>
          <li><a href="#">Home</a></li>
          <li class="dropdown">
            <a href="#">Categories</a>
            <div class="dropdown-content">
              <a href="#">T-Shirts</a>
              <a href="#">Hoodies</a>
              <a href="#">Accessories</a>
              <a href="#">Posters</a>
            </div>
          </li>
          <li class="dropdown">
            <a href="#">Men's</a>
            <div class="dropdown-content">
              <a href="#">Shirts</a>
              <a href="#">Pants</a>
              <a href="#">Shoes</a>
              <a href="#">Watches</a>
            </div>
          </li>
          <li class="dropdown">
            <a href="#">Women's</a>
            <div class="dropdown-content">
              <a href="#">Dresses</a>
              <a href="#">Tops</a>
              <a href="#">Jewelry</a>
              <a href="#">Bags</a>
            </div>
          </li>
          <li><a href="#">Anime</a></li>
          <li><a href="#">Offer's</a></li>
        </ul>
      </nav>

      <!-- Mobile Navigation Sidebar -->
      <nav class="mobile-nav">
        <ul>
          <li><a href="#">Home</a></li>
          <li class="mobile-dropdown">
            <a href="#">Categories</a>
            <div class="mobile-dropdown-content">
              <a href="#">T-Shirts</a>
              <a href="#">Hoodies</a>
              <a href="#">Accessories</a>
              <a href="#">Posters</a>
            </div>
          </li>
          <li class="mobile-dropdown">
            <a href="#">Men's</a>
            <div class="mobile-dropdown-content">
              <a href="#">Shirts</a>
              <a href="#">Pants</a>
              <a href="#">Shoes</a>
              <a href="#">Watches</a>
            </div>
          </li>
          <li class="mobile-dropdown">
            <a href="#">Women's</a>
            <div class="mobile-dropdown-content">
              <a href="#">Dresses</a>
              <a href="#">Tops</a>
              <a href="#">Jewelry</a>
              <a href="#">Bags</a>
            </div>
          </li>
          <li><a href="#">Anime</a></li>
          <li><a href="#">Offer's</a></li>
        </ul>
      </nav>

      <!-- Mobile Footer Menu -->
      <div class="mobile-footer-menu">
        <a href="#" class="footer-menu-item">
          <div class="menu-toggle footer-toggle">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <span>Menu</span>
        </a>
        <a href="#" class="footer-menu-item">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </a>
        <a href="#" class="footer-menu-item">
          <i class="fas fa-shopping-cart"></i>
          <span>Cart</span>
        </a>
        <a href="#" class="footer-menu-item">
            <i class="fa-solid fa-list"></i> 
            <span>Category</span>
        </a>
        <a href="user.php" class="footer-menu-item">
          <i class="fas fa-user"></i>
          <span>Account</span>
        </a>
      </div>
    </section>
</header>
<script src="script.js"></script>
