<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'temp_db.php';

// FETCH 10 ANIME PRODUCTS
$animeProducts = $conn->query("
  SELECT id, image, name, original_price, discount_price 
  FROM products 
  WHERE page='anime.php'
  ORDER BY RAND()
  LIMIT 12
");
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap');

/* ANIME SECTION - LIGHT THEME */
.anime-section {
  padding: 80px 20px;
  background: linear-gradient(135deg, #fff9f0 0%, #fff5e6 100%);
  font-family: 'Poppins', sans-serif;
  position: relative;
  overflow: hidden;
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.8s ease, transform 0.8s ease;
}

.anime-section.visible {
  opacity: 1;
  transform: translateY(0);
}

/* Decorative Elements */
.anime-glow {
  position: absolute;
  width: 800px;
  height: 800px;
  background: radial-gradient(circle, rgba(255, 215, 0, 0.08) 0%, transparent 70%);
  border-radius: 50%;
  top: -200px;
  right: -200px;
  animation: glowPulse 8s ease-in-out infinite;
  z-index: 0;
}

.anime-glow-2 {
  position: absolute;
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(232, 0, 61, 0.05) 0%, transparent 70%);
  border-radius: 50%;
  bottom: -100px;
  left: -100px;
  animation: glowPulse 10s ease-in-out infinite reverse;
  z-index: 0;
}

@keyframes glowPulse {
  0%, 100% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.1); opacity: 0.8; }
}

/* Floating Particles - Improved */
.anime-particle {
  position: absolute;
  width: 8px;
  height: 8px;
  background: #ffd700;
  border-radius: 50%;
  filter: blur(2px);
  opacity: 0;
  animation: floatParticleImproved 12s infinite linear;
  z-index: 1;
  pointer-events: none;
}

.anime-particle:nth-child(2n) {
  background: #e8003d;
  width: 12px;
  height: 12px;
  animation-duration: 15s;
}

.anime-particle:nth-child(3n) {
  background: #ff8c00;
  width: 6px;
  height: 6px;
  animation-duration: 10s;
}

@keyframes floatParticleImproved {
  0% {
    transform: translateY(0) translateX(0) rotate(0deg);
    opacity: 0;
  }
  10% {
    opacity: 0.4;
  }
  90% {
    opacity: 0.4;
  }
  100% {
    transform: translateY(-100vh) translateX(100px) rotate(360deg);
    opacity: 0;
  }
}

/* Speed Lines Effect - Improved */
.speed-lines-anime {
  position: absolute;
  inset: 0;
  background: repeating-linear-gradient(
    90deg,
    transparent,
    transparent 25px,
    rgba(255, 215, 0, 0.02) 25px,
    rgba(232, 0, 61, 0.02) 30px
  );
  pointer-events: none;
  z-index: 1;
  animation: speedLinesShift 20s linear infinite;
}

@keyframes speedLinesShift {
  0% { background-position: 0 0; }
  100% { background-position: 100px 0; }
}

/* SECTION TITLE - WITH APPEAR ANIMATION */
.section-title-wrapper {
  position: relative;
  z-index: 5;
  text-align: center;
  margin-bottom: 60px;
  animation: titleAppear 1s ease forwards;
}

@keyframes titleAppear {
  0% {
    opacity: 0;
    transform: translateY(-30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.section-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 6px;
  text-transform: uppercase;
  color: #e8003d;
  margin-bottom: 15px;
  display: inline-block;
  padding: 8px 20px;
  background: rgba(232, 0, 61, 0.1);
  border-radius: 40px;
  position: relative;
  overflow: hidden;
}

.section-subtitle::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.3), transparent);
  animation: subtitleShine 3s infinite;
}

@keyframes subtitleShine {
  0% { left: -100%; }
  20% { left: 100%; }
  100% { left: 100%; }
}

.section-title {
  font-size: 48px;
  font-weight: 800;
  color: #1a1a2e;
  margin: 0 0 15px;
  font-family: 'Montserrat', sans-serif;
  text-transform: uppercase;
  position: relative;
  display: inline-block;
  letter-spacing: 2px;
}

.section-title span {
  color: #e8003d;
  position: relative;
  display: inline-block;
  animation: titleGlow 2s ease-in-out infinite;
}

@keyframes titleGlow {
  0%, 100% { text-shadow: 0 0 10px rgba(232, 0, 61, 0.3); }
  50% { text-shadow: 0 0 20px rgba(255, 215, 0, 0.5); }
}

