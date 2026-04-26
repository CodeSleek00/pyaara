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
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ANIME FONT -->
<link href="https://fonts.googleapis.com/css2?family=Bangers&family=Noto+Sans+JP:wght@300;700&display=swap" rel="stylesheet">

<title>Anime Products</title>

<style>
body {
  margin: 0;
  font-family: 'Noto Sans JP', sans-serif;
  overflow-x: hidden;
}

/* WRAPPER */
.wrapper {
  height: 200vh;
}

/* HERO */
.hero {
  position: sticky;
  top: 0;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  
  background: linear-gradient(to bottom, #020008 0%, #ffffff 100%);
  overflow: hidden;
}

/* MAIN TEXT */
.hero h1 {
  font-family: 'Bangers', cursive;
  font-size: 100px;
  letter-spacing: 10px;
  color: white;
  text-shadow: 0 0 20px rgba(255,255,255,0.2);
  transition: 0.6s ease;
}

/* JAPANESE TEXT */
.jp-text {
  position: absolute;
  right: 40px;
  top: 50%;
  transform: translateY(-50%);
  writing-mode: vertical-rl;
  font-size: 28px;
  color: rgba(255,255,255,0.5);
  letter-spacing: 5px;
}

/* PRODUCTS */
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
  transition: 0.8s ease;
}

/* GRID */
.grid {
  width: 90%;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

/* CARD */
.card {
  background: white;
  color: black;
  padding: 15px;
  border-radius: 15px;
  transform: translateY(60px);
  opacity: 0;
  transition: 0.5s ease;
}

.card img {
  width: 100%;
  border-radius: 10px;
}

.card:hover {
  transform: scale(1.05);
}

/* STAGGER */
.card:nth-child(1){transition-delay:0.1s}
.card:nth-child(2){transition-delay:0.2s}
.card:nth-child(3){transition-delay:0.3s}
.card:nth-child(4){transition-delay:0.4s}
.card:nth-child(5){transition-delay:0.5s}
.card:nth-child(6){transition-delay:0.6s}
.card:nth-child(7){transition-delay:0.7s}
.card:nth-child(8){transition-delay:0.8s}

/* ACTIVE */
.active .hero h1 {
  transform: scale(0.5);
  opacity: 0;
}

.active .jp-text {
  opacity: 0;
}

.active .products {
  opacity: 1;
  transform: scale(1);
}

.active .card {
  transform: translateY(0);
  opacity: 1;
}

a {
  color: black;
  text-decoration: none;
  font-weight: bold;
}

.price {
  display: block;
  margin-top: 5px;
}

/* MOBILE */
@media(max-width:900px){
  .grid{
    grid-template-columns: repeat(2,1fr);
  }
}
</style>
</head>

<body>

<div class="wrapper" id="wrap">

  <section class="hero">
    <h1>ANIME</h1>

    <!-- JAPANESE TEXT -->
    <div class="jp-text">
      アニメ・スタイル
    </div>

    <!-- PRODUCTS -->
  <div class="card">
  <a href="orders/product_detail.php?id=<?= $row['id'] ?>">
    <?= htmlspecialchars($row['name']) ?>
  </a>

  <span class="price">₹<?= number_format(price_final($row)) ?></span>

  <img src="orders/uploads/<?= htmlspecialchars($row['image']) ?>">

  <!-- BUTTONS -->
  <div class="btns">
    <a href="orders/product_detail.php?id=<?= $row['id'] ?>" class="btn view">View</a>
    
    <form action="orders/add_to_cart.php" method="POST">
      <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
      <button type="submit" class="btn buy">Buy Now</button>
    </form>
  </div>
</div>

  </section>

</div>

<script>
window.addEventListener("scroll", () => {
  let scroll = window.scrollY;
  let trigger = window.innerHeight / 3;

  if (scroll > trigger) {
    document.getElementById("wrap").classList.add("active");
  } else {
    document.getElementById("wrap").classList.remove("active");
  }
});
</script>

</body>
</html>
```
