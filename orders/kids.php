<?php
include 'db_connect.php';

/* ================= FIXED QUERY (KIDS TABLE) ================= */
$products = $conn->query("
SELECT kp.*, kc.category_name 
FROM kids_products kp
LEFT JOIN kids_categories kc ON kp.category_id = kc.id
ORDER BY kp.id DESC
");

/* ================= ARRAY + SHUFFLE ================= */
$products_array = [];
if ($products && $products->num_rows > 0) {
    while ($row = $products->fetch_assoc()) {
        $products_array[] = $row;
    }
    shuffle($products_array);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kids | Pyaara Store</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-red: #E63946;
      --primary-yellow: #FFD166;
      --primary-white: #FFFFFF;
      --dark-gray: #2D3436;
      --light-gray: #F1FAEE;
      --border-radius: 8px;
      --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
    body { background: var(--primary-white); color: var(--dark-gray); }
    .container { max-width: 1400px; margin: auto; padding: 20px; }
    .page-title { font-size: 2.2rem; text-align: center; margin: 30px 0 10px; }
    .products-grid {
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;
    }
    .product-card {
      background: var(--primary-white); border-radius: var(--border-radius);
      overflow: hidden; box-shadow: var(--box-shadow); transition: var(--transition);
    }
    .product-card:hover { transform: translateY(-5px); }
    .product-image-container {
      width: 100%;  position: relative; background: var(--light-gray);
    }
    .product-image {
      position: absolute; width: 100%; height: 100%; object-fit: cover;
    }
    .discount-tag {
      position: absolute; top: 12px; right: 12px;
      background: var(--primary-yellow); padding: 4px 10px;
      font-size: 0.75rem; border-radius: 20px;
    }
    .product-info { padding: 18px; }
    .product-name { font-size: 1rem; font-weight: 600; height: 44px; overflow: hidden; }
    .price-container { margin-bottom: 15px; }
    .current-price { font-size: 1.1rem; font-weight: 700; color: var(--primary-red); }
    .original-price { text-decoration: line-through; font-size: 0.85rem; color: gray; }
    .product-actions { display: flex; gap: 10px; }
    .btn {
      flex: 1; padding: 10px; border-radius: 8px;
      text-align: center; text-decoration: none; cursor: pointer;
    }
    .btn-primary { background: var(--primary-red); color: white; border: none; }
    .btn-secondary { border: 1px solid var(--primary-red); color: var(--primary-red); }

    @media (max-width: 768px) {
      .products-grid { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>

<body>

<div class="container">

<h1 class="page-title">Kids Collection 👶</h1>

<div class="products-grid">

<?php if (!empty($products_array)): ?>
<?php foreach ($products_array as $row): 

/* DISCOUNT CALCULATION */
$discount = 0;
if($row['price'] > 0){
    $discount = (($row['price'] - $row['discount_price']) / $row['price']) * 100;
}
?>

<div class="product-card">

<div class="product-image-container">
<a href="product_detail.php?id=<?php echo $row['id']; ?>">
<img class="product-image" src="uploads/<?php echo htmlspecialchars($row['image']); ?>">
</a>

<?php if ($row['discount_price'] < $row['price']): ?>
<div class="discount-tag"><?php echo number_format($discount); ?>% OFF</div>
<?php endif; ?>

</div>

<div class="product-info">

<div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>

<p style="font-size:12px;color:#666;">
<?php echo $row['category_name']; ?> | <?php echo $row['age_group']; ?>
</p>

<div class="price-container">
<span class="current-price">₹<?php echo $row['discount_price']; ?></span>
<span class="original-price">₹<?php echo $row['price']; ?></span>
</div>

<div class="product-actions">

<a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">
View
</a>

<form method="POST" action="add_to_cart.php" style="flex:1;">
<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">

<?php if($row['stock'] > 0): ?>
<button type="submit" class="btn btn-primary">Add to Cart</button>
<?php else: ?>
<button class="btn btn-primary" style="background:gray;" disabled>
Out of Stock
</button>
<?php endif; ?>

</form>

</div>

</div>
</div>

<?php endforeach; ?>

<?php else: ?>
<p style="text-align:center;">No products found</p>
<?php endif; ?>

</div>
</div>

</body>
</html>

<?php $conn->close(); ?>