.section-title span::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, #ffd700, #e8003d, #ffd700);
  border-radius: 4px;
  animation: titleUnderline 3s linear infinite;
  background-size: 200% 100%;
}

@keyframes titleUnderline {
  0% { background-position: 0% 50%; }
  100% { background-position: 200% 50%; }
}

.section-title-glow {
  font-size: 18px;
  color: #666;
  font-weight: 300;
  letter-spacing: 4px;
  position: relative;
}

.section-title-glow::before,
.section-title-glow::after {
  content: '〜';
  margin: 0 10px;
  color: #ffd700;
}

/* DESKTOP GRID */
.anime-container {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 25px;
  max-width: 1400px;
  margin: 0 auto;
  position: relative;
  z-index: 5;
}

/* MOBILE CAROUSEL */
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
  transition: scroll 0.3s ease-out;
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
  transition: transform 0.1s ease;
}

.carousel-track .anime-card {
  flex: 0 0 280px;
  scroll-snap-align: start;
  margin: 5px 0;
}

/* Scroll Progress Indicator */
.scroll-indicator-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin: 20px 0 10px;
  position: relative;
  z-index: 10;
}

.scroll-progress-bar {
  width: 50px;
  height: 4px;
  background: rgba(232, 0, 61, 0.2);
  border-radius: 4px;
  overflow: hidden;
  transition: all 0.3s ease;
  cursor: pointer;
}

.scroll-progress-bar.active {
  width: 80px;
  background: linear-gradient(90deg, #ffd700, #e8003d);
  animation: progressPulse 2s ease-in-out infinite;
}

.scroll-progress-bar.active .progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #e8003d, #ffd700);
  animation: progressFill 4s linear infinite;
  transform-origin: left;
}

@keyframes progressFill {
  0% { width: 0%; }
  50% { width: 100%; }
  100% { width: 0%; }
}

@keyframes progressPulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

/* ANIME CARD - IMPROVED ANIMATIONS */
.anime-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 24px;
  overflow: hidden;
  transition: all 0.5s cubic-bezier(0.2, 0.9, 0.3, 1.1);
  position: relative;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08),
              0 0 0 1px rgba(232, 0, 61, 0.1);
  border: 1px solid rgba(255, 215, 0, 0.2);
  animation: cardSlideUp 0.6s cubic-bezier(0.2, 0.9, 0.3, 1.1) forwards;
  opacity: 0;
}

