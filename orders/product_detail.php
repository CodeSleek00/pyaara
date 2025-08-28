<?php
include  require_once 'db_connect.php';

$product = null;
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
}

if (!$product) {
    $_SESSION['message'] = "Product not found.";
    $_SESSION['message_type'] = "error";
    header("Location: index.php");
    exit();
}

// Determine the price to display
$display_price = ($product['discount_price'] < $product['original_price'] && $product['discount_price'] > 0)
    ? $product['discount_price']
    : $product['original_price'];

// Fetch related products
$related_products = [];
$stmt_related = $conn->prepare("SELECT id, name, image, original_price, discount_price, discount_percent FROM products WHERE id != ? ORDER BY RAND() LIMIT 3");
$stmt_related->bind_param("i", $product_id);
$stmt_related->execute();
$result_related = $stmt_related->get_result();
while ($row_related = $result_related->fetch_assoc()) {
    $related_products[] = $row_related;
}
$stmt_related->close();

// Fetch reviews from database
$reviews = [];
$stmt_reviews = $conn->prepare("SELECT * FROM product_reviews WHERE product_id = ? ORDER BY created_at DESC");
$stmt_reviews->bind_param("i", $product_id);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();
while ($row_review = $result_reviews->fetch_assoc()) {
    $reviews[] = $row_review;
}
$stmt_reviews->close();

// Calculate average rating
$average_rating = 0;
if (count($reviews) > 0) {
    $total_rating = 0;
    foreach ($reviews as $review) {
        $total_rating += $review['rating'];
    }
    $average_rating = round($total_rating / count($reviews), 1);
}

