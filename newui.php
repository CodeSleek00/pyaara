<?php
include 'db.php';

/* Fetch 4 random exclusive products */
$exclusiveProducts = $conn->query("
    SELECT * FROM products 
    WHERE page = 'exclusive.php'
    ORDER BY RAND()
    LIMIT 4
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pyaara Store | Exclusive Collection</title>

<link rel="icon" type="image/png" href="../images/Pyaara Circle.png">
<link rel="apple-touch-icon" href="../images/Pyaara Circle.png">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --red: #E63946;
  --yellow: #FFD166;
  --dark: #2D3436;
  --light: #F8F9FA;
  --radius: 10px;
  --shadow: 0 6px 20px rgba(0,0,0,0.08);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Outfit', sans-serif;
}

body {
  background: #fff;
  color: var(--dark);
}

/* ===== Exclusive Section ===== */
.exclusive-section {
  max-width: 1400px;
  margin: 60px auto;
  padding: 0 20px;
}

.exclusive-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.exclusive-header h2 {
  font-size: 2rem;
  font-weight: 700;
}

.more-btn {
  color: var(--red);
  font-weight: 600;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 6px;
}

.more-btn:hover {
  text-decoration: underline;
}

/* ===== Products Grid (Desktop) ===== */
.products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 25px;
}

/* ===== Product Card ===== */
.product-card {
  background: #fff;
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: 0.3s ease;
}

.product-card:hover {
  transform: translateY(-6px);
}

/* Image */
.product-image-container {
  width: 100%;
  padding-top: 120%;
  position: relative;
  background: #f1f1f1;
}

.product-image-container img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Discount Badge */
.discount-tag {
  position: absolute;
  top: 12px;
  right: 12px;
  background: var(--yellow);
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

/* Info */
.product-info {
  padding: 16px;
}

.product-name {
  font-size: 1rem;
  font-weight: 600;
  height: 44px;
  overflow: hidden;
}

.price {
  margin: 8px 0 12px;
}

.current-price {
  color: var(--red);
  font-weight: 700;
  font-size: 1.1rem;
}

.original-price {
  margin-left: 6px;
  text-decoration: line-through;
  opacity: 0.6;
  font-size: 0.9rem;
}

/* Buttons */
.product-actions {
  display: flex;
  gap: 10px;
}

.btn {
  flex: 1;
  padding: 10px;
  font-size: 0.85rem;
  font-weight: 600;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  text-decoration: none;
  text-align: center;
}

.btn-buy {
  background: var(--red);
  color: #fff;
}

.btn-cart {
  background: #fff;
  color: var(--red);
  border: 1px solid var(--red);
}

/* ===== Mobile Horizontal Scroll ===== */
@media (max-width: 768px) {
  .products-grid {
    display: flex;
    overflow-x: auto;
    gap: 16px;
    padding-bottom: 10px;
    scroll-snap-type: x mandatory;
  }

  .products-grid::-webkit-scrollbar {
    display: none;
  }

  .product-card {
    min-width: 70%;
    scroll-snap-align: start;
  }
}
</style>
</head>

<body>

<section class="exclusive-section">
  <div class="exclusive-header">
    <h2>Exclusive Collection</h2>
    <a href="exclusive.php" class="more-btn">
      More Products <i class="fa fa-arrow-right"></i>
    </a>
  </div>

  <div class="products-grid">
    <?php if ($exclusiveProducts && $exclusiveProducts->num_rows > 0): ?>
      <?php while ($row = $exclusiveProducts->fetch_assoc()): ?>
        <div class="product-card">
          <div class="product-image-container">
            <a href="product_detail.php?id=<?php echo $row['id']; ?>">
              <img src="orders/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            </a>

            <?php if ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0): ?>
              <div class="discount-tag"><?php echo $row['discount_percent']; ?>% OFF</div>
            <?php endif; ?>
          </div>

          <div class="product-info">
            <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>

            <div class="price">
              <?php if ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0): ?>
                <span class="current-price">₹<?php echo number_format($row['discount_price']); ?></span>
                <span class="original-price">₹<?php echo number_format($row['original_price']); ?></span>
              <?php else: ?>
                <span class="current-price">₹<?php echo number_format($row['original_price']); ?></span>
              <?php endif; ?>
            </div>

            <div class="product-actions">
              <a href="orders/product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-buy">Buy Now</a>

              
            </div>

          </div>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</section>

</body>
</html>

<?php $conn->close(); ?>