@keyframes cardSlideUp {
  0% {
    opacity: 0;
    transform: translateY(40px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.anime-card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 30px 60px rgba(232, 0, 61, 0.15),
              0 0 0 2px rgba(255, 215, 0, 0.4);
}

/* Card Badge - Improved */
.anime-card::before {
  content: '⚡';
  position: absolute;
  top: 15px;
  right: 15px;
  width: 45px;
  height: 45px;
  background: linear-gradient(135deg, #ffd700, #e8003d);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 22px;
  z-index: 10;
  box-shadow: 0 8px 20px rgba(232, 0, 61, 0.4);
  opacity: 0;
  transform: scale(0.5) rotate(-90deg);
  transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.anime-card:hover::before {
  opacity: 1;
  transform: scale(1) rotate(0deg);
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
  transition: transform 0.8s cubic-bezier(0.2, 0.9, 0.3, 1.1);
}

.anime-card:hover img {
  transform: scale(1.12) rotate(1.5deg);
}

/* Image Overlay - Improved */
.img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    transparent 0%,
    rgba(255, 255, 255, 0.1) 40%,
    rgba(232, 0, 61, 0.3) 100%
  );
  opacity: 0;
  transition: opacity 0.5s ease;
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
  line-height: 1.5;
  min-height: 48px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  font-family: 'Montserrat', sans-serif;
  transition: color 0.3s ease;
}

.anime-card:hover h3 {
  color: #e8003d;
}

/* PRICE SECTION - IMPROVED */
.price-container {
  display: flex;
  align-items: baseline;
  justify-content: center;
  gap: 8px;
  margin-bottom: 18px;
  flex-wrap: wrap;
}

.current-price {
  font-size: 26px;
  font-weight: 800;
  color: #e8003d;
  font-family: 'Montserrat', sans-serif;
  position: relative;
  display: inline-flex;
  align-items: center;
}

.current-price::before {
  content: '₹';
  font-size: 18px;
  margin-right: 2px;
  color: #ffd700;
  font-weight: 600;
}

.original-price {
  font-size: 14px;
  color: #999;
  text-decoration: line-through;
  position: relative;
  display: inline-flex;
  align-items: center;
}

.original-price::before {
  content: '₹';
  font-size: 12px;
  margin-right: 2px;
  color: #999;
}

.discount-badge {
  background: linear-gradient(135deg, #ffd700, #e8003d);
  color: white;
  font-size: 12px;
  font-weight: 700;
  padding: 4px 12px;
  border-radius: 30px;
  margin-left: 8px;
  display: inline-block;
  box-shadow: 0 4px 12px rgba(232, 0, 61, 0.3);
  animation: badgePulse 2s ease-in-out infinite;
}

@keyframes badgePulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

/* BUTTON - IMPROVED */
.anime-card button {
  width: 100%;
  padding: 14px 20px;
  border: none;
  background: linear-gradient(135deg, #1a1a2e, #16213e);
  color: white;
  font-size: 14px;
  font-weight: 700;
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.2, 0.9, 0.3, 1.1);
  position: relative;
  overflow: hidden;
  letter-spacing: 1.5px;
  font-family: 'Montserrat', sans-serif;
  text-transform: uppercase;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.anime-card button::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.anime-card button:hover {
  background: linear-gradient(135deg, #e8003d, #ffd700);
  transform: translateY(-3px);
  box-shadow: 0 15px 30px rgba(232, 0, 61, 0.4);
}

.anime-card button:hover::before {
  width: 300px;
  height: 300px;
}

.anime-card a {
  text-decoration: none;
  display: block;
}

/* VIEW ALL BUTTON - IMPROVED */
.view-all-btn {
  text-align: center;
  margin-top: 60px;
  position: relative;
  z-index: 5;
  animation: viewAllAppear 1s ease 0.5s forwards;
  opacity: 0;
}

@keyframes viewAllAppear {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.view-all-btn a {
  display: inline-block;
  padding: 16px 45px;
  background: white;
  color: #e8003d;
  text-decoration: none;
  font-weight: 700;
  border-radius: 60px;
  font-size: 16px;
  letter-spacing: 2px;
  border: 2px solid #ffd700;
  transition: all 0.4s cubic-bezier(0.2, 0.9, 0.3, 1.1);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
  position: relative;
  overflow: hidden;
}

.view-all-btn a::before {
  content: '→';
  position: absolute;
  right: -30px;
  top: 50%;
  transform: translateY(-50%);
  opacity: 0;
  transition: all 0.4s ease;
}

.view-all-btn a:hover {
  background: linear-gradient(135deg, #ffd700, #e8003d);
  color: white;
  border-color: transparent;
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(232, 0, 61, 0.3);
  padding-right: 65px;
}

.view-all-btn a:hover::before {
  right: 35px;
  opacity: 1;
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
  
  .section-title {
    font-size: 42px;
  }
}

@media (max-width: 768px) {
  .anime-section {
    padding: 50px 15px;
  }
  
  .section-title {
    font-size: 38px;
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
  
  .view-all-btn a {
    padding: 14px 35px;
    font-size: 15px;
  }
  
  .view-all-btn a:hover {
    padding-right: 55px;
  }
}

@media (max-width: 480px) {
  .section-title {
    font-size: 30px;
    letter-spacing: 1px;
  }
  
  .section-subtitle {
    font-size: 12px;
    letter-spacing: 4px;
  }
  
  .section-title-glow {
    font-size: 14px;
  }
  
  .carousel-track .anime-card {
    flex: 0 0 220px;
  }
  
  .anime-card h3 {
    font-size: 14px;
    min-height: 42px;
  }
  
  .current-price {
    font-size: 22px;
  }
  
  .anime-card button {
    padding: 12px 16px;
    font-size: 13px;
  }
  
  .scroll-progress-bar.active {
    width: 60px;
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
  
  .view-all-btn a:hover {
    background: white;
    color: #e8003d;
    transform: none;
    padding-right: 45px;
  }
  
  .view-all-btn a:hover::before {
    right: 35px;
    opacity: 1;
  }
}

/* Loading animation */
@keyframes shimmer {
  0% {
    background-position: -1000px 0;
  }
  100% {
    background-position: 1000px 0;
  }
}

/* Custom scrollbar */
.anime-section::-webkit-scrollbar {
  width: 10px;
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
<section class="anime-section" id="animeSection">
  <!-- Decorative Elements -->
  <div class="anime-glow"></div>
  <div class="anime-glow-2"></div>
  <div class="speed-lines-anime"></div>
  
  <!-- Floating Particles - Dynamically Generated -->
  <?php for($i = 0; $i < 30; $i++): ?>
    <div class="anime-particle" style="
      left: <?php echo rand(0, 100); ?>%;
      top: <?php echo rand(0, 100); ?>%;
      animation-delay: <?php echo rand(0, 20); ?>s;
      animation-duration: <?php echo rand(8, 20); ?>s;"></div>
  <?php endfor; ?>

  <!-- Section Header -->
  <div class="section-title-wrapper">
    <div class="section-subtitle">POPULAR SERIES</div>
    <h2 class="section-title">
      ANIME <span>WORLD</span>
    </h2>
    <div class="section-title-glow">人気キャラクター</div>
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
          <!-- FIXED: Correct image path -->
          <img src="orders/uploads/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div class="img-overlay"></div>
        </div>

        <div class="anime-card-content">
          <h3><?php echo htmlspecialchars($row['name']); ?></h3>

          <div class="price-container">
            <span class="current-price"><?php echo number_format($row['discount_price']); ?></span>
            <?php if($discount > 0): ?>
              <span class="original-price"><?php echo number_format($row['original_price']); ?></span>
              <span class="discount-badge">-<?php echo $discount; ?>%</span>
            <?php endif; ?>
          </div>

          <a href="orders/product_detail.php?id=<?php echo $row['id']; ?>">
            <button>BUY NOW</button>
          </a>
        </div>
      </div>

    <?php endwhile; ?>
  </div>

  <!-- MOBILE CAROUSEL - IMPROVED AUTO SCROLL -->
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
              <!-- FIXED: Correct image path -->
              <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
              <div class="img-overlay"></div>
            </div>

            <div class="anime-card-content">
              <h3><?php echo htmlspecialchars($row['name']); ?></h3>

              <div class="price-container">
                <span class="current-price"><?php echo number_format($row['discount_price']); ?></span>
                <?php if($discount > 0): ?>
                  <span class="original-price"><?php echo number_format($row['original_price']); ?></span>
                  <span class="discount-badge">-<?php echo $discount; ?>%</span>
                <?php endif; ?>
              </div>

              <a href="product_detail.php?id=<?php echo $row['id']; ?>">
                <button>BUY NOW</button>
              </a>
            </div>
          </div>

        <?php endforeach; ?>
      </div>
    </div>

    <!-- Scroll Progress Indicator -->
    <div class="scroll-indicator-container" id="scrollIndicator">
      <?php for($d = 0; $d < count($products); $d++): ?>
        <div class="scroll-progress-bar" data-index="<?php echo $d; ?>">
          <div class="progress-fill"></div>
        </div>
      <?php endfor; ?>
    </div>
  </div>

  <!-- View All Button -->
  <div class="view-all-btn">
    <a href="anime.php">VIEW ALL COLLECTION</a>
  </div>
</section>

<script>
// Main initialization
document.addEventListener('DOMContentLoaded', function() {
  
  // SECTION APPEAR ANIMATION
  const animeSection = document.getElementById('animeSection');
  
  // Create intersection observer for section appear animation
  const sectionObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        // Once visible, stop observing
        sectionObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2, rootMargin: '50px' });
  
  if (animeSection) {
    sectionObserver.observe(animeSection);
  }
  
  // MOBILE CAROUSEL FUNCTIONALITY
  const carousel = document.getElementById('animeCarousel');
  if (!carousel || window.innerWidth > 768) return;

  const track = carousel.querySelector('.carousel-track');
  const cards = track ? track.querySelectorAll('.anime-card') : [];
  const scrollBars = document.querySelectorAll('.scroll-progress-bar');

  if (!track || !cards.length) return;

  let autoScrollInterval;
  let isDragging = false;
  let startX, scrollLeft;
  let currentIndex = 0;
  const autoScrollDelay = 4000; // 4 seconds per slide

  // Get card width including gap
  const getCardWidth = () => {
    if (!cards.length) return 0;
    const gap = 20; // gap from CSS
    return cards[0].offsetWidth + gap;
  };

  // Update active indicator
  const updateActiveIndicator = (index) => {
    scrollBars.forEach((bar, i) => {
      bar.classList.toggle('active', i === index);
    });
    currentIndex = index;
  };

  // Scroll to specific index
  const scrollToIndex = (index, smooth = true) => {
    if (!track || !cards.length) return;
    const cardWidth = getCardWidth();
    if (!cardWidth) return;
    
    const maxIndex = cards.length - 1;
    const targetIndex = Math.min(Math.max(index, 0), maxIndex);
    
    carousel.scrollTo({
      left: targetIndex * cardWidth,
      behavior: smooth ? 'smooth' : 'auto'
    });
    
    updateActiveIndicator(targetIndex);
  };

  // Update index based on scroll position
  const updateIndexFromScroll = () => {
    const cardWidth = getCardWidth();
    if (!cardWidth) return;
    const newIndex = Math.round(carousel.scrollLeft / cardWidth);
    if (newIndex !== currentIndex && newIndex >= 0 && newIndex < cards.length) {
      updateActiveIndicator(newIndex);
    }
  };

  // Auto scroll function
  const startAutoScroll = () => {
    if (autoScrollInterval) clearInterval(autoScrollInterval);
    
    autoScrollInterval = setInterval(() => {
      if (!isDragging && window.innerWidth <= 768 && !document.hidden) {
        const cardWidth = getCardWidth();
        if (!cardWidth) return;
        
        const nextIndex = (currentIndex + 1) % cards.length;
        scrollToIndex(nextIndex, true);
      }
    }, autoScrollDelay);
  };

  const stopAutoScroll = () => {
    if (autoScrollInterval) {
      clearInterval(autoScrollInterval);
      autoScrollInterval = null;
    }
  };

  // Click on progress bar to navigate
  scrollBars.forEach((bar, index) => {
    bar.addEventListener('click', () => {
      stopAutoScroll();
      scrollToIndex(index, true);
      startAutoScroll();
    });
  });

  // DRAG INTERACTIONS
  carousel.addEventListener('mousedown', (e) => {
    isDragging = true;
    startX = e.pageX - carousel.offsetLeft;
    scrollLeft = carousel.scrollLeft;
    carousel.style.cursor = 'grabbing';
    stopAutoScroll();
  });

  carousel.addEventListener('mouseleave', () => {
    if (!isDragging) return;
    isDragging = false;
    carousel.style.cursor = 'grab';
    updateIndexFromScroll();
    startAutoScroll();
  });

  carousel.addEventListener('mouseup', () => {
    if (!isDragging) return;
    isDragging = false;
    carousel.style.cursor = 'grab';
    updateIndexFromScroll();
    startAutoScroll();
  });

  carousel.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const x = e.pageX - carousel.offsetLeft;
    const walk = (x - startX) * 2;
    carousel.scrollLeft = scrollLeft - walk;
  });

  // TOUCH INTERACTIONS
  carousel.addEventListener('touchstart', (e) => {
    isDragging = true;
    startX = e.touches[0].pageX - carousel.offsetLeft;
    scrollLeft = carousel.scrollLeft;
    stopAutoScroll();
  });

  carousel.addEventListener('touchend', () => {
    if (!isDragging) return;
    isDragging = false;
    updateIndexFromScroll();
    startAutoScroll();
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

  // WINDOW RESIZE HANDLING
  window.addEventListener('resize', () => {
    if (window.innerWidth <= 768) {
      startAutoScroll();
      // Recalculate current index
      updateIndexFromScroll();
    } else {
      stopAutoScroll();
    }
  });

  // VISIBILITY CHANGE
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      stopAutoScroll();
    } else if (window.innerWidth <= 768) {
      startAutoScroll();
    }
  });

  // INITIAL SETUP
  updateActiveIndicator(0);
  if (window.innerWidth <= 768) {
    startAutoScroll();
  }

  // OPTIONAL: Add wheel event for better desktop scrolling
  if (window.innerWidth <= 768) {
    carousel.addEventListener('wheel', (e) => {
      if (e.deltaY !== 0) {
        e.preventDefault();
        carousel.scrollLeft += e.deltaY * 0.5;
      }
    }, { passive: false });
  }
});

// Add smooth parallax effect on mouse move (optional)
document.addEventListener('mousemove', function(e) {
  const particles = document.querySelectorAll('.anime-particle');
  const mouseX = e.clientX / window.innerWidth;
  const mouseY = e.clientY / window.innerHeight;
  
  particles.forEach((particle, index) => {
    const speed = (index % 3) + 1;
    const x = (mouseX * 20 * speed) + 'px';
    const y = (mouseY * 20 * speed) + 'px';
    particle.style.transform = `translate(${x}, ${y})`;
  });
});
</script> 