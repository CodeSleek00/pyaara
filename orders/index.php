<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="images/Pyaara Circle.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Add these meta tags -->
    <meta name="description" content="Pyaara - Premium quality anime and exclusive t-shirts for men and women. Shop our latest collections with great discounts and free shipping on orders over $50.">
    <meta name="keywords" content="anime t-shirts, exclusive t-shirts, men's fashion, women's fashion, oversized t-shirts, F1 racing t-shirts, Indian clothing brand">
    <meta name="author" content="Pyaara">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="Pyaara - Premium Quality T-Shirts & Apparel">
    <meta property="og:description" content="Shop the latest collection of anime and exclusive t-shirts for men and women at Pyaara.">
    <meta property="og:image" content="https://pyaara.in/images/Pyaara-Site-Svg.svg">
    <meta property="og:url" content="https://pyaara.in">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Pyaara - Premium Quality T-Shirts & Apparel">
    <meta name="twitter:description" content="Shop the latest collection of anime and exclusive t-shirts for men and women at Pyaara.">
    <meta name="twitter:image" content="https://pyaara.in.com/images/Pyaara-Site-Svg.svg">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://pyaara.in" />
    
    <!-- Rest of your head content -->
    <style>
      :root {
    --primary-color: #3498db;
    --secondary-color: #2ecc71;
    --text-color: #333;
    --background-color: #f4f4f4;
    --red-accent: #ff0000;
    --red-hover: #fdc7c7;
    --mobile-nav-width: 60%;
    --transition-speed: 0.3s;
    --primary-white: #ffffff;
    --primary-yellow: #FFD700;
    --primary-red: #FF0000;
    --secondary-yellow: #FFFACD;
    --secondary-red: #FFCCCB;
    --dark-gray: #333333;
    --light-gray: #f5f5f5;
}