$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/Pyaara Circle.png">
    <link rel="apple-touch-icon" href="../images/Pyaara Circle.png">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
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
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }
        
        body {
            background-color: var(--primary-white);
            color: var(--dark-gray);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: var(--primary-red);
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .back-btn:hover {
            color: var(--dark-gray);
            transform: translateX(-3px);
        }
        
        /* Message */
        .message {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .message.success {
            background-color: rgba(38, 166, 91, 0.1);
            color: #26A65B;
            border-left: 3px solid #26A65B;
        }
        
        .message.error {
            background-color: rgba(230, 57, 70, 0.1);
            color: var(--primary-red);
            border-left: 3px solid var(--primary-red);
        }
        
        /* Product Hero Section */
        .product-hero {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin: 30px 0;
            background-color: var(--primary-white);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .product-gallery {
            flex: 1 1 45%;
            min-width: 300px;
        }
        
        .main-image {
            width: 100%;
            height: auto;
            object-fit: fill;
            border-radius: var(--border-radius);
            background: var(--light-gray);
            padding: 20px;
            margin-bottom: 15px;
        }
        
        .thumbnail-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .thumbnail {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: var(--transition);
        }
        
        .thumbnail:hover, .thumbnail.active {
            border-color: var(--primary-red);
        }
        
        .product-info {
            flex: 1 1 45%;
            min-width: 300px;
        }
        
        .product-title {
            font-size: 2rem;
            margin-bottom: 12px;
            color: var(--dark-gray);
            font-weight: 700;
            line-height: 1.2;
        }
        
        .product-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .product-sku {
            background-color: var(--light-gray);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--dark-gray);
            font-weight: 500;
        }
        
        .rating-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            background-color: rgba(255, 209, 102, 0.2);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .rating-badge i {
            color: var(--primary-yellow);
        }
        
        .price-container {
            margin: 25px 0;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .current-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-red);
        }
        
        .original-price {
            font-size: 1.2rem;
            color: var(--dark-gray);
            opacity: 0.5;
            text-decoration: line-through;
        }
        
        .discount-badge {
            background-color: var(--primary-yellow);
            color: var(--dark-gray);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        a {
            text-decoration: none;
        }
        .product-description {
            margin: 25px 0;
            color: #555;
            line-height: 1.8;
            font-size: 1rem;
        }
        
        /* Product Options */
        .product-options {
            margin: 30px 0;
        }
        
        .option-group {
            margin-bottom: 20px;
        }
        
        .option-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }
        
        .size-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .size-option {
            position: relative;
        }
        
        .size-option input {
            position: absolute;
            opacity: 0;
        }
        
        .size-option label {
            display: block;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .size-option input:checked + label {
            border-color: var(--primary-red);
            background-color: rgba(230, 57, 70, 0.05);
            color: var(--primary-red);
        }
        
        .quantity-selector {
            display: flex;
            width: auto;
            align-items: center;
            max-width: 120px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .quantity-btn {
            width: 36px;
            height: 36px;
            background-color: var(--light-gray);
            border: none;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-gray);
            transition: var(--transition);
        }
        
        .quantity-btn:hover {
            background-color: var(--primary-red);
            color: var(--primary-white);
        }
        
        .quantity-input {
            width: 50px;
            height: 36px;
            text-align: center;
            border: none;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        /* Product Actions */
        .product-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 14px 28px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            min-width: 180px;
        }
        
        .btn-primary {
            background-color: var(--primary-red);
            color: var(--primary-white);
            box-shadow: 0 4px 12px rgba(230, 57, 70, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(230, 57, 70, 0.3);
        }
        
        .btn-secondary {
            background-color: var(--primary-white);
            color: var(--primary-red);
            border: 1px solid var(--primary-red);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-red);
            color: var(--primary-white);
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .wishlist-btn {
            background-color: transparent;
            border: none;
            font-size: 1.4rem;
            color: #ddd;
            cursor: pointer;
            transition: var(--transition);
            margin-left: 10px;
        }
        
        .wishlist-btn:hover, .wishlist-btn.active {
            color: var(--primary-red);
        }
        
        /* Product Tabs */
        .product-tabs {
            margin: 50px 0;
            background-color: var(--primary-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .tabs-header {
            display: flex;
            border-bottom: 1px solid #eee;
            background-color: var(--light-gray);
            flex-wrap: wrap;
        }
        
        .tab-btn {
            padding: 14px 24px;
            background: none;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--dark-gray);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        
        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background-color: var(--primary-red);
            transition: var(--transition);
        }
        
        .tab-btn.active {
            color: var(--primary-red);
        }
        
        .tab-btn.active::after {
            width: 100%;
        }
        
        .tab-content {
            display: none;
            padding: 25px;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .tab-content h3 {
            margin-bottom: 18px;
            color: var(--dark-gray);
            font-size: 1.4rem;
            font-weight: 700;
            position: relative;
            padding-bottom: 8px;
        }
        
        .tab-content h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-yellow);
        }
        
        .tab-content ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        
        .tab-content li {
            margin-bottom: 8px;
        }
        
        /* Related Products */
        .related-products {
            margin: 60px 0;
        }
        
        .section-title {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 40px;
            color: var(--dark-gray);
            position: relative;
            font-weight: 700;
        }
        
        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--primary-red);
            margin: 15px auto 0;
            border-radius: 2px;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background-color: var(--primary-white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .product-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: var(--primary-yellow);
            color: var(--dark-gray);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }
        
        .product-card-image {
            width: 100%;
            height: auto;
            object-fit: contain;
            background-color: var(--light-gray);
            padding: 15px;
        }
        
        .product-card-content {
            padding: 18px;
        }
        
        .product-card-title {
            font-size: 1.05rem;
            margin-bottom: 8px;
            color: var(--dark-gray);
            font-weight: 600;
            line-height: 1.4;
        }
        
        .product-card-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        
        .product-card-current {
            font-weight: 700;
            color: var(--primary-red);
            font-size: 1.1rem;
        }
        
        .product-card-original {
            font-size: 0.85rem;
            color: var(--dark-gray);
            opacity: 0.6;
            text-decoration: line-through;
        }
        
        /* Reviews Section */
        .reviews-section {
            margin: 60px 0;
        }
        
        .review-summary-card {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            background-color: var(--primary-white);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 40px;
        }
        
        .rating-overview {
            flex: 1;
            min-width: 220px;
            text-align: center;
            padding: 15px;
        }
        
        .average-rating {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary-red);
            line-height: 1;
            margin-bottom: 8px;
        }
        
        .rating-stars {
            color: var(--primary-yellow);
            font-size: 1.3rem;
            margin-bottom: 12px;
        }
        
        .total-reviews {
            color: var(--dark-gray);
            opacity: 0.7;
            font-size: 0.85rem;
        }
        
        .rating-details {
            flex: 2;
            min-width: 280px;
            padding: 15px;
        }
        
        .rating-bar {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .rating-label {
            width: 80px;
            font-size: 0.85rem;
            color: var(--dark-gray);
        }
        
        .rating-stars-small {
            width: 70px;
            color: var(--primary-yellow);
            font-size: 0.8rem;
        }
        
        .rating-progress {
            flex: 1;
            height: 6px;
            background-color: #eee;
            border-radius: 3px;
            margin: 0 12px;
            overflow: hidden;
        }
        
        .rating-progress-fill {
            height: 100%;
            background-color: var(--primary-red);
        }
        
        .rating-count {
            width: 30px;
            text-align: right;
            font-size: 0.85rem;
            color: var(--dark-gray);
        }
        
        .reviews-list {
            display: grid;
            gap: 20px;
        }
        
        .review-card {
            background-color: var(--primary-white);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .review-author {
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }
        
        .review-date {
            color: var(--dark-gray);
            opacity: 0.6;
            font-size: 0.8rem;
        }
        
        .review-rating {
            color: var(--primary-yellow);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .review-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark-gray);
            font-size: 1rem;
        }
        
        .review-text {
            color: var(--dark-gray);
            opacity: 0.8;
            line-height: 1.7;
            font-size: 0.9rem;
        }
        
        /* Review Form */
        .review-form-card {
            background-color: var(--primary-white);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-top: 40px;
        }
        
        .form-title {
            font-size: 1.4rem;
            margin-bottom: 18px;
            color: var(--dark-gray);
            position: relative;
            padding-bottom: 8px;
            font-weight: 700;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-yellow);
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary-red);
            outline: none;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .rating-select {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .rating-select i {
            font-size: 1.4rem;
            color: #ddd;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .rating-select i:hover, .rating-select i.active {
            color: var(--primary-yellow);
        }
        
        .submit-btn {
            background-color: var(--primary-red);
            color: var(--primary-white);
            padding: 12px 28px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(230, 57, 70, 0.2);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(230, 57, 70, 0.3);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .product-hero {
                flex-direction: column;
            }
            
            .main-image {
                height: auto;
            }
        }
        
        @media (max-width: 768px) {
            .product-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .review-summary-card {
                flex-direction: column;
            }
            
            .rating-overview, .rating-details {
                min-width: 100%;
            }
            
            .product-title {
                font-size: 1.7rem;
            }
            
            .current-price {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .container {
                padding: 15px;
            }
            
            .product-hero, .product-tabs, .review-summary-card {
                padding: 20px;
            }
            
            .main-image {
                height: auto;
                
            }
            
            .section-title {
                font-size: 1.5rem;
                margin-bottom: 30px;
            }
            
            .tab-btn {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
            
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <a href="javascript:history.back()"><i class="fa fa-arrow-left"></i> Go Back</a>
        
        <!-- Product Hero Section -->
        <div class="product-hero">
            <div class="product-gallery">
                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-image">
                
            </div>
            
            <div class="product-info">
                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="product-meta">
                    <span class="product-sku">SKU: <?php echo strtoupper(substr(md5($product['id']), 0, 8)); ?></span>
                    <div class="rating-badge">
                        <i class="fas fa-star"></i>
                        <?php echo $average_rating; ?>
                    </div>
                    <span class="review-count">(<?php echo count($reviews); ?> reviews)</span>
                </div>
                
                <div class="price-container">
                    <?php if ($product['discount_price'] < $product['original_price'] && $product['discount_price'] > 0): ?>
                        <span class="current-price">₹<?php echo htmlspecialchars(number_format($product['discount_price'], 2)); ?></span>
                        <span class="original-price">₹<?php echo htmlspecialchars(number_format($product['original_price'], 2)); ?></span>
                        <span class="discount-badge">Save <?php echo htmlspecialchars(number_format($product['discount_percent'], 0)); ?>%</span>
                    <?php else: ?>
                        <span class="current-price">₹<?php echo htmlspecialchars(number_format($product['original_price'], 2)); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <form action="process_product_action.php" method="post" id="productActionForm">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="product-options">
                        <div class="option-group">
                            <label class="option-label">Size:</label>
                            <div class="size-options">
                                <div class="size-option">
                                    <input type="radio" id="size-xs" name="size" value="XS">
                                    <label for="size-xs">XS</label>
                                </div>
                                <div class="size-option">
                                    <input type="radio" id="size-s" name="size" value="S">
                                    <label for="size-s">S</label>
                                </div>
                                <div class="size-option">
                                    <input type="radio" id="size-m" name="size" value="M" checked>
                                    <label for="size-m">M</label>
                                </div>
                                <div class="size-option">
                                    <input type="radio" id="size-l" name="size" value="L">
                                    <label for="size-l">L</label>
                                </div>
                                <div class="size-option">
                                    <input type="radio" id="size-xl" name="size" value="XL">
                                    <label for="size-xl">XL</label>
                                </div>
                                <div class="size-option">
                                    <input type="radio" id="size-xxl" name="size" value="XXL">
                                    <label for="size-xxl">XXL</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <label class="option-label">Quantity:</label>
                            <div class="quantity-selector">
                                <button type="button" class="quantity-btn minus">-</button>
                                <input type="number" name="quantity" value="1" min="1" max="10" class="quantity-input">
                                <button type="button" class="quantity-btn plus">+</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="product-actions">
                        <button type="submit" name="action" value="add_to_cart" class="btn btn-primary">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button type="submit" name="action" value="buy_now" class="btn btn-secondary">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="product-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="description">Description</button>
                <button class="tab-btn" data-tab="details">Details</button>
                <button class="tab-btn" data-tab="shipping">Shipping</button>
                <button class="tab-btn" data-tab="reviews">Reviews</button>
            </div>
            
            <div id="description" class="tab-content active">
                <h3>Product Story</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <p>Our premium collection combines exceptional craftsmanship with contemporary design. Each piece is carefully constructed using the finest materials to ensure lasting quality and comfort.</p>
                
                <h3>Key Features</h3>
                <ul>
                    <li>Premium quality materials for lasting durability</li>
                    <li>Thoughtful design with attention to detail</li>
                    <li>Versatile styling for various occasions</li>
                    <li>Ethically sourced and responsibly manufactured</li>
                </ul>
            </div>
            
            <div id="details" class="tab-content">
                <h3>Technical Specifications</h3>
                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Material:</span>
                        <span class="meta-value">100% Premium Organic Cotton</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Fit:</span>
                        <span class="meta-value">Modern Slim Fit</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Neckline:</span>
                        <span class="meta-value">Classic Crew Neck</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Sleeve:</span>
                        <span class="meta-value">Short Sleeve</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Pattern:</span>
                        <span class="meta-value">Solid Color</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Care Instructions:</span>
                        <span class="meta-value">Machine wash cold with like colors. Tumble dry low. Do not bleach. Iron low heat if needed.</span>
                    </div>
                </div>
            </div>
            
            <div id="shipping" class="tab-content">
                <h3>Shipping Information</h3>
                <p>We offer fast and reliable shipping options to get your order to you as quickly as possible.</p>
                
                <h4>Domestic Shipping (India)</h4>
                <ul>
                    <li><strong>Standard Shipping:</strong> 3-5 business days - ₹49 (Free on online payments)</li>
                </ul>
                
               
                <h3>Return Policy</h3>
        
                <h4>Returns & Exchanges</h4>
                <ul>
                    <li>No Return Policy  </li>
                    <li>Only Manual Order Cancellation is Available </li>
                </ul>
            </div>
            
            <div id="reviews" class="tab-content">
                <h3>Customer Experiences</h3>
                <p>See what our customers are saying about this product.</p>
                
                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <div class="review-header">
                                <span class="review-author"><?php echo htmlspecialchars($review['name']); ?></span>
                                <span class="review-date"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                            <div class="review-rating">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $review['rating']) {
                                        echo '<i class="fas fa-star"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <?php if (!empty($review['title'])): ?>
                                <h4 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h4>
                            <?php endif; ?>
                            <div class="review-text">
                                <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Related Products -->
        <div class="related-products">
            <h2 class="section-title">Complete Your Look</h2>
            <div class="product-grid">
                <?php if (!empty($related_products)): ?>
                    <?php foreach ($related_products as $related_item):
                        $related_display_price = ($related_item['discount_price'] < $related_item['original_price'] && $related_item['discount_price'] > 0)
                            ? $related_item['discount_price']
                            : $related_item['original_price'];
                    ?>
                        <a href="product_detail.php?id=<?php echo htmlspecialchars($related_item['id']); ?>" class="product-card">
                            <?php if ($related_item['discount_price'] < $related_item['original_price'] && $related_item['discount_price'] > 0): ?>
                                <span class="product-badge">Save <?php echo htmlspecialchars(number_format($related_item['discount_percent'], 0)); ?>%</span>
                            <?php endif; ?>
                            <img src="uploads/<?php echo htmlspecialchars($related_item['image']); ?>" alt="<?php echo htmlspecialchars($related_item['name']); ?>" class="product-card-image">
                            <div class="product-card-content">
                                <h3 class="product-card-title"><?php echo htmlspecialchars($related_item['name']); ?></h3>
                                <div class="product-card-price">
                                    <span class="product-card-current">₹<?php echo htmlspecialchars(number_format($related_display_price, 2)); ?></span>
                                    <?php if ($related_item['discount_price'] < $related_item['original_price'] && $related_item['discount_price'] > 0): ?>
                                        <span class="product-card-original">₹<?php echo htmlspecialchars(number_format($related_item['original_price'], 2)); ?></span>
                                         
                                    <?php endif; ?>
                                </div><button type="submit" name="action" value="buy_now" class="btn btn-secondary">
                                            <i class="fas fa-bolt"></i> Buy Now
                                        </button>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No related products found.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="reviews-section">
            <h2 class="section-title">Customer Reviews</h2>
            
            <div class="review-summary-card">
                <div class="rating-overview">
                    <div class="average-rating"><?php echo $average_rating; ?></div>
                    <div class="rating-stars">
                        <?php
                        $full_stars = floor($average_rating);
                        $half_star = ($average_rating - $full_stars) >= 0.5;
                        $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                        
                        for ($i = 0; $i < $full_stars; $i++) {
                            echo '<i class="fas fa-star"></i>';
                        }
                        if ($half_star) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        }
                        for ($i = 0; $i < $empty_stars; $i++) {
                            echo '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>
                    <div class="total-reviews">Based on <?php echo count($reviews); ?> review<?php echo count($reviews) !== 1 ? 's' : ''; ?></div>
                </div>
                
                <div class="rating-details">
                    <?php for ($i = 5; $i >= 1; $i--): 
                        $count = 0;
                        foreach ($reviews as $review) {
                            if ($review['rating'] == $i) $count++;
                        }
                        $percentage = count($reviews) > 0 ? ($count / count($reviews)) * 100 : 0;
                    ?>
                        <div class="rating-bar">
                            <div class="rating-label"><?php echo $i; ?> Star</div>
                            <div class="rating-stars-small">
                                <?php for ($j = 0; $j < 5; $j++): ?>
                                    <?php if ($j < $i): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="rating-progress">
                                <div class="rating-progress-fill" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                            <div class="rating-count"><?php echo $count; ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="reviews-list">
                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <div class="review-header">
                                <span class="review-author"><?php echo htmlspecialchars($review['name']); ?></span>
                                <span class="review-date"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                            <div class="review-rating">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $review['rating']) {
                                        echo '<i class="fas fa-star"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <?php if (!empty($review['title'])): ?>
                                <h4 class="review-title"><?php echo htmlspecialchars($review['title']); ?></h4>
                            <?php endif; ?>
                            <div class="review-text">
                                <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
            </div>
            
            <div class="review-form-card">
                <h3 class="form-title">Share Your Experience</h3>
                <form action="submit_review.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="form-group">
                        <label for="review_name" class="form-label">Your Name</label>
                        <input type="text" id="review_name" name="review_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="review_email" class="form-label">Email Address</label>
                        <input type="email" id="review_email" name="review_email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <div class="rating-select" id="rating-select">
                            <i class="fas fa-star" data-rating="1"></i>
                            <i class="fas fa-star" data-rating="2"></i>
                            <i class="fas fa-star" data-rating="3"></i>
                            <i class="fas fa-star" data-rating="4"></i>
                            <i class="fas fa-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" id="review_rating" name="review_rating" value="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="review_title" class="form-label">Review Title</label>
                        <input type="text" id="review_title" name="review_title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="review_comment" class="form-label">Your Review</label>
                        <textarea id="review_comment" name="review_comment" class="form-control form-textarea" required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Submit Review</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Add active class to clicked button and corresponding content
                    button.classList.add('active');
                    const tabId = button.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Quantity selector
            const quantityInput = document.querySelector('.quantity-input');
            const minusBtn = document.querySelector('.quantity-btn.minus');
            const plusBtn = document.querySelector('.quantity-btn.plus');
            
            if (quantityInput && minusBtn && plusBtn) {
                minusBtn.addEventListener('click', () => {
                    let value = parseInt(quantityInput.value);
                    if (value > 1) {
                        quantityInput.value = value - 1;
                    }
                });
                
                plusBtn.addEventListener('click', () => {
                    let value = parseInt(quantityInput.value);
                    if (value < 10) {
                        quantityInput.value = value + 1;
                    }
                });
                
                quantityInput.addEventListener('change', function() {
                    if (this.value < 1) this.value = 1;
                    if (this.value > 10) this.value = 10;
                });
            }
            
            // Rating stars in review form
            const ratingStars = document.querySelectorAll('#rating-select i');
            const ratingInput = document.getElementById('review_rating');
            
            if (ratingStars && ratingInput) {
                ratingStars.forEach(star => {
                    star.addEventListener('click', () => {
                        const rating = parseInt(star.getAttribute('data-rating'));
                        ratingInput.value = rating;
                        
                        // Update star display
                        ratingStars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.add('active');
                                s.classList.remove('far');
                                s.classList.add('fas');
                            } else {
                                s.classList.remove('active');
                                s.classList.remove('fas');
                                s.classList.add('far');
                            }
                        });
                    });
                });
            }
            
            // Thumbnail image switching
            const thumbnails = document.querySelectorAll('.thumbnail');
            const mainImage = document.querySelector('.main-image');
            
            if (thumbnails && mainImage) {
                thumbnails.forEach(thumb => {
                    thumb.addEventListener('click', () => {
                        // Remove active class from all thumbnails
                        thumbnails.forEach(t => t.classList.remove('active'));
                        
                        // Add active class to clicked thumbnail
                        thumb.classList.add('active');
                        
                        // Update main image (in a real scenario, you'd use different image sources)
                        mainImage.src = thumb.src;
                    });
                });
            }
            
            // Wishlist button toggle
            const wishlistBtn = document.querySelector('.wishlist-btn');
            if (wishlistBtn) {
                wishlistBtn.addEventListener('click', function() {
                    this.classList.toggle('active');
                    this.classList.toggle('far');
                    this.classList.toggle('fas');
                    
                    if (this.classList.contains('active')) {
                        // In a real app, you would add to wishlist here
                        console.log('Added to wishlist');
                    } else {
                        // Remove from wishlist
                        console.log('Removed from wishlist');
                    }
                });
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>