<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = null;

if (file_exists(__DIR__ . '/orders/db_connect.php')) {
  require_once __DIR__ . '/orders/db_connect.php';
} elseif (file_exists(__DIR__ . '/temp_db.php')) {
  require_once __DIR__ . '/temp_db.php';
}

if (!($conn instanceof mysqli)) {
  die('Database connection not available.');
}

$pageA = 'anime.php';
$pageB = 'orders/anime.php';
$products = [];

$stmt = $conn->prepare("
  SELECT id, image, name, original_price, discount_price
  FROM products
  WHERE page IN (?, ?)
  ORDER BY id DESC
  LIMIT 8
");

if ($stmt) {
  $stmt->bind_param('ss', $pageA, $pageB);
  if ($stmt->execute()) {
    $res = $stmt->get_result();
    if ($res) {
      while ($row = $res->fetch_assoc()) {
        $products[] = $row;
      }
    } else {
      // Fallback for environments without mysqlnd (no get_result()).
      $id = null;
      $image = null;
      $name = null;
      $original = null;
      $discount = null;
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
function price_final($row) {
  if (!is_array($row)) return 0;

  $o = (float)($row['original_price'] ?? 0);
  $d = (float)($row['discount_price'] ?? 0);

  return ($d > 0 && $d < $o) ? $d : $o;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- ANIME FONT -->
<link href="https://fonts.googleapis.com/css2?family=Bangers&family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">

<title>Anime Products | Otaku Merch</title>

<style>
/* ===== BASE REFINEMENTS ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  margin: 0;
 
  overflow-x: hidden;
  background: #0a0a0f;  /* deeper dark base */
}

/* WRAPPER */
.wrapper {
  height: 200vh;
  background: linear-gradient(145deg, #03000a 0%, #0c0b14 100%);
}

/* HERO SECTION – smooth sticky with refined gradient */
.hero {
  position: sticky;
   font-family: 'Noto Sans JP', sans-serif;
  top: 0;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  
  /* more anime‑vibe gradient with deep purple/blue */
  background: radial-gradient(circle at 20% 30%, #0b001a, #010008);
  overflow: hidden;
  box-shadow: inset 0 0 120px rgba(0,0,0,0.6);
  transition: all 0.3s;
}

/* subtle animated aura behind title */
.hero::before {
  content: "";
  position: absolute;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,80,120,0.08) 0%, rgba(0,0,0,0) 70%);
  animation: softPulse 12s infinite alternate;
  pointer-events: none;
}

@keyframes softPulse {
  0% { opacity: 0.3; transform: scale(1);}
  100% { opacity: 0.8; transform: scale(1.15);}
}

/* MAIN TEXT – more dynamic, slight glow */
.hero h1 {
  font-family: 'Bangers', cursive;
  font-size: 110px;
  letter-spacing: 12px;
  color: #fff3e6;
  text-shadow: 0 0 25px rgba(255,100,150,0.5), 0 10px 20px rgba(0,0,0,0.4);
  transition: 0.6s cubic-bezier(0.2, 0.9, 0.4, 1.1);
  background: linear-gradient(135deg, #ffe6f0, #ffb3c6);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  text-shadow: none;
  letter-spacing: 14px;
}

/* alternative solid fallback for better visibility */
@supports not (background-clip: text) {
  .hero h1 {
    color: #ffdfc4;
    text-shadow: 0 0 12px #ff4d7a;
  }
}

/* JAPANESE VERTICAL TEXT – more elegant */
.jp-text {
  position: absolute;
  right: 45px;
  top: 50%;
  transform: translateY(-50%);
  writing-mode: vertical-rl;
  font-size: 30px;
  font-weight: 500;
  color: rgba(255,220,180,0.65);
  letter-spacing: 8px;
  text-shadow: 0 0 8px rgba(0,0,0,0.5);
  font-family: 'Noto Sans JP', sans-serif;
  transition: 0.5s ease;
  backdrop-filter: blur(2px);
  padding: 12px 4px;
  border-radius: 60px;
}

/* PRODUCTS CONTAINER – refined scaling and backdrop */
.products {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  
  display: flex;
  align-items: center;
  justify-content: center;

  opacity: 0;
  transform: scale(1.2);
  transition: 0.8s cubic-bezier(0.2, 0.9, 0.3, 1.1);
  backdrop-filter: blur(0px);
}

.active .products {
  backdrop-filter: blur(4px);
}

/* GRID – breathing space & modern */
.grid {
  width: 88%;
  max-width: 1400px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 28px;
  margin: 0 auto;
}

/* CARD – crisp white, soft shadows, better corner rounding */
.card {
  background: rgba(255,255,255,0.98);
  color: #1a1a2e;
  padding: 18px 18px 22px;
  border-radius: 32px;
  transform: translateY(70px);
  opacity: 0;
  transition: 0.45s cubic-bezier(0.2, 0.9, 0.4, 1.2);
  box-shadow: 0 20px 35px -12px rgba(0,0,0,0.4), 0 1px 3px rgba(0,0,0,0.05);
  backdrop-filter: blur(0px);
  display: flex;
  flex-direction: column;
  will-change: transform;
}

.card:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 28px 38px -14px rgba(0,0,0,0.45);
  transition: 0.2s ease-out;
}

/* image wrapper for consistency */
.card img {
  width: 100%;
  border-radius: 24px;
  aspect-ratio: 1 / 1;
  object-fit: cover;
  background: #f0eef5;
  margin: 12px 0 12px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.05);
  transition: transform 0.25s ease;
}

.card:hover img {
  transform: scale(1.01);
}

/* product name link styling */
.card a:first-of-type {
  font-weight: 800;
  font-size: 1.25rem;
  color: #12102a;
  text-decoration: none;
  letter-spacing: -0.3px;
  transition: 0.15s;
  border-bottom: 2px solid transparent;
  display: inline-block;
  width: fit-content;
}

.card a:first-of-type:hover {
  color: #e34b7a;
  border-bottom-color: #e34b7a;
}

/* price style */
.price {
  display: block;
  margin-top: 8px;
  font-size: 1.45rem;
  font-weight: 700;
  color: #d13b6c;
  background: rgba(209,59,108,0.08);
  display: inline-block;
  padding: 4px 12px;
  border-radius: 60px;
  width: fit-content;
  letter-spacing: -0.2px;
}

/* button (open product) – modern & consistent */
.btn {
  display: inline-block;
  margin-top: 18px;
  padding: 12px 10px;
  border-radius: 44px;
  background: #10101c;
  color: #fff2ea;
  font-weight: 700;
  text-align: center;
  transition: 0.2s;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
  border: 1px solid rgba(255,255,255,0.1);
  text-decoration: none;
}

.btn:hover {
  background: #e34b7a;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 8px 18px rgba(227,75,122,0.3);
  border-color: transparent;
}

/* stagger delays */
.card:nth-child(1){transition-delay:0.05s}
.card:nth-child(2){transition-delay:0.12s}
.card:nth-child(3){transition-delay:0.19s}
.card:nth-child(4){transition-delay:0.26s}
.card:nth-child(5){transition-delay:0.33s}
.card:nth-child(6){transition-delay:0.40s}
.card:nth-child(7){transition-delay:0.47s}
.card:nth-child(8){transition-delay:0.54s}

/* ACTIVE STATE – smooth reveal */
.active .hero h1 {
  transform: scale(0.45) translateY(-20px);
  opacity: 0;
}

.active .jp-text {
  opacity: 0;
  transform: translateY(-50%) scale(0.9);
  pointer-events: none;
}

.active .products {
  opacity: 1;
  transform: scale(1);
}

.active .card {
  transform: translateY(0);
  opacity: 1;
}

/* subtle mask transition */
.wrapper.active .hero {
  box-shadow: inset 0 0 60px rgba(0,0,0,0.8);
}

/* no products fallback style */
.no-products-card {
  background: rgba(30,25,45,0.9);
  backdrop-filter: blur(8px);
  color: #ffefdb;
  padding: 2rem;
  border-radius: 40px;
  text-align: center;
  width: 100%;
  max-width: 500px;
}

.no-products-card code {
  background: #2a223a;
  padding: 4px 8px;
  border-radius: 16px;
  color: #ffbc6e;
}

/* RESPONSIVE (slightly polished) */
@media(max-width: 1000px){
  .grid{
    grid-template-columns: repeat(3,1fr);
    gap: 20px;
  }
  .hero h1 {
    font-size: 82px;
    letter-spacing: 8px;
  }
  .jp-text {
    right: 20px;
    font-size: 24px;
  }
}

@media(max-width: 720px){
  .grid{
    grid-template-columns: repeat(2,1fr);
    gap: 18px;
    width: 92%;
  }
  .hero h1 {
    font-size: 60px;
    letter-spacing: 4px;
  }
  .jp-text {
    right: 12px;
    font-size: 20px;
    letter-spacing: 4px;
  }
  .card {
    padding: 14px;
  }
  .btn {
    padding: 8px 8px;
    font-size: 0.75rem;
  }
  .price {
    font-size: 1.2rem;
  }
}

@media(max-width: 480px){
  .grid{
    grid-template-columns: 1fr;
    width: 85%;
  }
  .hero h1 {
    font-size: 44px;
  }
}
</style>
</head>

<body>

<div class="wrapper" id="wrap">

  <section class="hero">
    <h1>ANIME</h1>

    <!-- JAPANESE VERTICAL TEXT -->
    <div class="jp-text">
      アニメ・スタイル
    </div>

    <!-- PRODUCTS GRID -->
    <div class="products" aria-label="Featured anime products">
      <div class="grid">
        <?php if (empty($products)): ?>
          <div class="no-products-card" style="grid-column: 1/-1; justify-self: center;">
            <strong>✨ No anime products found ✨</strong><br>
            <span style="display: block; margin-top: 12px;">Add products with page = <code>anime.php</code> or <code>orders/anime.php</code></span>
            <span style="font-size: 0.85rem; opacity: 0.8;">⚡ bring your collection to life</span>
          </div>
        <?php else: ?>
          <?php foreach ($products as $row): ?>
            <?php
              if (!is_array($row)) continue;
              $id = (int)($row['id'] ?? 0);
              $name = htmlspecialchars((string)($row['name'] ?? 'Unnamed Product'), ENT_QUOTES, 'UTF-8');
              $image = htmlspecialchars((string)($row['image'] ?? ''), ENT_QUOTES, 'UTF-8');
              $price = number_format(price_final($row));
              // Add fallback placeholder if image missing (optional)
              $imageSrc = (!empty($image) && file_exists(__DIR__ . '/orders/uploads/' . $image)) ? 'orders/uploads/' . $image : '';
            ?>
            <div class="card">
              <a href="orders/product_detail.php?id=<?= $id ?>"><?= $name ?></a>
              <span class="price">₹<?= $price ?></span>
              <?php if (!empty($imageSrc)): ?>
                <img loading="lazy" src="<?= $imageSrc ?>" alt="<?= $name ?> illustration" onerror="this.style.display='none'">
              <?php else: ?>
                <div style="background: #f2e9ff; border-radius: 24px; height: auto; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 12px 0; color: #b19cd9;">🎴</div>
              <?php endif; ?>
              <a class="btn" href="orders/product_detail.php?id=<?= $id ?>">📖 Open Product</a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </section>

</div>

<script>
// Enhanced scroll detection – smoother trigger and debounce for performance
(function() {
  let ticking = false;
  const wrapEl = document.getElementById("wrap");
  const triggerOffset = window.innerHeight / 3.2;  // similar feel but slightly earlier for elegance

  function updateScrollClass() {
    const scrollPos = window.scrollY;
    if (scrollPos > triggerOffset) {
      if (!wrapEl.classList.contains("active")) {
        wrapEl.classList.add("active");
      }
    } else {
      if (wrapEl.classList.contains("active")) {
        wrapEl.classList.remove("active");
      }
    }
    ticking = false;
  }

  window.addEventListener("scroll", () => {
    if (!ticking) {
      requestAnimationFrame(updateScrollClass);
      ticking = true;
    }
  });

  // initial check (in case page loads with scroll)
  updateScrollClass();
})();
</script>

</body>
</html>