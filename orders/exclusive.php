<?php
include 'db_connect.php';

$page = basename($_SERVER['PHP_SELF']);
$products = $conn->query("SELECT * FROM products WHERE page = '$page' ORDER BY id DESC");

// Store products in an array and shuffle them
$products_array = [];
if ($products && $products->num_rows > 0) {
    while ($row = $products->fetch_assoc()) {
        $products_array[] = $row;
    }
    shuffle($products_array); // This randomizes the order of products
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <link rel="icon" type="image/png" href="../images/Pyaara Circle.png">
    <link rel="apple-touch-icon" href="../images/Pyaara Circle.png">
  <title><?php echo ucfirst(str_replace('.php', '', $page)); ?> | Pyaara Store</title>
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
    .back-button a { color: var(--primary-red); font-weight: 600; text-decoration: none; }
    .back-button a:hover { text-decoration: underline; }
    .page-title { font-size: 2.2rem; text-align: center; margin: 30px 0 10px; position: relative; }
    .page-title::after {
      content: ''; width: 80px; height: 4px; background: var(--primary-red);
      position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%);
    }
    .products-grid {
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;
    }
    .product-card {
      background: var(--primary-white); border-radius: var(--border-radius);
      overflow: hidden; box-shadow: var(--box-shadow); transition: var(--transition);
    }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .product-image-container {
      width: 100%; padding-top: 120%; position: relative; overflow: hidden; background: var(--light-gray);
    }
    .product-image {
      position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;
    }
    .discount-tag {
      position: absolute; top: 12px; right: 12px;
      background: var(--primary-yellow); color: var(--dark-gray);
      padding: 4px 10px; font-size: 0.75rem; font-weight: 600; border-radius: 20px;
    }
    .product-info { padding: 18px; }
    .product-name {
      font-size: 1rem; font-weight: 600; height: 44px; overflow: hidden;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    }
    .price-container { margin-bottom: 15px; }
    .current-price { font-size: 1.1rem; font-weight: 700; color: var(--primary-red); }
    .original-price {
      font-size: 0.85rem; text-decoration: line-through;
      color: var(--dark-gray); opacity: 0.6; margin-left: 5px;
    }
    .discount-percent {
      font-size: 0.85rem; color: var(--primary-yellow);
      margin-left: 8px; font-weight: 600;
    }
    .product-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn {
      flex: 1; padding: 10px 15px; font-size: 0.9rem; font-weight: 600;
      border: none; border-radius: var(--border-radius); cursor: pointer;
      text-align: center; text-decoration: none; transition: var(--transition);
    }
    .btn-primary { background-color: var(--primary-red); color: var(--primary-white); }
    .btn-secondary {
      background-color: var(--primary-white); color: var(--primary-red);
      border: 1px solid var(--primary-red);
    }
    .btn:hover { opacity: 0.9; transform: translateY(-2px); }

    .no-products {
      grid-column: 1 / -1; text-align: center;
      padding: 40px; font-size: 1.1rem; color: #666;
    }

    @media (max-width: 1200px) {
      .products-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
      .products-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
    }
    @media (max-width: 480px) {
      .products-grid { grid-template-columns: repeat(2, 1fr); }
      .product-actions { flex-direction: column; }
      .btn { width: 100%; }
    }

    #shareModal {
      display: none;
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.5); z-index: 9999;
    }

    #shareModal .modal-content {
      position: absolute; top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      background: #fff; padding: 20px;
      border-radius: 10px; width: 300px;
    }

    #shareModal input {
      width: 100%; padding: 10px; margin-bottom: 10px;
      font-size: 14px; border-radius: 6px; border: 1px solid #ccc;
    }

    #shareModal .social-icons i {
      font-size: 20px; margin: 0 10px; color: #2D3436; cursor: pointer;
      transition: transform 0.2s;
    }

    #shareModal .social-icons i:hover {
      transform: scale(1.2); color: var(--primary-red);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="back-button">
      <a href="javascript:history.back()"><i class="fa fa-arrow-left"></i> Go Back</a>
    </div>

    <h1 class="page-title"><?php echo ucfirst(str_replace('.php', '', $page)); ?></h1>

    <div class="products-grid">
      <?php if (!empty($products_array)): ?>
        <?php foreach ($products_array as $row): ?>
          <div class="product-card">
            <div class="product-image-container">
              <a href="product_detail.php?id=<?php echo $row['id']; ?>">
                <img loading="lazy" class="product-image" src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Buy <?php echo htmlspecialchars($row['name']); ?>">
              </a>
              <?php if ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0): ?>
                <div class="discount-tag"><?php echo number_format($row['discount_percent']); ?>% OFF</div>
              <?php endif; ?>
            </div>
            <div class="product-info">
              <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
              <div class="price-container">
                <?php if ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0): ?>
                  <span class="current-price">₹<?php echo number_format($row['discount_price'], 2); ?></span>
                  <span class="original-price">₹<?php echo number_format($row['original_price'], 2); ?></span>
                  <span class="discount-percent">Save <?php echo number_format($row['discount_percent'], 0); ?>%</span>
                <?php else: ?>
                  <span class="current-price">₹<?php echo number_format($row['original_price'], 2); ?></span>
                <?php endif; ?>
              </div>
             <div class="product-actions">
  <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Buy Now</a>

  <form method="POST" action="add_to_cart.php" style="flex:1;">
    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="btn btn-primary">
   Add to Cart
    </button>
  </form>
</div>

            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-products">
          <p>No products found in this category.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>


  <script>
    const shareButtons = document.querySelectorAll('.share-btn');
    const shareModal = document.getElementById('shareModal');
    const shareLinkInput = document.getElementById('shareLink');

    shareButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const link = btn.getAttribute('data-link');
        shareLinkInput.value = link;

        document.getElementById('fbShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(link)}`;
        document.getElementById('waShare').href = `https://api.whatsapp.com/send?text=${encodeURIComponent(link)}`;
        document.getElementById('twShare').href = `https://twitter.com/intent/tweet?url=${encodeURIComponent(link)}`;

        shareModal.style.display = 'block';
      });
    });

    function copyLink() {
      shareLinkInput.select();
      document.execCommand('copy');
      alert("Link copied to clipboard!");
      shareModal.style.display = 'none';
    }

    shareModal.addEventListener('click', function (e) {
      if (e.target === shareModal) {
        shareModal.style.display = 'none';
      }
    });
  </script>
</body>
</html>

<?php $conn->close(); ?>