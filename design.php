<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

// FETCH 10 ANIME PRODUCTS
$animeProducts = $conn->query("
  SELECT id, image, name, original_price, discount_price 
  FROM products 
  WHERE page='anime.php'
  ORDER BY RAND()
  LIMIT 10
");
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap');

/* ANIME SECTION - LIGHT THEME */
.anime-section {
  padding: 70px 20px;
  background: linear-gradient(135deg, #fff9f0 0%, #fff5e6 100%);
  font-family: 'Poppins', sans-serif;
  position: relative;
  overflow: hidden;
}

/* Anime Decorative Elements */
.anime-section::before {
  content: '';
  position: absolute;
  top: -50px;
  right: -50px;
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(255, 230, 0, 0.1) 0%, transparent 70%);
  border-radius: 50%;
  z-index: 0;
}

.anime-section::after {
  content: '';
  position: absolute;
  bottom: -50px;
  left: -50px;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(232, 0, 61, 0.08) 0%, transparent 70%);
  border-radius: 50%;
  z-index: 0;
}

/* Speed Lines Effect */
.speed-lines-anime {
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(
    90deg,
    transparent,
    transparent 20px,
    rgba(255, 230, 0, 0.03) 20px,
    rgba(232, 0, 61, 0.03) 25px
  );
  pointer-events: none;
  z-index: 1;
}

/* Floating Anime Particles */
.anime-particle {
  position: absolute;
  width: 6px;
  height: 6px;
  background: #ffd700;
  border-radius: 50%;
  filter: blur(1px);
  opacity: 0.5;
  animation: floatParticle 15s infinite linear;
  z-index: 1;
}

@keyframes floatParticle {
  0% { transform: translateY(0) translateX(0) rotate(0deg); opacity: 0; }
  10% { opacity: 0.5; }
  90% { opacity: 0.5; }
  100% { transform: translateY(-100vh) translateX(100px) rotate(360deg); opacity: 0; }
}

/* SECTION TITLE - ANIME STYLE */
.section-title-wrapper {
  position: relative;
  z-index: 5;
  text-align: center;
  margin-bottom: 50px;
}

.section-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 4px;
  text-transform: uppercase;
  color: #e8003d;
  margin-bottom: 10px;
  display: inline-block;
  padding: 5px 15px;
  background: rgba(232, 0, 61, 0.1);
  border-radius: 30px;
}

.section-title {
  font-size: 42px;
  font-weight: 800;
  color: #1a1a2e;
  margin: 0 0 10px;
  font-family: 'Montserrat', sans-serif;
  text-transform: uppercase;
  position: relative;
  display: inline-block;
}

.section-title span {
  color: #e8003d;
  position: relative;
  display: inline-block;
}

.section-title span::after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, #ffd700, #e8003d);
  border-radius: 3px;
}

.section-title-glow {
  font-size: 18px;
  color: #666;
  font-weight: 300;
  letter-spacing: 2px;
}

/* DESKTOP GRID */
.anime-container {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 30px;
  max-width: 1400px;
  margin: 0 auto;
  position: relative;
  z-index: 5;
}

/* MOBILE CAROUSEL - SINGLE ROW */
.mobile-carousel-container {
  display: none;
  position: relative;
  z-index: 5;
  width: 100%;
  overflow: hidden;
  padding: 10px 0;
}

.carousel-wrapper {
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  padding: 10px 0 20px;
  cursor: grab;
  scroll-behavior: smooth;
  width: 100%;
}

.carousel-wrapper::-webkit-scrollbar {
  display: none;
}

.carousel-wrapper:active {
  cursor: grabbing;
}

.carousel-track {
  display: flex;
  gap: 20px;
  padding: 0 20px;
  width: max-content;
  flex-wrap: nowrap;
}

.carousel-track .anime-card {
  flex: 0 0 280px;
  scroll-snap-align: start;
  margin: 5px 0;
  scroll-snap-stop: always;
}