* {
    font-family: "Outfit", sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* News Ticker */
.news-ticker {
    width: 100%;
    background-color: var(--red-accent);
    color: white;
    padding: 6px;
    text-align: center;
    font-size: 0.85rem;
    z-index: 1000;
}

/* Search Styles */
.search {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.6rem 0.8rem;
    background-color: white;
    position: relative;
}

.logo {
    height: 50px;
    width: auto;
    margin-right: 0.8rem;
    cursor: pointer;
}

.search-container {
    display: flex;
    align-items: center;
    flex-grow: 1;
    max-width: 500px;
    margin: 0 1.5rem;
}

.search input[type="search"] {
    width: 100%;
    padding: 0.6rem 1rem;
    border: 1px solid #ddd;
    border-radius: 12px;
    font-size: 0.9rem;
    outline: none;
    transition: all var(--transition-speed) ease;
}

.search input[type="search"]:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.search-button {
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0.6rem 1.2rem;
    margin-left: -56px;
    cursor: pointer;
    transition: all var(--transition-speed) ease;
}

.search-button:hover {
    background-color: #2980b9;
}

.search-button i {
    font-size: 0.9rem;
}

.search img {
    margin-left: 1.2rem;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.search img:hover {
    transform: scale(1.05);
}

.search hr {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    border: none;
    height: 1px;
    background-color: #eee;
    margin: 0;
}

.signup {
    text-decoration: none;
    cursor: pointer;
}

/* Navigation Styles */
.nav {
    background-color: #ffffff;
    border-bottom: 2px solid #ffcc00;
    padding: 1rem 1.5rem;
    position: relative;
    z-index: 1000;
}

.nav-container ul {
    display: flex;
    list-style: none;
    gap: 1.5rem;
    align-items: center;
    justify-content: center;
}

.nav-container a {
    text-decoration: none;
    color: #1a1a1a;
    font-weight: 600;
    font-size: 0.9rem;
    position: relative;
    padding: 0.4rem 0;
    transition: color 0.3s ease;
    cursor: pointer;
}

.nav-container a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: #ff4d4f;
    transition: width 0.3s ease;
}

.nav-container a:hover {
    color: #ff4d4f;
}

.nav-container a:hover::after {
    width: 100%;
}

/* Dropdown Menu */
.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #ffffff;
    min-width: 180px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 0.4rem;
    z-index: 1;
    opacity: 0;
    transform: translateY(-8px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.dropdown:hover .dropdown-content {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.dropdown-content a {
    display: block;
    padding: 0.6rem 0.8rem;
    color: #1a1a1a;
    font-size: 0.85rem;
    transition: background-color 0.2s ease;
    cursor: pointer;
}

.dropdown-content a:hover {
    background-color: #fff3cd;
}

/* Mobile Styles */
.menu-toggle {
    display: none;
    cursor: pointer;
    position: fixed;
    top: 0.8rem;
    right: 1.2rem;
    z-index: 1001;
}

.menu-toggle span {
    display: block;
    width: 22px;
    height: 2px;
    background-color: #1a1a1a;
    margin: 4px 0;
    transition: all 0.3s ease;
}

.mobile-nav {
    position: fixed;
    top: 0;
    left: -70%;
    width: 70%;
    height: 100vh;
    background-color: #ffffff;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    transition: left 0.3s ease;
    overflow-y: auto;
}

.mobile-nav.active {
    left: 0;
}

.mobile-nav ul {
    list-style: none;
    padding: 1.5rem 0.8rem;
}

.mobile-nav a {
    display: block;
    padding: 0.8rem;
    text-decoration: none;
    color: #1a1a1a;
    font-weight: 500;
    font-size: 0.9rem;
    border-bottom: 1px solid #eee;
    cursor: pointer;
}

.mobile-dropdown-content {
    display: none;
    padding-left: 0.8rem;
    background-color: #fefae0;
}

.mobile-dropdown.active .mobile-dropdown-content {
    display: block;
}

.mobile-dropdown > a::after {
    content: '+';
    float: right;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.mobile-dropdown.active > a::after {
    content: '-';
}

/* Overlay */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Mobile Footer Menu */
.mobile-footer-menu {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #ffffff;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    padding: 0;
}

.footer-menu-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: #1a1a1a;
    font-size: 0.7rem;
    flex: 1;
    padding: 0.4rem 0;
    cursor: pointer;
}

.footer-menu-item i {
    font-size: 1rem;
    margin-bottom: 0;
}

.footer-menu-item span {
    margin-top: 0.1rem;
}

.footer-toggle {
    position: relative;
    top: auto;
    right: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 0.1rem;
    cursor: pointer;
}

/* Search for Mobile */
.search-mobile {
    display: none;
}

/* Hero Carousel */
.hero-desktop,
.hero-mobile {
    display: none;
    width: 100%;
    height: auto;
    overflow: hidden;
    position: relative;
    background: #f0f0f0;
}

.carousel {
    display: flex;
    height: auto;
    transition: transform 0.5s ease-in-out;
}

.carousel img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    flex-shrink: 0;
}

.click-overlay {
    position: absolute;
    top: 0;
    left: 0;
    height: auto;
    width: 100%;
    z-index: 5;
    cursor: pointer;
}

.voucher {
    width: 100%;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #ffffff;
}

.voucher img {
    width: 100%;
    max-width: 900px;
    height: auto;
    aspect-ratio: 8.5 / 2.75;
    border-radius: 6px;
}

/* Responsive design */
@media (max-width: 768px) {
    .search {
        display: none;
        flex-wrap: wrap;
        padding: 0;
    }

    .search-mobile {
        display: flex;
    }
    
    .section-nav {
        display: none;
    }
    
    .hero-mobile {
        display: block;
    }

    .hero-desktop {
        display: none;
    }

    .menu-toggle {
        display: block;
    }

    .nav-container ul {
        display: none;
    }

    .mobile-footer-menu {
        display: flex;
    }
    
    .nav {
        padding: 0;
    }
}

@media (min-width: 769px) {
    .hero-desktop {
        display: block;
    }

    .hero-mobile {
        display: none;
    }

    .menu-toggle {
        display: none;
    }

    .mobile-nav {
        display: none;
    }
}

/* Cart Badge */
.cart-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: var(--red-accent);
    color: white;
    font-size: 0.7rem;
    padding: 1px 5px;
    border-radius: 50%;
    font-weight: 700;
    pointer-events: none;
}

/* Pulse animation for cart icon */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1); }
}

.pulse {
    animation: pulse 0.5s ease;
}

/* User Dropdown Styles */
.user-dropdown {
    position: absolute;
    background: white;
    border: 1px solid #ddd;
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    border-radius: 0.3rem;
    z-index: 2000;
    min-width: 140px;
    padding: 0.4rem 0;
}

.user-dropdown a {
    display: block;
    padding: 0;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
}

.user-dropdown a:hover {
    background-color: var(--red-hover);
}

