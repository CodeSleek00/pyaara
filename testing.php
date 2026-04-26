Here is an HTML document that creates an anime product showcase page with a Tailwind CSS framework and a dark blue to white gradient background.
```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Anime Merch | Otaku Collection</title>
  <!-- Tailwind CSS v3 + Google Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- custom tailwind overrides & smooth behaviour -->
  <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Noto+Sans+JP:wght@300;400;500;700;800&display=swap" rel="stylesheet">
  <style>
    /* custom classes to extend tailwind + gradient animation and card effects */
    body {
      font-family: 'Noto Sans JP', sans-serif;
    }
    .anime-gradient-bg {
      /* dark blue to white at bottom — rich anime vibe */
      background: linear-gradient(145deg, #03001e 0%, #0a0f2a 40%, #eef5ff 100%);
      background-attachment: fixed;
    }
    /* bangers font for hero title */
    .font-bangers {
      font-family: 'Bangers', cursive;
    }
    /* floating orb animation for extra life */
    @keyframes softGlow {
      0% { opacity: 0.25; transform: scale(1);}
      100% { opacity: 0.6; transform: scale(1.2);}
    }
    .animate-glow-orb {
      animation: softGlow 7s infinite alternate ease-in-out;
    }
    /* card hover smooth 3d uplift */
    .product-card {
      transition: transform 0.25s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
      transform: translateY(-6px) scale(1.02);
      box-shadow: 0 25px 35px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255,255,245,0.2);
    }
    /* image placeholder graceful fallback */
    .img-placeholder {
      background: linear-gradient(135deg, #f5e6ff, #e0c8ff);
    }
    /* custom scroll reveal transition for products */
    .product-grid-item {
      transition: all 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.2);
    }
    /* japanese vertical text styling */
    .vertical-jp {
      writing-mode: vertical-rl;
      text-orientation: mixed;
      letter-spacing: 6px;
    }
    /* active state for scroll-triggered animations */
    .wrapper-scroll.active .hero-title-scale {
      transform: scale(0.65) translateY(-30px);
      opacity: 0;
      pointer-events: none;
    }
    .wrapper-scroll.active .jp-vertical-text {
      opacity: 0;
      transform: translateX(12px);
      transition: 0.4s;
    }
    .wrapper-scroll.active .products-container {
      opacity: 1;
      transform: scale(1);
      backdrop-filter: blur(0px);
    }
    .wrapper-scroll.active .product-grid-item {
      transform: translateY(0);
      opacity: 1;
    }
    /* product grid initial hidden state */
    .products-container {
      transition: 0.7s cubic-bezier(0.2, 0.9, 0.4, 1.1);
      opacity: 0;
      transform: scale(1.08);
    }
    .product-grid-item {
      transform: translateY(45px);
      opacity: 0;
      transition: 0.45s ease-out;
    }
    /* staggered delays */
    .grid > .product-grid-item:nth-child(1) { transition-delay: 0.05s; }
    .grid > .product-grid-item:nth-child(2) { transition-delay: 0.11s; }
    .grid > .product-grid-item:nth-child(3) { transition-delay: 0.17s; }
    .grid > .product-grid-item:nth-child(4) { transition-delay: 0.23s; }
    .grid > .product-grid-item:nth-child(5) { transition-delay: 0.29s; }
    .grid > .product-grid-item:nth-child(6) { transition-delay: 0.35s; }
    .grid > .product-grid-item:nth-child(7) { transition-delay: 0.41s; }
    .grid > .product-grid-item:nth-child(8) { transition-delay: 0.47s; }
    /* custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #0b0722;
    }
    ::-webkit-scrollbar-thumb {
      background: #ff6a88;
      border-radius: 20px;
    }
  </style>
</head>
<body class="m-0 p-0 overflow-x-hidden antialiased anime-gradient-bg">

  <!-- main scroll wrapper: triggers class .active on scroll -->
  <div class="wrapper-scroll relative" id="scrollWrapper">
    
    <!-- STICKY HERO SECTION with floating elements -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden sticky top-0 z-10 shadow-2xl"
             style="background: radial-gradient(circle at 30% 20%, #07001a, #010008);">
      
      <!-- animated background orbs / particles (modern anime style) -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-96 h-96 rounded-full bg-pink-500/10 blur-3xl -top-32 -left-32 animate-glow-orb"></div>
        <div class="absolute w-[600px] h-[600px] rounded-full bg-purple-600/10 blur-3xl bottom-0 right-0 animate-glow-orb" style="animation-delay: -2s;"></div>
        <div class="absolute w-80 h-80 rounded-full bg-blue-400/10 blur-2xl top-1/3 left-1/4 animate-pulse"></div>
      </div>

      <!-- main ANIME title with bangers font -->
      <h1 class="hero-title-scale font-bangers text-8xl md:text-[110px] lg:text-[140px] tracking-[0.2em] transition-all duration-700 ease-out
                 bg-gradient-to-r from-rose-200 via-pink-300 to-amber-200 bg-clip-text text-transparent drop-shadow-2xl"
          style="text-shadow: 0 0 18px rgba(255,80,120,0.6);">
        ANIME
      </h1>

      <!-- Japanese vertical elegant text (right side) -->
      <div class="jp-vertical-text absolute right-5 md:right-12 top-1/2 -translate-y-1/2 vertical-jp text-2xl md:text-4xl font-medium
                  text-white/70 backdrop-blur-sm px-2 py-4 rounded-full transition-all duration-500"
           style="font-family: 'Noto Sans JP', sans-serif; letter-spacing: 8px;">
        アニメ・魂
      </div>

      <!-- subtle bottom fade gradient to connect hero with content (design detail) -->
      <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-[#eef5ff] to-transparent opacity-40 pointer-events-none"></div>
    </section>

    <!-- PRODUCTS SECTION: appears after scroll -->
    <div class="products-container relative z-20 pb-28 pt-12 md:pt-20 px-5 md:px-10">
      <div class="max-w-7xl mx-auto">
        <!-- decorative line + category hint -->
        <div class="flex justify-center mb-8 md:mb-12">
          <div class="inline-flex items-center gap-3 bg-white/20 backdrop-blur-sm rounded-full px-5 py-2 border border-white/30 shadow-md">
            <span class="text-sm font-medium text-indigo-900 tracking-wide">⚡ HOT RELEASES</span>
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
            <span class="text-sm text-indigo-800/80">限定コレクション</span>
          </div>
        </div>

        <!-- dynamic product grid from PHP (simulated data if DB missing but we keep dynamic integration) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7 md:gap-8">
          <?php
          /* ========== DATABASE INTEGRATION (same logic as original but fully tailwind ready) ========== */
          $conn = null;
          if (file_exists(__DIR__ . '/orders/db_connect.php')) {
            require_once __DIR__ . '/orders/db_connect.php';
          } elseif (file_exists(__DIR__ . '/temp_db.php')) {
            require_once __DIR__ . '/temp_db.php';
          }

          $products = [];
          $pageA = 'anime.php';
          $pageB = 'orders/anime.php';

          if (isset($conn) && $conn instanceof mysqli) {
            $stmt = $conn->prepare("SELECT id, image, name, original_price, discount_price FROM products WHERE page IN (?, ?) ORDER BY id DESC LIMIT 8");
            if ($stmt) {
              $stmt->bind_param('ss', $pageA, $pageB);
              if ($stmt->execute()) {
                $res = $stmt->get_result();
                if ($res && $res->num_rows > 0) {
                  while ($row = $res->fetch_assoc()) {
                    $products[] = $row;
                  }
                } else {
                  // fallback for env without mysqlnd (bind_result)
                  $id = null; $image = null; $name = null; $original = null; $discount = null;
                  if ($stmt->bind_result($id, $image, $name, $original, $discount)) {
                    while ($stmt->fetch()) {
                      $products[] = [
                        'id' => $id,
                        'image' => $image,
                        'name' => $name,
                        'original_price' => $original,
                        'discount_price' => $discount,
                      ];
                    }
                  }
                }
              }
              $stmt->close();
            }
          }

          function price_final_tailwind($row) {
            if (!is_array($row)) return 0;
            $o = (float)($row['original_price'] ?? 0);
            $d = (float)($row['discount_price'] ?? 0);
            return ($d > 0 && $d < $o) ? $d : $o;
          }

          // if no products exist show fallback collection (beautiful empty state)
          if (empty($products)): ?>
            <div class="col-span-full flex justify-center items-center py-16">
              <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-10 text-center max-w-2xl border border-indigo-200">
                <div class="text-7xl mb-4">⚡🎴</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">No Anime Products Found</h3>
                <p class="text-gray-600 mb-4">Add products with page = <code class="bg-gray-200 px-2 py-1 rounded-md text-rose-600">anime.php</code> or <code class="bg-gray-200 px-2 py-1 rounded-md">orders/anime.php</code></p>
                <p class="text-sm text-indigo-700">✨ bring your otaku collection to life ✨</p>
              </div>
            </div>
          <?php else: 
            foreach ($products as $row):
              if (!is_array($row)) continue;
              $id = (int)($row['id'] ?? 0);
              $name = htmlspecialchars((string)($row['name'] ?? 'Mysterious Item'), ENT_QUOTES, 'UTF-8');
              $imageRaw = htmlspecialchars((string)($row['image'] ?? ''), ENT_QUOTES, 'UTF-8');
              $finalPrice = price_final_tailwind($row);
              $formattedPrice = number_format($finalPrice);
              $imagePath = '';
              if (!empty($imageRaw) && file_exists(__DIR__ . '/orders/uploads/' . $imageRaw)) {
                $imagePath = 'orders/uploads/' . $imageRaw;
              } elseif (!empty($imageRaw) && file_exists(__DIR__ . '/uploads/' . $imageRaw)) {
                $imagePath = 'uploads/' . $imageRaw;
              }
          ?>
          <div class="product-grid-item bg-white/95 rounded-2xl overflow-hidden shadow-xl transition-all duration-300 flex flex-col h-full backdrop-blur-sm border border-white/40">
            <!-- product image area -->
            <div class="relative pt-4 px-4">
              <?php if (!empty($imagePath)): ?>
                <img src="<?= $imagePath ?>" alt="<?= $name ?>" loading="lazy" class="w-full aspect-square object-cover rounded-2xl shadow-md hover:scale-[1.02] transition-transform duration-300">
              <?php else: ?>
                <div class="w-full aspect-square rounded-2xl img-placeholder flex items-center justify-center text-6xl shadow-inner">
                  🎴
                </div>
              <?php endif; ?>
              <!-- discount badge if discount applies -->
              <?php if (is_array($row) && isset($row['discount_price']) && (float)($row['discount_price'] ?? 0) > 0 && (float)($row['discount_price']) < (float)($row['original_price'] ?? 0)): ?>
                <span class="absolute top-6 left-6 bg-rose-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">SALE</span>
              <?php endif; ?>
            </div>
            
            <div class="p-5 flex flex-col flex-grow">
              <a href="orders/product_detail.php?id=<?= $id ?>" class="font-extrabold text-xl text-gray-800 hover:text-rose-600 transition-colors line-clamp-2 mb-1">
                <?= $name ?>
              </a>
              <div class="mt-2 flex items-baseline gap-2 flex-wrap">
                <span class="text-2xl font-black text-rose-600">₹<?= $formattedPrice ?></span>
                <?php if (is_array($row) && isset($row['original_price']) && (float)($row['discount_price'] ?? 0) > 0 && (float)$row['discount_price'] < (float)$row['original_price']): 
                  $orig = number_format((float)$row['original_price']);
                ?>
                  <span class="text-sm text-gray-400 line-through">₹<?= $orig ?></span>
                  <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">save</span>
                <?php endif; ?>
              </div>
              <div class="mt-5 flex gap-2">
                <a href="orders/product_detail.php?id=<?= $id ?>" class="flex-1 text-center bg-gray-900 hover:bg-rose-600 text-white font-semibold py-2.5 rounded-xl transition-all duration-200 shadow-md text-sm tracking-wide">
                  🔍 View details
                </a>
              </div>
            </div>
          </div>
          <?php endforeach; endif; ?>
        </div>

        <!-- call to action explore more (optional) -->
        <div class="flex justify-center mt-16">
          <a href="orders/anime.php" class="group px-8 py-3 rounded-full bg-gradient-to-r from-indigo-900 to-purple-900 text-white font-bold shadow-lg hover:shadow-xl transition-all flex items-center gap-2 hover:scale-105">
            <span>Explore Full Collection</span>
            <span class="group-hover:translate-x-1 transition">➡️</span>
          </a>
        </div>
      </div>
    </div>

    <!-- footer indicator (subtle) -->
    <footer class="relative z-10 py-8 text-center text-indigo-900/70 text-sm border-t border-white/30 backdrop-blur-sm mt-8">
      <p>✨ Otaku Merch — アニメ愛好家のために ✨</p>
    </footer>
  </div>

  <!-- scroll trigger js (enhanced) with intersection observer fallback -->
  <script>
    (function() {
      const wrapper = document.getElementById('scrollWrapper');
      if (!wrapper) return;
      
      let ticking = false;
      const heroSection = document.querySelector('.hero-title-scale')?.closest('section');
      
      // trigger at ~25% of viewport height for gentle reveal
      const activationOffset = window.innerHeight * 0.28;
      
      function updateActiveState() {
        const scrollY = window.scrollY;
        if (scrollY > activationOffset) {
          if (!wrapper.classList.contains('active')) {
            wrapper.classList.add('active');
          }
        } else {
          if (wrapper.classList.contains('active')) {
            wrapper.classList.remove('active');
          }
        }
        ticking = false;
      }
      
      window.addEventListener('scroll', () => {
        if (!ticking) {
          requestAnimationFrame(updateActiveState);
          ticking = true;
        }
      });
      
      // also trigger on load in case page reloads with scroll
      updateActiveState();
      
      // handle resize to recalc offset if needed
      let resizeTimer;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          // recalc offset after resize (new vh)
          const newOffset = window.innerHeight * 0.28;
          if (window.scrollY > newOffset) {
            wrapper.classList.add('active');
          } else {
            wrapper.classList.remove('active');
          }
        }, 100);
      });
    })();
  </script>
  <!-- note: tailwind CDN includes all utilities, no extra config needed -->
  <!-- Background gradient is applied via anime-gradient-bg class on body -->
  <!-- The whole design uses dark blue to white bottom: deep navy to crisp white at footer -->
</body>
</html>
```