/* Auto-scroll indicator */
.auto-scroll-indicator {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin: 15px 0 10px;
}

.scroll-bar {
  width: 30px;
  height: 4px;
  background: rgba(232, 0, 61, 0.2);
  border-radius: 4px;
  overflow: hidden;
  position: relative;
}

.scroll-bar.active .scroll-progress {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  background: linear-gradient(90deg, #ffd700, #e8003d);
  animation: scrollProgress 4s linear infinite;
}

@keyframes scrollProgress {
  0% { width: 0%; }
  100% { width: 100%; }
}

/* ANIME CARD - LIGHT THEME */
.anime-card {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  position: relative;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05),
              0 0 0 1px rgba(232, 0, 61, 0.1);
  border: 1px solid rgba(255, 215, 0, 0.2);
}

.anime-card:hover {
  transform: translateY(-15px) scale(1.02);
  box-shadow: 0 30px 45px rgba(232, 0, 61, 0.15),
              0 0 0 2px rgba(255, 215, 0, 0.3);
}

/* Card Badge - Anime Style */
.anime-card::before {
  content: '⚡';
  position: absolute;
  top: 10px;
  right: 10px;
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #ffd700, #e8003d);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 20px;
  z-index: 10;
  box-shadow: 0 5px 15px rgba(232, 0, 61, 0.4);
  opacity: 0;
  transform: scale(0.5);
  transition: all 0.3s ease;
}

.anime-card:hover::before {
  opacity: 1;
  transform: scale(1);
}

/* Card Corner Decoration */
.anime-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, rgba(255, 215, 0, 0.2) 0%, transparent 100%);
  clip-path: polygon(0 0, 100% 0, 0 100%);
  z-index: 1;
}

/* IMAGE CONTAINER */
.anime-card-img {
  position: relative;
  overflow: hidden;
  aspect-ratio: 1/1.2;
}

.anime-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

.anime-card:hover img {
  transform: scale(1.1) rotate(2deg);
}

/* Image Overlay - Anime Effect */
.img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    transparent 0%,
    rgba(255, 255, 255, 0.1) 50%,
    rgba(232, 0, 61, 0.2) 100%
  );
  opacity: 0;
  transition: opacity 0.3s;
  mix-blend-mode: overlay;
}

.anime-card:hover .img-overlay {
  opacity: 1;
}

/* CARD CONTENT */
.anime-card-content {
  padding: 20px 15px 25px;
  position: relative;
  background: white;
}

/* Product Title */
.anime-card h3 {
  font-size: 16px;
  font-weight: 700;
  color: #1a1a2e;
  margin: 0 0 12px;
  line-height: 1.4;
  min-height: 45px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  font-family: 'Montserrat', sans-serif;
}

/* Anime Category Tag */
.anime-tag {
  display: inline-block;
  padding: 4px 12px;
  background: rgba(255, 215, 0, 0.15);
  border-radius: 30px;
  font-size: 11px;
  font-weight: 600;
  color: #e8003d;
  margin-bottom: 10px;
  letter-spacing: 1px;
}

/* PRICE SECTION */
.price-container {
  display: flex;
  align-items: baseline;
  justify-content: center;
  gap: 8px;
  margin-bottom: 15px;
  flex-wrap: wrap;
}

.current-price {
  font-size: 24px;
  font-weight: 800;
  color: #e8003d;
  font-family: 'Montserrat', sans-serif;
  position: relative;
  display: inline-block;
}

.current-price::before {
  content: '¥';
  font-size: 16px;
  margin-right: 2px;
  color: #ffd700;
}

.original-price {
  font-size: 14px;
  color: #999;
  text-decoration: line-through;
  position: relative;
}

