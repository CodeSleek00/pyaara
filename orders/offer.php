<?php
include 'db_connect.php';

$page = basename($_SERVER['PHP_SELF']);
$products = $conn->query("SELECT * FROM products WHERE discount_percent > 30");

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
    <title>Exclusive Offers</title>
    <link rel="icon" type="image/png" href="../images/Pyaara Circle.png">
    <link rel="apple-touch-icon" href="../images/Pyaara Circle.png">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--primary-white);
            color: var(--dark-gray);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .back-button {
            margin-bottom: 20px;
        }

        .back-button a {
            display: inline-flex;
            align-items: center;
            color: var(--primary-red);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .back-button a i {
            margin-right: 8px;
        }

        .back-button a:hover {
            text-decoration: underline;
            transform: translateX(-2px);
        }

        .page-header {
            text-align: center;
            margin: 30px 0 40px;
        }

        .page-title {
            font-size: 2.4rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark-gray);
            position: relative;
            display: inline-block;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 90px;
            height: 4px;
            background-color: var(--primary-yellow);
            border-radius: 2px;
        }

        .page-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-top: 15px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .product-card {
            background: var(--primary-white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .product-image-container {
            width: 100%;
            padding-top: 120%;
            position: relative;
            overflow: hidden;
            background-color: var(--light-gray);
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .discount-tag {
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: var(--primary-yellow);
            color: var(--dark-gray);
            padding: 5px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 20px;
        }

        .product-info {
            padding: 18px;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 600;
            height: 44px;
            overflow: hidden;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .price-container {
            margin-bottom: 15px;
        }

        .current-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-red);
        }

        .original-price {
            font-size: 0.85rem;
            color: #888;
            text-decoration: line-through;
            margin-left: 6px;
        }

        .discount-percent {
            font-size: 0.85rem;
            color: var(--primary-yellow);
            margin-left: 10px;
            font-weight: 600;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 10px 15px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary-red);
            color: var(--primary-white);
            border: none;
        }

        .btn-secondary {
            background-color: var(--primary-white);
            color: var(--primary-red);
            border: 1px solid var(--primary-red);
        }

        .btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            font-size: 1.1rem;
            color: #666;
        }

        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-title {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .container {
                padding: 15px;
            }

            .product-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-button">
            <a href="../index.php"><i class="fa fa-arrow-left"></i> Back to Home</a>
        </div>

        <div class="page-header">
            <h1 class="page-title">Top Offers</h1>
            <p class="page-subtitle">Get best deals with 30%+ discount!</p>
        </div>

        <div class="products-grid">
            <?php if (!empty($products_array)): ?>
                <?php foreach ($products_array as $row): ?>
                    <div class="product-card">
                        <div class="product-image-container">
                            <a href="product_detail.php?id=<?php echo $row['id']; ?>">
                                <img class="product-image" src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            </a>
                            <div class="discount-tag"><?php echo htmlspecialchars(number_format($row['discount_percent'], 0)); ?>% OFF</div>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                            <div class="price-container">
                                <span class="current-price">₹<?php echo number_format($row['discount_price'], 2); ?></span>
                                <span class="original-price">₹<?php echo number_format($row['original_price'], 2); ?></span>
                                <span class="discount-percent">Save <?php echo number_format($row['discount_percent'], 0); ?>%</span>
                            </div>
                            <div class="product-actions">
                                <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Buy Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <p>No offers available currently. Please check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>