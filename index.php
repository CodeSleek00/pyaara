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
          <a href="orders/search_order.php">Track Order </a>
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
            </div>
          </li>
          <li class="dropdown">
            <a href="#">Women's</a>
            <div class="dropdown-content">
               <a href="orders/cutie.php">Cutiesssss</a>
              <a href="orders/exclusive.php">Exclusive</a>
            </div>
          </li>
          <li><a href="orders/oversized.php">Oversized</a></li>
          <li><a href="orders/anime.php">Anime</a></li>
          <li><a href="orders/offer.php">Offer's</a></li>
          <li><a href="orders/order_history.php">My Orders</a></li>
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
            </div>
          </li>
          <li class="mobile-dropdown">
            <a href="#">Women's</a>
            <div class="mobile-dropdown-content">
              <a href="orders/cutie.php">Cutiesssss</a>
              <a href="orders/exclusive.php">Exclusive</a>
            </div>
          </li>
          <li><a href="orders/oversized.php">Oversized</a></li>
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
        <a href="orders/order_history.php" class="footer-menu-item">
            <i class="fa-solid fa-list"></i> 
            <span>My Orders</span>
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
    <img src="images/change.jpg" alt="">
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
        <a href="orders/offer.php"><img class="w-full h-full object-cover block" src="images/model.jpeg" alt="Man in beige polo shirt" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">F1 Racing</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300">
        <a href="orders/exclusive.php"><img class="w-full h-full object-cover block" src="images/model 11.jpeg" alt="Man in white full sleeve shirt" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">Exclusive</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300">
        <a href="orders/oversized.php"><img class="w-full h-full object-cover block" src="images/model 12.jpeg" alt="Man in yellow oversized t-shirt" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">OVERSIZED<br/>T-SHIRTS</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300 row-span-2">
        <a href="orders/cutie.php"><img class="w-full h-full object-cover block" src="images/model(2).jpeg" alt="Man in blue half sleeve shirt" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">HALF SLEEVE<br/>T-SHIRTS</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300">
        <a href="orders/offer.php"><img class="w-full h-full object-cover block" src="images/model 13.jpeg" alt="Person wearing blue denim pants" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat']">CASUAL</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden hover:scale-103 transition-transform duration-300">
       <a href="orders/anime.php"> <img class="w-full h-full object-cover block" src="images/model 14.jpeg" alt="Casual shirts" />
        <div class="absolute bottom-6 right-6 text-white font-bold text-2xl text-shadow font-['Montserrat'] text-right leading-tight">ANIME<br/>BASED</div>
      </div></a>
    </div>
  </div>

  <!-- Mobile Layout -->
  <div class="mobile-container max-w-md mx-auto px-4 py-4">
    <h2 class="font-['Montserrat'] font-extrabold text-black text-center text-xl tracking-[0.15em] mb-4 uppercase">TRENDING CATEGORIES</h2>
    <div class="grid grid-cols-2 gap-4">
      <div class="relative rounded-3xl overflow-hidden row-span-2">
         <a href="orders/offer.php"><img class="w-full h-full object-cover block" src="images/model.jpeg" alt="Man in beige polo shirt" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">OFFER</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden">
        <a href="orders/oversized.php"><img class="w-full h-full object-cover block" src="images/model 12.jpeg" alt="Man in white full sleeve shirt" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">OVERSIZED<br/>T-SHIRTS</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden">
        <a href="orders/offer.php"><img class="w-full h-full object-cover block" src="images/model 13.jpeg" alt="Man in yellow oversized t-shirt" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">CASUAL<br/>T-SHIRTS</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden">
        <a href="orders/exclusive.php"><img class="w-full h-full object-cover block" src="images/model 11.jpeg" alt="Man in blue half sleeve shirt" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">Exclusive</div>
      </div></a>
      <div class="relative rounded-3xl overflow-hidden">
       <a href="orders/anime.php"><img class="w-full h-full object-cover block" src="images/model 14.jpeg" alt="Person wearing blue denim pants" />
        <div class="absolute bottom-4 right-4 text-white font-bold text-lg text-shadow font-['Montserrat']">Anime </div>
      </div></a>
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