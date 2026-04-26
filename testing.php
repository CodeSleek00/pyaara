
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
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $products[] = $row;
  }
  $stmt->close();
}

function price_final($row) {
  $o = (float)$row['original_price'];
  $d = (float)$row['discount_price'];
  return ($d > 0 && $d < $o) ? $d : $o;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Anime Products</title>

<style>
body {
  margin: 0;
  font-family: Arial;
  background: #0a0a0f;
  color: white;
  overflow-x: hidden;
}

/* SECTION 1 (HERO) */
.hero {
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: radial-gradient(circle at center, #000 0%, #120a25 100%);
}

.hero h1 {
  font-size: 80px;
  letter-spacing: 10px;
  opacity: 0.8;
  transform: scale(0.8);
  transition: 1s;
}

/* SCROLL EFFECT */
.hero.shrink h1 {
  transform: scale(1.2);
  opacity: 0;
}

/* PRODUCTS SECTION */
.products {
  padding: 60px 40px;
}

/* GRID 2 ROWS FIXED */
.grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;

  opacity: 0;
  transform: translateY(100px);
  transition: 1s ease;
}

.show .grid {
  opacity: 1;
  transform: translateY(0);
}

/* PRODUCT CARD */
.card {
  background: #111;
  padding: 15px;
  border-radius: 15px;
  transform: translateY(50px) scale(0.9);
  opacity: 0;
  transition: 0.5s ease;
}

.show .card {
  transform: translateY(0) scale(1);
  opacity: 1;
}

/* STAGGER EFFECT */
.card:nth-child(1) { transition-delay: 0.1s; }
.card:nth-child(2) { transition-delay: 0.2s; }
.card:nth-child(3) { transition-delay: 0.3s; }
.card:nth-child(4) { transition-delay: 0.4s; }
.card:nth-child(5) { transition-delay: 0.5s; }
.card:nth-child(6) { transition-delay: 0.6s; }
.card:nth-child(7) { transition-delay: 0.7s; }
.card:nth-child(8) { transition-delay: 0.8s; }

.card img {
  width: 100%;
  border-radius: 10px;
}

.card:hover {
  transform: scale(1.05);
}

a {
  color: #00d4ff;
  text-decoration: none;
  font-weight: bold;
}

.price {
  display: block;
  margin-top: 5px;
}

/* MOBILE */
@media(max-width: 900px) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
</head>

<body>

<!-- HERO -->
<section class="hero" id="hero">
  <h1>ANIME</h1>
</section>

<!-- PRODUCTS -->
<section class="products" id="products">
  <div class="grid">
    <?php foreach ($products as $row): ?>
      <div class="card">
        <a href="orders/product_detail.php?id=<?= $row['id'] ?>">
          <?= htmlspecialchars($row['name']) ?>
        </a>

        <span class="price">₹<?= number_format(price_final($row)) ?></span>

        <img src="orders/uploads/<?= htmlspecialchars($row['image']) ?>">
      </div>
    <?php endforeach; ?>
  </div>
</section>

<script>
let triggered = false;

window.addEventListener("scroll", () => {
  let scroll = window.scrollY;

  // HERO shrink effect
  if (scroll > 50) {
    document.getElementById("hero").classList.add("shrink");
  }

  // PRODUCT reveal
  if (!triggered && scroll > window.innerHeight / 2) {
    document.getElementById("products").classList.add("show");
    triggered = true;
  }
});
</script>

</body>
</html>