.discount-badge {
  background: linear-gradient(135deg, #ffd700, #e8003d);
  color: white;
  font-size: 12px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 30px;
  margin-left: 5px;
  display: inline-block;
  box-shadow: 0 3px 8px rgba(232, 0, 61, 0.2);
}

/* BUTTON */
.anime-card button {
  width: 100%;
  padding: 12px 20px;
  border: none;
  background: linear-gradient(135deg, #1a1a2e, #16213e);
  color: white;
  font-size: 14px;
  font-weight: 600;
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.3s;
  position: relative;
  overflow: hidden;
  letter-spacing: 1px;
  font-family: 'Montserrat', sans-serif;
  text-transform: uppercase;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.anime-card button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.anime-card button:hover {
  background: linear-gradient(135deg, #e8003d, #ffd700);
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(232, 0, 61, 0.3);
}

.anime-card button:hover::before {
  left: 100%;
}

.anime-card a {
  text-decoration: none;
  display: block;
}

/* RATING STARS - Anime Style */
.rating-stars {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 3px;
  margin: 8px 0;
  color: #ffd700;
  font-size: 14px;
}

.rating-stars span {
  color: #ddd;
  margin-left: 5px;
  font-size: 12px;
}

/* VIEW ALL BUTTON */
.view-all-btn {
  text-align: center;
  margin-top: 50px;
  position: relative;
  z-index: 5;
}

.view-all-btn a {
  display: inline-block;
  padding: 15px 40px;
  background: white;
  color: #e8003d;
  text-decoration: none;
  font-weight: 700;
  border-radius: 50px;
  font-size: 16px;
  letter-spacing: 2px;
  border: 2px solid #ffd700;
  transition: all 0.3s;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
}

.view-all-btn a:hover {
  background: linear-gradient(135deg, #ffd700, #e8003d);
  color: white;
  border-color: transparent;
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(232, 0, 61, 0.2);
}

/* RESPONSIVE BREAKPOINTS */
@media (max-width: 1200px) {
  .anime-container {
    grid-template-columns: repeat(4, 1fr);
  }
}

@media (max-width: 992px) {
  .anime-container {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .anime-section {
    padding: 50px 15px;
  }
  
  .section-title {
    font-size: 36px;
  }
  
  /* Hide desktop grid on mobile */
  .anime-container {
    display: none;
  }
  
  /* Show mobile carousel */
  .mobile-carousel-container {
    display: block;
  }
  
  .carousel-track .anime-card {
    flex: 0 0 260px;
  }
}

@media (max-width: 480px) {
  .section-title {
    font-size: 28px;
  }
  
  .section-subtitle {
    font-size: 12px;
  }
  
  .section-title-glow {
    font-size: 14px;
  }
  
  .carousel-track .anime-card {
    flex: 0 0 220px;
  }
  
  .anime-card h3 {
    font-size: 14px;
    min-height: 40px;
  }
  
  .current-price {
    font-size: 20px;
  }
  
  .view-all-btn a {
    padding: 12px 30px;
    font-size: 14px;
  }
}

/* Touch optimization */
@media (hover: none) and (pointer: coarse) {
  .carousel-wrapper {
    cursor: default;
  }
  
  .anime-card:hover {
    transform: none;
  }
  
  .anime-card:hover img {
    transform: none;
  }
  
  .anime-card:hover::before {
    opacity: 1;
    transform: scale(1);
  }
}

/* Animation */
@keyframes cardAppear {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.anime-card {
  animation: cardAppear 0.5s ease forwards;
  opacity: 0;
}

/* Custom scrollbar */
.anime-section::-webkit-scrollbar {
  width: 8px;
}

.anime-section::-webkit-scrollbar-track {
  background: #fff5e6;
}

.anime-section::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #ffd700, #e8003d);
  border-radius: 10px;
}

.anime-section::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #e8003d, #ffd700);
}
</style>

<!-- ANIME SECTION - LIGHT THEME -->
<section class="anime-section">
  <!-- Decorative Elements -->
  <div class="speed-lines-anime"></div>
  
  <!-- Floating Particles -->
  <?php for($i = 0; $i < 20; $i++): ?>
    <div class="anime-particle" style="
      left: <?php echo rand(0, 100); ?>%;
      top: <?php echo rand(0, 100); ?>%;
      animation-delay: <?php echo rand(0, 10); ?>s;
      background: <?php echo rand(0, 1) ? '#ffd700' : '#e8003d'; ?>;
    "></div>
  <?php endfor; ?>

  <!-- Section Header -->
  <div class="section-title-wrapper">
    <div class="section-subtitle">⚡ Limited Collection</div>
    <h2 class="section-title">
      ANIME <span>WORLD</span>
    </h2>
    <div class="section-title-glow">〜 人気キャラクター 〜</div>
  </div>

  <!-- DESKTOP GRID -->
  <div class="anime-container">
    <?php 
    $i = 0;
    $products = [];
    while($row = $animeProducts->fetch_assoc()): 
      $products[] = $row;
      $discount = $row['discount_price'] < $row['original_price'] ? 
                  round((($row['original_price'] - $row['discount_price']) / $row['original_price']) * 100) : 0;
      $i++;
    ?>
      
      <div class="anime-card" style="animation-delay: <?php echo $i * 0.1; ?>s;">
        <div class="anime-card-img">
          <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div class="img-overlay"></div>
        </div>

        <div class="anime-card-content">
          <span class="anime-tag">
            <?php 
              $tags = ['Shonen', 'Shojo', 'Seinen', 'Kodomo', 'Mecha', 'Isekai'];
              echo $tags[$i % count($tags)];
            ?>
          </span>

          <h3><?php echo htmlspecialchars($row['name']); ?></h3>

          <div class="rating-stars">
            ★★★★☆ <span>(<?php echo rand(100, 999); ?>)</span>
          </div>

          <div class="price-container">
            <span class="current-price"><?php echo number_format($row['discount_price']); ?></span>
            <?php if($discount > 0): ?>
              <span class="original-price"><?php echo number_format($row['original_price']); ?></span>
              <span class="discount-badge">-<?php echo $discount; ?>%</span>
            <?php endif; ?>
          </div>

          <a href="product_detail.php?id=<?php echo $row['id']; ?>">
            <button>Buy Now</button>
          </a>
        </div>
      </div>

    <?php endwhile; ?>
  </div>

  <!-- MOBILE CAROUSEL - SINGLE ROW AUTO SCROLL -->
  <div class="mobile-carousel-container">
    <div class="carousel-wrapper" id="animeCarousel">
      <div class="carousel-track">
        <?php 
        $i = 0;
        foreach($products as $row): 
          $discount = $row['discount_price'] < $row['original_price'] ? 
                      round((($row['original_price'] - $row['discount_price']) / $row['original_price']) * 100) : 0;
          $i++;
        ?>
          
          <div class="anime-card">
            <div class="anime-card-img">
              <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
              <div class="img-overlay"></div>
            </div>

            <div class="anime-card-content">
              <span class="anime-tag">
                <?php 
                  $tags = ['Shonen', 'Shojo', 'Seinen', 'Kodomo', 'Mecha', 'Isekai'];
                  echo $tags[$i % count($tags)];
                ?>
              </span>

              <h3><?php echo htmlspecialchars($row['name']); ?></h3>

              <div class="rating-stars">
                ★★★★☆ <span>(<?php echo rand(100, 999); ?>)</span>
              </div>

              <div class="price-container">
                <span class="current-price"><?php echo number_format($row['discount_price']); ?></span>
                <?php if($discount > 0): ?>
                  <span class="original-price"><?php echo number_format($row['original_price']); ?></span>
                  <span class="discount-badge">-<?php echo $discount; ?>%</span>
                <?php endif; ?>
              </div>

              <a href="product_detail.php?id=<?php echo $row['id']; ?>">
                <button>Buy Now</button>
              </a>
            </div>
          </div>

        <?php endforeach; ?>
      </div>
    </div>

    <!-- Auto-scroll indicator -->
    <div class="auto-scroll-indicator" id="scrollIndicator">
      <?php for($d = 0; $d < count($products); $d++): ?>
        <div class="scroll-bar <?php echo $d == 0 ? 'active' : ''; ?>" data-index="<?php echo $d; ?>">
          <div class="scroll-progress"></div>
        </div>
      <?php endfor; ?>
    </div>
  </div>

  <!-- View All Button -->
  <div class="view-all-btn">
    <a href="anime.php">View All Collection →</a>
  </div>
</section>

<script>
// Mobile Carousel - Single Row Auto Scroll
document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.getElementById('animeCarousel');
  if (!carousel) return;

  const track = carousel.querySelector('.carousel-track');
  const cards = track ? track.querySelectorAll('.anime-card') : [];
  const scrollBars = document.querySelectorAll('.scroll-bar');

  if (!track || !cards.length) return;

  let isDragging = false;
  let startX = 0;
  let scrollLeft = 0;
  let rafId = null;
  const autoSpeed = 0.6; // px per frame for smooth auto-scroll

  const getCardWidth = () => {
    if (!cards.length) return 0;
    const gap = 20; // gap from CSS
    return cards[0].offsetWidth + gap;
  };

  const updateIndexFromScroll = () => {
    if (!scrollBars.length) return;
    const cardWidth = getCardWidth();
    if (!cardWidth) return;
    const idx = Math.round(carousel.scrollLeft / cardWidth);
    scrollBars.forEach((bar, i) => {
      bar.classList.toggle('active', i === idx);
    });
  };

  const startAuto = () => {
    if (rafId) cancelAnimationFrame(rafId);
    const step = () => {
      if (window.innerWidth <= 768 && !isDragging && !document.hidden) {
        carousel.scrollLeft += autoSpeed;
        if (carousel.scrollLeft >= track.scrollWidth - carousel.clientWidth - 2) {
          carousel.scrollLeft = 0;
        }
        updateIndexFromScroll();
      }
      rafId = requestAnimationFrame(step);
    };
    rafId = requestAnimationFrame(step);
  };

  const stopAuto = () => {
    if (rafId) {
      cancelAnimationFrame(rafId);
      rafId = null;
    }
  };

  // Drag interactions
  carousel.addEventListener('mousedown', (e) => {
    isDragging = true;
    startX = e.pageX - carousel.offsetLeft;
    scrollLeft = carousel.scrollLeft;
    carousel.style.cursor = 'grabbing';
    stopAuto();
  });

  carousel.addEventListener('mouseleave', () => {
    if (!isDragging) return;
    isDragging = false;
    carousel.style.cursor = 'grab';
    updateIndexFromScroll();
    startAuto();
  });

  carousel.addEventListener('mouseup', () => {
    if (!isDragging) return;
    isDragging = false;
    carousel.style.cursor = 'grab';
    updateIndexFromScroll();
    startAuto();
  });

  carousel.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const x = e.pageX - carousel.offsetLeft;
    const walk = (x - startX) * 2;
    carousel.scrollLeft = scrollLeft - walk;
  });

  // Touch interactions
  carousel.addEventListener('touchstart', (e) => {
    isDragging = true;
    startX = e.touches[0].pageX - carousel.offsetLeft;
    scrollLeft = carousel.scrollLeft;
    stopAuto();
  });

  carousel.addEventListener('touchend', () => {
    if (!isDragging) return;
    isDragging = false;
    updateIndexFromScroll();
    startAuto();
  });

  carousel.addEventListener('touchmove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const x = e.touches[0].pageX - carousel.offsetLeft;
    const walk = (x - startX) * 2;
    carousel.scrollLeft = scrollLeft - walk;
  });

  carousel.addEventListener('scroll', () => {
    if (!isDragging) updateIndexFromScroll();
  });

  // Handle resize and visibility
  window.addEventListener('resize', () => {
    if (window.innerWidth <= 768) {
      startAuto();
    } else {
      stopAuto();
    }
  });

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      stopAuto();
    } else if (window.innerWidth <= 768) {
      startAuto();
    }
  });

  if (window.innerWidth <= 768) {
    startAuto();
  }
});
</script>
