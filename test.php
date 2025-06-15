<?php
include 'db.php';

// Fetch product details (example query, adjust as needed)
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product_query = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $product_query->fetch_assoc();

// Dummy data for reviews and rating
$reviews = [1, 2, 3]; // Replace with actual review data
$average_rating = 4.3; // Replace with actual average rating
$message = '';
$message_type = 'success';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> | Product Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            background-color: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
            padding: 20px;
        }

        .back a {
            color: #333;
            text-decoration: none;
            font-size: 18px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .product-detail-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .product-detail-image {
            flex: 1 1 400px;
            text-align: center;
        }

        .product-detail-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .product-detail-info {
            flex: 1 1 500px;
        }

        .product-title {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .product-sku {
            font-size: 14px;
            color: #777;
            margin-bottom: 15px;
        }

        .rating-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .stars i {
            color: #fbbf24;
        }

        .review-count {
            color: #555;
            font-size: 14px;
        }

        .product-detail-prices {
            margin: 20px 0;
        }

        .original-price {
            text-decoration: line-through;
            color: #999;
            margin-right: 10px;
            font-size: 18px;
        }

        .discount-price {
            font-size: 24px;
            font-weight: bold;
            color: #e63946;
        }

        .discount-percent {
            background-color: #e63946;
            color: #fff;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 5px;
            margin-left: 10px;
        }

        .product-detail-description p {
            font-size: 16px;
            line-height: 1.6;
            color: #444;
        }

        .product-meta {
            margin-top: 25px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .meta-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
        }

        .meta-label {
            color: #555;
            font-weight: 500;
        }

        .meta-value {
            color: #333;
        }

        .product-sizes {
            margin: 25px 0;
        }

        .product-sizes h4 {
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
        }

        .size-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .size-circle {
            position: relative;
            cursor: pointer;
        }

        .size-circle input[type="radio"] {
            display: none;
        }

        .size-circle span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #f0f0f0;
            color: #333;
            font-weight: 500;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .size-circle input[type="radio"]:checked + span {
            background-color: #222;
            color: #fff;
            border-color: #000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.15);
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .product-detail-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="back">
            <a href="../index.php"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <div class="product-detail-container">
            <div class="product-detail-image">
                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-detail-info">
                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="product-sku">SKU: <?php echo strtoupper(substr(md5($product['id']), 0, 8)); ?></div>
                
                <div class="rating-container">
                    <div class="stars">
                        <?php
                        $full_stars = floor($average_rating);
                        $half_star = ($average_rating - $full_stars) >= 0.5;
                        $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                        for ($i = 0; $i < $full_stars; $i++) echo '<i class="fas fa-star"></i>';
                        if ($half_star) echo '<i class="fas fa-star-half-alt"></i>';
                        for ($i = 0; $i < $empty_stars; $i++) echo '<i class="far fa-star"></i>';
                        ?>
                    </div>
                    <div class="review-count">
                        <?php echo count($reviews); ?> review<?php echo count($reviews) !== 1 ? 's' : ''; ?>
                    </div>
                </div>

                <div class="product-detail-prices">
                    <?php if ($product['discount_price'] < $product['original_price'] && $product['discount_price'] > 0): ?>
                        <span class="original-price">₹<?php echo number_format($product['original_price'], 2); ?></span>
                        <span class="discount-price">₹<?php echo number_format($product['discount_price'], 2); ?></span>
                        <span class="discount-percent">SAVE <?php echo number_format($product['discount_percent'], 0); ?>%</span>
                    <?php else: ?>
                        <span class="discount-price">₹<?php echo number_format($product['original_price'], 2); ?></span>
                    <?php endif; ?>
                </div>

                <div class="product-sizes">
                    <h4>Select Size:</h4>
                    <div class="size-options">
                        <?php 
                        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                        foreach ($sizes as $size): ?>
                            <label class="size-circle">
                                <input type="radio" name="size" value="<?php echo $size; ?>">
                                <span><?php echo $size; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="product-detail-description">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Availability:</span>
                        <span class="meta-value">In Stock (<?php echo rand(10, 50); ?> units)</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Material:</span>
                        <span class="meta-value">100% Premium Cotton</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Care Instructions:</span>
                        <span class="meta-value">Machine wash cold, tumble dry low</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
