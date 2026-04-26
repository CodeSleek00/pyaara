```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = null;

// DB connection
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
      $id = $image = $name = $original = $discount = null;
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

function price_final(array $row): float {
  $original = (float)($row['original_price'] ?? 0);
  $discount = (float)($row['discount_price'] ?? 0);
  if ($discount > 0 && $discount < $original) return $discount;
  return $original;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Products</title>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #0a0a0f;
  color: white;
}

/* SHUTTER */
#shutter {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
}

.shutter-top,
.shutter-bottom {
  position: absolute;
  width: 100%;
  height: 50%;
  background: radial-gradient(ellipse at center, #000000 0%, #110921 100%);
  transition: transform 1s ease-in-out;
}

.shutter-top { top: 0; }
.shutter-bottom { bottom: 0; }

.open .shutter-top { transform: translateY(-100%); }
.open .shutter-bottom { transform: translateY(100%); }

/* PRODUCTS */
.container {
  padding: 40px;
}

h1 {
  text-align: center;
  margin-bottom: 20px;
}

ul {
  list-style: none;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;

  opacity: 0;
  transform: translateY(50px);
  transition: all 1s ease;
}

.show-products ul {
  opacity: 1;
  transform: translateY(0);
}

li {
  background: #111;
  padding: 15px;
  border-radius: 12px;
  transition: 0.3s;
}

li:hover {
  transform: translateY(-5px);
}

img {
  width: 100%;
  border-radius: 10px;
  margin-top: 10px;
}

a {
  color: #00d4ff;
  text-decoration: none;
  font-weight: bold;
}

.price {
  margin-top: 5px;
  display: block;
}
</style>

</head>
<body>

<!-- SHUTTER -->
<div id="shutter">
  <div class="shutter-top"></div>
  <div class="shutter-bottom"></div>
</div>

<div class="container">
  <h1>🔥 Products</h1>

  <?php if (empty($products)): ?>
    <p>No products found.</p>
  <?php else: ?>
    <p>Total: <?= count($products) ?></p>

    <ul>
      <?php foreach ($products as $row): ?>
        <?php
          $id = (int)($row['id'] ?? 0);
          $name = htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8');
          $image = htmlspecialchars((string)$row['image'], ENT_QUOTES, 'UTF-8');
          $price = price_final($row);
        ?>
        <li>
          <a href="orders/product_detail.php?id=<?= $id ?>">
            <?= $name ?>
          </a>

          <span class="price">₹<?= number_format($price) ?></span>

          <?php if ($image): ?>
            <img src="orders/uploads/<?= $image ?>" alt="<?= $name ?>">
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<script>
let opened = false;

window.addEventListener("scroll", () => {
  if (!opened && window.scrollY > 50) {
    document.getElementById("shutter").classList.add("open");
    document.body.classList.add("show-products");
    opened = true;
  }
});

/* OPTIONAL AUTO OPEN */
window.addEventListener("load", () => {
  setTimeout(() => {
    document.getElementById("shutter").classList.add("open");
    document.body.classList.add("show-products");
  }, 700);
});
</script>

</body>
</html>
```