.mobile-header-logo-only {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: white;
    padding: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.mobile-header-logo-only .logo {
    height: 50px;
    width: auto;
}

.accordion-content {
    transition: max-height 0.2s ease-out, opacity 0.2s ease;
}

/* Hide on desktop */
@media (min-width: 769px) {
    .mobile-header-logo-only {
        display: none;
    }
}

.new-arrivals {
    padding: 0 15px;
    max-width: 1200px;
    margin: 0 auto;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark-gray);
    position: relative;
    padding-bottom: 8px;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(to right, var(--primary-red), var(--primary-yellow));
    border-radius: 2px;
}

.view-all {
    color: var(--primary-red);
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: all 0.3s ease;
}

.view-all:hover {
    color: var(--dark-gray);
    transform: translateX(3px);
}

.category-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.tab {
    padding: 6px 16px;
    background-color: var(--light-gray);
    border-radius: 16px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.tab.active {
    background-color: var(--primary-red);
    color: var(--primary-white);
}

.tab:hover:not(.active) {
    background-color: var(--secondary-red);
}

/* Desktop Grid Layout */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
}

/* Mobile Scroller Layout */
.products-scroller {
    display: none;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 15px;
    margin: 0 -15px;
}

.products-scroller::-webkit-scrollbar {
    display: none;
}

.scroller-inner {
    display: flex;
    gap: 15px;
    padding: 0 15px;
    animation: scroll 30s linear infinite;
}

@keyframes scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(calc(-220px * 6 - 15px * 6)); }
}

.product-card {
    background-color: var(--primary-white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    flex: 0 0 240px;
    scroll-snap-align: start;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
}

.product-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background-color: var(--primary-yellow);
    color: var(--dark-gray);
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 2;
}

.product-image {
    position: relative;
    height: 240px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.03);
}

.product-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transition: all 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.action-btn {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: var(--primary-white);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    font-size: 0.8rem;
}

.action-btn:hover {
    background-color: var(--primary-red);
    color: var(--primary-white);
    transform: scale(1.05);
}

.product-info {
    padding: 15px;
}

.product-category {
    color: #777;
    font-size: 0.8rem;
    margin-bottom: 4px;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.current-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-red);
}

.old-price {
    font-size: 0.85rem;
    color: #777;
    text-decoration: line-through;
}

.to-cart {
    width: 100%;
    padding: 8px;
    background-color: var(--primary-yellow);
    border: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.to-cart:hover {
    background-color: var(--dark-gray);
    color: var(--primary-white);
}

.rating {
    display: flex;
    gap: 2px;
    margin-bottom: 8px;
}

.star {
    color: var(--primary-yellow);
    font-size: 0.8rem;
}
  .account-menu {
      position: relative;
      display: inline-block;
      cursor: pointer;
    }

    .account-icon {
      font-size: 20px;
      padding: 5px 10px;
    }

    .account-dropdown {
      display: none;
      position: absolute;
      right: 0;
      background-color: white;
      min-width: 150px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      z-index: 100000;
    }

    .account-dropdown a {
      display: block;
      padding: 10px;
      text-decoration: none;
      color: black;
    }

    .account-dropdown a:hover {
      background-color: #f0f0f0;
    }

    .account-menu:hover .account-dropdown {
      display: block;
    }
/* Responsive styles */
@media (max-width: 1024px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 18px;
    }
    
    .product-image {
        height: 220px;
    }
}

