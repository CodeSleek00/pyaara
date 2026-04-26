<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = null;

// Use the same DB connection as product pages when available.
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
</head>
<body>
  <h1>Products</h1>

  <?php if (empty($products)): ?>
    <p>No products found.</p>
  <?php else: ?>
    <p>Total: <?= count($products) ?></p>
    <ul>
      <?php foreach ($products as $row): ?>
        <?php
          $id = (int)($row['id'] ?? 0);
          $name = htmlspecialchars((string)($row['name'] ?? ''), ENT_QUOTES, 'UTF-8');
          $image = htmlspecialchars((string)($row['image'] ?? ''), ENT_QUOTES, 'UTF-8');
          $price = price_final($row);
        ?>
        <li>
          <a href="orders/product_detail.php?id=<?= $id ?>"><?= $name ?></a>
          — ₹<?= number_format($price) ?>
          <?php if ($image !== ''): ?>
            <div>
              <img src="orders/uploads/<?= $image ?>" alt="<?= $name ?>" loading="lazy">
            </div>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>