@media (max-width: 768px) {
    .products-grid {
        display: none;
    }
    
    .products-scroller {
        display: block;
    }
    
    .product-image {
        height: 200px;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .product-card {
        flex: 0 0 200px;
    }
}

@media (max-width: 576px) {
    .product-card {
        flex: 0 0 180px;
    }
    
    .product-image {
        height: 180px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .category-tabs {
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        width: 100%;
    }
    .category-tabs::-webkit-scrollbar {
        display: none;
    }
}

@media (max-width: 480px) {
    .desktop-container {
        display: none;
    }
}

@media (min-width: 481px) {
    .mobile-container {
        display: none;
    }
}

.text-shadow {
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}

.hover\:scale-103:hover {
    transform: scale(1.02);
}

/* Animation for new arrivals */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

.product-card {
    animation: fadeIn 0.4s ease forwards;
}

.product-card:nth-child(1) { animation-delay: 0.05s; }
.product-card:nth-child(2) { animation-delay: 0.1s; }
.product-card:nth-child(3) { animation-delay: 0.15s; }
.product-card:nth-child(4) { animation-delay: 0.2s; }
.product-card:nth-child(5) { animation-delay: 0.25s; }
.product-card:nth-child(6) { animation-delay: 0.3s; }
        .mobile-header-logo-only {
            display: none;
        }
        @media (max-width:770px) {
            .mobile-header-logo-only {
                display: flex; justify-content: center; align-items: center; padding: 0px; position: relative;"
            }
            
        }
    </style>
    <title>Pyaara</title>
</head>
<body>
    <!-- News Ticker -->
  <div class="news-ticker">
    <div class="news-item" id="news-item">
      Free hipping on orders over $50! | New collection coming soon!
    </div>
  </div>

  <!-- Header Section -->
  <header>
    <!-- Desktop Search Bar -->
    <section class="search">
      <img src="images/Pyaara Site Svg.svg" alt="Logo" class="logo" />
      <form action="search_results.php" method="GET" style="display: flex; gap: 10px; width: 80%;" >
  <input type="search" name="query" placeholder="Search..." required>
  <button type="submit" class="search-button" >
    <i class="fa fa-search"></i>
  </button>
</form>
      </button>
      <a href="orders/cart.php"><img width="48" height="48" src="https://img.icons8.com/pulsar-line/48/FA5252/fast-cart.png" alt="fast-cart"/></a>
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
<header class="mobile-header-logo-only" >
  <!-- Centered Logo -->
  <img src="images/Pyaara Site Svg.svg" alt="Logo" class="logo" height="330px" style="margin: 0 auto;" />
  
  <!-- Search Icon on Right -->
   <a href="search_results.php">
  <img width="22" height="22" src="https://img.icons8.com/metro/52/1A1A1A/search.png" alt="search" style="position: absolute; right: 10px; top:15px" /></a>
</header>
    <!-- Navigation -->
    <section class="nav">
      <!-- Mobile Menu Button -->
      <div class="menu-toggle"></div>

      <!-- Desktop Navigation -->
      <nav class="nav-container">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li class="dropdown">
            <a href="#">Categories</a>
            <div class="dropdown-content">
               <a href="orders/anime.php">Anime</a>
              <a href="orders/exclusive.php">Exclusive</a>
              <a href="orders/womens.php">Women's</a>
              <a href="#">F1 racing (Coming Soon)</a>
            </div>
          </li>
          <li class="dropdown">
            <a href="#">Men's</a>
            <div class="dropdown-content">
              <a href="orders/exclusive.php">Exclusive</a>
              <a href="orders/anime.php">Anime</a>
              <a href="orders/oversized.php">Oversized</a>
            </div>
          </li>
          <li class="dropdown">
            <a href="#">Women's</a>
            <div class="dropdown-content">
               <a href="orders/cutie.php">Cutiesssss</a>
              <a href="orders/oversized.php">Oversized</a>
              <a href="orders/exclusive.php">Exclusive</a>
            </div>
          </li>
          <li><a href="orders/anime.php">Anime</a></li>
          <li><a href="orders/offer.php">Offer's</a></li>
        </ul>
      </nav>

      <!-- Mobile Navigation Sidebar -->
      <nav class="mobile-nav">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li class="mobile-dropdown">
            <a href="#">Categories</a>
            <div class="mobile-dropdown-content">
              <a href="orders/anime.php">Anime</a>
              <a href="orders/exclusive.php">Exclusive</a>
              <a href="orders/womens.php">Women's</a>
              <a href="#">F1 racing (Coming Soon)</a>
            </div>
          </li>
          <li class="mobile-dropdown">
            <a href="#">Men's</a>
            <div class="mobile-dropdown-content">
              <a href="orders/exclusive.php">Exclusive</a>
              <a href="orders/anime.php">Anime</a>
              <a href="orders/oversized.php">Oversized</a>
            </div>
          </li>
          <li class="mobile-dropdown">
            <a href="#">Women's</a>
            <div class="mobile-dropdown-content">
              <a href="orders/cutie.php">Cutiesssss</a>
              <a href="orders/oversized.php">Oversized</a>
              <a href="orders/exclusive.php">Exclusive</a>
            </div>
          </li>
          <li><a href="pyaarasuggest.php">Pyaara Suggest</a></li>
          <li><a href="orders/offer.php">Offer's</a></li>
          <li><a href="orders/search_order.php">Delivery Status</a></li>
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
        <a href="index.php" class="footer-menu-item">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </a>
        <a href="orders/cart.php" class="footer-menu-item">
          <i class="fas fa-shopping-cart"></i>
          <span>Cart</span>
        </a>
        <a href="orders/anime.php" class="footer-menu-item">
            <i class="fa-solid fa-list"></i> 
            <span>Suggestion</span>
        </a>
        <a href="user.php" class="footer-menu-item">
          <i class="fas fa-user"></i>
          <span>Account</span>
        </a>
      </div>
    </section>
</header>
  <!-- 🌐 DESKTOP HERO SECTION -->
  <section class="hero-desktop">
    <div class="carousel" id="desktop-carousel">
      <img src="images/Hero section 1.jpg" alt="T-shirt Landscape 1">
      <img src="images/Hero section 2.jpg" alt="T-shirt Landscape 2">
      <img src="images/Hero section 3.jpg" alt="T-shirt Landscape 3">
    </div>
    <div class="click-overlay" id="desktop-overlay"></div>
  </section>

  <!-- 📱 MOBILE HERO SECTION -->
  <section class="hero-mobile">
    <div class="carousel" id="mobile-carousel">
      <img src="images/hero-mobile1.jpg" alt="T-shirt Portrait 1">
      <img src="images/hero-mobile2.jpg" alt="T-shirt Portrait 2">
      <img src="images/hero-mobile3.jpg" alt="T-shirt Portrait 3">
    </div>
    <div class="click-overlay" id="mobile-overlay"></div>
  </section>
  <section class="voucher">
    <img src="images/voucher.jpg" alt="">
  </section>
     <section class="new-arrivals">
        <div class="section-header">
            <h2 class="section-title">New Arrivals</h2>
            <a href="orders/exclusive.php" class="view-all">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Desktop Grid Layout -->
        <div class="products-grid">
            <!-- Product cards will be duplicated here for desktop -->
            <!-- Product 1 -->
            <div class="product-card"  onclick="window.location.href='orders/product_detail.php?id=23'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 012.jpg" alt="Graphic T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Ammmmm hmmmm....</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star-half-alt star"></i>
                        <span>(42)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                        <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=23'">
                        Buy Now
                    </button>

                    </button>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="product-card"  onclick="window.location.href='orders/product_detail.php?id=25'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 014.jpg" alt="Plain T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Yummy In The Tummy</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(28)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=25'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="product-card"  onclick="window.location.href='orders/product_detail.php?id=14'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 003.jpg" alt="Striped T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Greatness Is Not For Everyone </h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <span>(46)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                     <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=14'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=27'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 016.jpg" alt="Slogan T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Super Sonic</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(35)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                     <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=27'">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Scroller Layout -->
        <div class="products-scroller">
            <div class="scroller-inner">
                <!-- Product cards will be duplicated here for infinite scroll -->
                <!-- Product 1 -->
                <div class="product-card"  onclick="window.location.href='orders/product_detail.php?id=23'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 012.jpg" alt="Graphic T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Ammmmm hmmmm....</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star-half-alt star"></i>
                        <span>(42)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                        <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=23'">
                        Buy Now
                    </button>

                    </button>
                </div>
            </div>
            <!-- Product 2 -->
            <div class="product-card">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 014.jpg" alt="Plain T-Shirt">
                   
                </div>
                <div class="product-info" onclick="window.location.href='orders/product_detail.php?id=25'">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Yummy In The Tummy</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(28)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=25'">
                        Buy Now
                    </button>
                </div>
            </div>    
               <!-- Product 3 -->
            <div class="product-card"  onclick="window.location.href='orders/product_detail.php?id=14'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 003.jpg" alt="Striped T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Greatness Is Not For Everyone </h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <span>(56)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                     <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=14'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=27'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 016.jpg" alt="Slogan T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Super Sonic White Edition</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(35)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                     <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=27'">
                        Buy Now
                    </button>
                </div>
            </div>  
                <!-- Duplicate products for infinite effect -->
                <!-- Product 1 (Duplicate) -->
                 <div class="product-card"  onclick="window.location.href='orders/product_detail.php?id=23'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 012.jpg" alt="Graphic T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Ammmmm hmmmm....</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star-half-alt star"></i>
                        <span>(42)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                        <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=23'">
                        Buy Now
                    </button>

                    </button>
                </div>
            </div>
            <!-- Product 2 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=25'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 014.jpg" alt="Plain T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Yummy In The Tummy</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(28)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=25'">
                        Buy Now
                    </button>
                </div>
            </div> 
                <!-- Product 3 (Duplicate) -->
                   <!-- Product 3 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=14'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 003.jpg" alt="Striped T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Greatness Is Not For Everyone </h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <span>(56)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                     <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=14'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=27'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 016.jpg" alt="Slogan T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Super Sonic White Edition</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(35)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                     <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=27'">
                        Buy Now
                    </button>
                </div>
            </div>  
                </div>
            </div>
        </div>
    </section>
    <body class="bg-white">
  <!-- Desktop Layout -->
  <div class="desktop-container max-w-7xl mx-auto px-8 py-8">
    <h2 class="font-['Montserrat'] font-extrabold text-black text-center text-3xl tracking-[0.15em] mb-8 uppercase">TRENDING CATEGORIES</h2>
    <div class="grid grid-cols-4 gap-6">
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300 row-span-2">
        <img class="w-full h-full object-cover block" src="images/model.jpeg" alt="Man in beige polo shirt" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">F1 Racing</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300">
        <img class="w-full h-full object-cover block" src="images/model 11.jpeg" alt="Man in white full sleeve shirt" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">FULL SLEEVE<br/>SHIRTS</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300">
        <img class="w-full h-full object-cover block" src="images/model 12.jpeg" alt="Man in yellow oversized t-shirt" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">OVERSIZED<br/>T-SHIRTS</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300 row-span-2">
        <img class="w-full h-full object-cover block" src="images/model(2).jpeg" alt="Man in blue half sleeve shirt" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">HALF SLEEVE<br/>T-SHIRTS</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300">
        <img class="w-full h-full object-cover block" src="images/model 13.jpeg" alt="Person wearing blue denim pants" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat']">CASUAL</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300">
        <img class="w-full h-full object-cover block" src="images/model 14.jpeg" alt="Casual shirts" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">ANIME<br/>BASED</div>
      </div>
    </div>
  </div>

  <!-- Mobile Layout -->
  <div class="mobile-container max-w-md mx-auto px-4 py-4">
    <h2 class="font-['Montserrat'] font-extrabold text-black text-center text-xl tracking-[0.15em] mb-4 uppercase">TRENDING CATEGORIES</h2>
    <div class="grid grid-cols-2 gap-4">
      <div class="relative rounded-3xl overflow-hidden row-span-2">
        <img class="w-full h-full object-cover block" src="images/model.jpeg" alt="Man in beige polo shirt" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">F1 Racing</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden">
        <img class="w-full h-full object-cover block" src="images/model 12.jpeg" alt="Man in white full sleeve shirt" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">OVERSIZED<br/>T-SHIRTS</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden">
        <img class="w-full h-full object-cover block" src="images/model 13.jpeg" alt="Man in yellow oversized t-shirt" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">CASUAL<br/>T-SHIRTS</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden">
        <img class="w-full h-full object-cover block" src="images/model 11.jpeg" alt="Man in blue half sleeve shirt" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">HALF SLEEVE<br/>SHIRTS</div>
      </div>
      <div class="relative rounded-3xl overflow-hidden">
        <img class="w-full h-full object-cover block" src="images/model 14.jpeg" alt="Person wearing blue denim pants" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">Anime </div>
      </div>
    </div>
  </div>
 <section class="new-arrivals">
        <div class="section-header">
            <h2 class="section-title">Anime Based</h2>
            <a href="orders/anime.php" class="view-all">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Desktop Grid Layout -->
        <div class="products-grid">
            <!-- Product cards will be duplicated here for desktop -->
            <!-- Product 1 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=37'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 028 Black.jpg" alt="Graphic T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Gojo Satoru (black)</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star-half-alt star"></i>
                        <span>(42)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹659.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=37'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=35'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 026 White.jpg" alt="Plain T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Zoro Manga Panel</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(28)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹499.00</span>
                        <span class="old-price">₹659.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=35'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=33'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 025.jpg" alt="Striped T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Roronoa Zoro</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <span>(56)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹499.00</span>
                        <span class="old-price">₹689.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=33'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=36'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 027.jpg" alt="Slogan T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Jujutsut Kaisen Gojo</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(35)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=36'">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Scroller Layout -->
        <div class="products-scroller">
            <div class="scroller-inner">
                <!-- Product cards will be duplicated here for infinite scroll -->
                <div class="product-card"  onclick="window.location.href='orders/product_detail.php?id=37'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 028 Black.jpg" alt="Graphic T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Gojo Satoru (black)</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star-half-alt star"></i>
                        <span>(42)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹659.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=37'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="product-card"  onclick="window.location.href='orders/product_detail.php?id=35'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 026 White.jpg" alt="Plain T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Zoro Manga Panel</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(28)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹469.00</span>
                        <span class="old-price">₹699.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=35'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=33'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 025.jpg" alt="Striped T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Roronoa Zoro</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <span>(56)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹499.00</span>
                        <span class="old-price">₹689.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=33'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=36'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 027.jpg" alt="Slogan T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Jujutsut Kaisen Gojo</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(35)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=36'">
                        Buy Now
                    </button>
                </div>
            </div>
                 <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=37'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 028 Black.jpg" alt="Graphic T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Gojo Satoru (black)</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star-half-alt star"></i>
                        <span>(42)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹659.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=37'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=35'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 026 White.jpg" alt="Plain T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Zoro Manga Panel</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(28)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹469.00</span>
                        <span class="old-price">₹699.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=35'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=33'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 025.jpg" alt="Striped T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Roronoa Zoro</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <span>(56)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹499.00</span>
                        <span class="old-price">₹689.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=33'">
                        Buy Now
                    </button>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="product-card" onclick="window.location.href='orders/product_detail.php?id=36'">
                <span class="product-badge">New</span>
                <div class="product-image">
                    <img src="images/jpg 027.jpg" alt="Slogan T-Shirt">
                   
                </div>
                <div class="product-info">
                    <span class="product-category">T-Shirts</span>
                    <h3 class="product-title">Jujutsut Kaisen Gojo</h3>
                    <div class="rating">
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="fas fa-star star"></i>
                        <i class="far fa-star star"></i>
                        <span>(35)</span>
                    </div>
                    <div class="product-price">
                        <span class="current-price">₹449.00</span>
                        <span class="old-price">₹649.00</span>
                    </div>
                    <button  class="to-cart" onclick="window.location.href='orders/product_detail.php?id=36'">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
    </section>
    
     <!-- Hero Section -->
  <div class="w-full bg-[#d83f40] py-6 md:py-8 flex justify-center items-center">
    <h1
      class="text-white text-center text-2xl md:text-3xl leading-tight font-semibold max-w-[320px] sm:max-w-[380px] md:max-w-3xl"
    >
      Made In India, For The World
    </h1>
  </div>
  
  <!-- Customers Section -->
  <div class="bg-white py-4 md:py-6 flex justify-center">
    <h2
      class="text-[#222222] text-xl md:text-2xl font-semibold max-w-[620px] sm:max-w-[680px] md:max-w-3xl text-center"
    >
      Over Thousand Of Happy Customers
    </h2>
  </div>
  
  <!-- Accordion Container -->
  <div class="bg-gray-00 px-4 py-6 max-w-[100%] md:max-w-3xl mx-auto md:px-8 md:py-8">
    <!-- Contact Accordion -->
    <div class="accordion-item border-b border-gray-300 pb-3 mb-3">
      <button
        class="w-full flex justify-between items-center text-lg md:text-xl font-semibold text-[#222222] cursor-pointer select-none hover:text-[#d83f40] transition-colors"
        aria-expanded="false"
        aria-controls="panel1"
        id="accordion1"
        type="button"
      >
        <span>Let's get in touch</span>
        <i class="fas fa-chevron-down transition-transform duration-200 text-sm"></i>
      </button>
      <div
        id="panel1"
        class="accordion-content overflow-hidden max-h-0 opacity-0 mt-2 text-sm md:text-base font-normal text-gray-600"
        aria-labelledby="accordion1"
      >
        <p class="mb-1"><i class="fas fa-envelope mr-2 text-[#d83f40] text-xs"></i> Email: pyaara001@gmail.com</p>
        <p><i class="fas fa-headset mr-2 text-[#d83f40] text-xs"></i> Support: pyaara001@gmail.com</p>
        <p><i class="fas fa-phone mr-2 text-[#d83f40] text-xs"></i> <a href="tel:+917839460427">Contact: +91-7839460427</a></p>

        <p><i></i></p>
      </div>
    </div>

    <!-- Address Accordion -->
    <div class="accordion-item border-b border-gray-300 pb-3 mb-3">
      <button
        class="w-full flex justify-between items-center text-base md:text-lg font-bold text-[#222222] cursor-pointer select-none hover:text-[#d83f40] transition-colors"
        aria-expanded="false"
        aria-controls="panel2"
        id="accordion2"
        type="button"
      >
        <span>ADDRESS</span>
        <i class="fas fa-chevron-down transition-transform duration-200 text-sm"></i>
      </button>
      <div
        id="panel2"
        class="accordion-content overflow-hidden max-h-0 opacity-0 mt-2 text-sm md:text-base font-normal text-gray-600"
        aria-labelledby="accordion2"
      >
        <p class="mb-1"><i class="fas fa-map-marker-alt mr-2 text-[#d83f40] text-xs"></i> Suraksha Enclave, Udyan-2, Eldeco, Lucknow-226029</p>
        <p class="mb-1 pl-6"></p>
      </div>
    </div>

    <!-- Company Accordion -->
    <div class="accordion-item border-b border-gray-300 pb-3 mb-3">
      <button
        class="w-full flex justify-between items-center text-base md:text-lg font-bold text-[#222222] cursor-pointer select-none hover:text-[#d83f40] transition-colors"
        aria-expanded="false"
        aria-controls="panel3"
        id="accordion3"
        type="button"
      >
        <span>COMPANY</span>
        <i class="fas fa-chevron-down transition-transform duration-200 text-sm"></i>
      </button>
      <div
        id="panel3"
        class="accordion-content overflow-hidden max-h-0 opacity-0 mt-2 text-sm md:text-base font-normal text-gray-600"
        aria-labelledby="accordion3"
      >
        <p class="mb-1 hover:text-[#d83f40] transition-colors cursor-pointer"><i class="fas fa-info-circle mr-2 text-[#d83f40] text-xs"></i><a href="policies/aboutus.php"> About Us</a>  </p>
      </div>
    </div>

    <!-- More Info Accordion -->
    <div class="accordion-item pb-3 mb-3">
      <button
        class="w-full flex justify-between items-center text-base md:text-lg font-bold text-[#222222] cursor-pointer select-none hover:text-[#d83f40] transition-colors"
        aria-expanded="false"
        aria-controls="panel4"
        id="accordion4"
        type="button"
      >
        <span>MORE INFO</span>
        <i class="fas fa-chevron-down transition-transform duration-200 text-sm"></i>
      </button>
      <div
        id="panel4"
        class="accordion-content overflow-hidden max-h-0 opacity-0 mt-2 space-y-1 text-sm md:text-base font-normal text-gray-600"
        aria-labelledby="accordion4"
      >
        <p class="hover:text-[#d83f40] transition-colors cursor-pointer"><a href="policies/privacy_policy.php"><i class="fas fa-shield-alt mr-2 text-[#d83f40] text-xs"></i> Privacy Policy</p></a>
        <p class="hover:text-[#d83f40] transition-colors cursor-pointer"><a href="policies/term_&_condition.php"><i class="fas fa-file-contract mr-2 text-[#d83f40] text-xs"></i> Terms &amp; Condition</p></a>
        <p class="hover:text-[#d83f40] transition-colors cursor-pointer"><a href="policies/shipping_policy.php"><i class="fas fa-truck mr-2 text-[#d83f40] text-xs"></i> Shipping Policy</p></a>
        <p class="hover:text-[#d83f40] transition-colors cursor-pointer"><a href="policies/return&refund_policy.php"><i class="fas fa-truck mr-2 text-[#d83f40] text-xs"></i> Return & Refund Policy</p></a>
        <p class="hover:text-[#d83f40] transition-colors cursor-pointer"><a href="policies/contactus.php"><i class="fas fa-shield-alt mr-2 text-[#d83f40] text-xs"></i> Contact Us</p></a>
      </div>
    </div><!-- Scroll to Top Button -->
  <button
    aria-label="Scroll to top"
    class="fixed bottom-4 right-4 w-10 h-10 rounded-full bg-[#d83f40] flex justify-center items-center text-white text-lg shadow-lg hover:bg-[#c13738] transition-colors"
    id="scrollToTop"
  >
    <i class="fas fa-arrow-up"></i>
  </button>
  

    <!-- Copyright -->
    <p class="mt-6 text-sm md:text-base font-normal text-[#222222] text-center md:text-left">
      Copyright © 2025 Pyaara. All rights reserved. <br>Design and Created by CodeSleek</p>
     <br><br>
  </div>
  
  <script>
       // Check session via cookie
    fetch('session_check.php')
      .then(res => res.json())
      .then(data => {
        if (data.loggedIn) {
          document.getElementById('signupLink').style.display = 'none';
          document.getElementById('loginLink').style.display = 'none';
          document.getElementById('profileLink').style.display = 'block';
          document.getElementById('logoutLink').style.display = 'block';
        }
      });
  </script>
  <script src="script.js"></script>
</body>
</html>