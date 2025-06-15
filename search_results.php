<?php
include 'orders/db_connect.php';

$search = $conn->real_escape_string($_GET['query'] ?? '');
$sort = $_GET['sort'] ?? '';

$orderClause = '';
switch ($sort) {
    case 'price_asc':
        $orderClause = 'ORDER BY discount_price ASC';
        break;
    case 'price_desc':
        $orderClause = 'ORDER BY discount_price DESC';
        break;
    case 'name_asc':
        $orderClause = 'ORDER BY name ASC';
        break;
    case 'name_desc':
        $orderClause = 'ORDER BY name DESC';
        break;
    default:
        $orderClause = 'ORDER BY id DESC';
        break;
}

$sql = "SELECT * FROM products WHERE name LIKE '%$search%' $orderClause";
$products = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - Pyaara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
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
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--light-gray);
            color: var(--dark-gray);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: auto;
            padding: 20px;
        }

        /* Header Styles */
        .mobile-header-logo-only {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: var(--primary-white);
            box-shadow: var(--box-shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .mobile-header-logo-only .logo {
            height: 40px;
            width: auto;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .mobile-header-logo-only .search-icon {
            margin-left: auto;
            cursor: pointer;
        }

        /* Back Button */
        .back-button a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-red);
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
            padding: 10px 15px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background: rgba(230, 57, 70, 0.1);
        }

        .back-button a:hover {
            background: rgba(230, 57, 70, 0.2);
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin: 30px 0;
        }

        .page-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
            color: var(--dark-gray);
        }

        .page-title::after {
            content: '';
            width: 100px;
            height: 4px;
            background: var(--primary-red);
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* Search Form */
        .search-sort {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 40px;
            background: var(--primary-white);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .search-box {
            display: flex;
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-sort input {
            flex: 1;
            padding: 12px 50px 12px 15px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-sort input:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 2px rgba(230, 57, 70, 0.2);
        }

        .search-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--dark-gray);
            cursor: pointer;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .search-button:hover {
            color: var(--primary-red);
        }

        .search-sort select {
            padding: 12px 15px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            font-size: 1rem;
            background-color: var(--primary-white);
            cursor: pointer;
            transition: var(--transition);
            min-width: 200px;
        }

        .search-sort select:focus {
            outline: none;
            border-color: var(--primary-red);
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: var(--primary-white);
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: var(--transition);
            box-shadow: var(--box-shadow);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }

        .product-image-container {
            position: relative;
            width: 100%;
            height: 280px;
            background-color: #f8f8f8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .product-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            mix-blend-mode: multiply;
            transition: var(--transition);
        }

        .product-card:hover .product-image {
            transform: scale(1.08);
        }

        .discount-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary-yellow);
            color: var(--dark-gray);
            font-weight: bold;
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 20px;
            z-index: 1;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .product-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 12px;
            color: var(--dark-gray);
            line-height: 1.4;
        }

        .price-container {
            margin: 10px 0 18px;
        }

        .current-price {
            color: var(--primary-red);
            font-weight: bold;
            font-size: 1.3rem;
        }

        .original-price {
            text-decoration: line-through;
            margin-left: 8px;
            color: #777;
            font-size: 0.95rem;
        }

        .discount-percent {
            display: block;
            color: #4CAF50;
            font-size: 0.9rem;
            margin-top: 6px;
            font-weight: 600;
        }

        .product-actions {
            margin-top: auto;
        }

        .product-actions a {
            display: block;
            text-align: center;
            padding: 12px 15px;
            background: var(--primary-red);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-size: 1rem;
        }

        .product-actions a:hover {
            background: #c5303a;
            transform: translateY(-2px);
        }

        .no-products {
            text-align: center;
            font-size: 1.2rem;
            padding: 80px 0;
            color: #666;
            grid-column: 1 / -1;
            background: var(--primary-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .no-products p:first-child {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--dark-gray);
            font-weight: 600;
        }

        .no-products .search-again {
            margin-top: 20px;
        }

        .no-products .search-again a {
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
        }

        .no-products .search-again a:hover {
            text-decoration: underline;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 20px;
            }
            
            .product-image-container {
                height: auto;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .search-sort {
                padding: 15px;
            }
        }

        @media (max-width: 600px) {
            .search-sort {
                flex-direction: column;
                gap: 12px;
            }
            
            .search-box,
            .search-sort input,
            .search-sort select {
                width: 100%;
            }

            .mobile-header-logo-only {
                padding: 12px 15px;
            }

            .mobile-header-logo-only .logo {
                height: 35px;
            }
        }

        @media (max-width: 480px) {

            .page-title {
                font-size: 1.6rem;
            }

            .product-name {
                font-size: 1.1rem;
            }

            .current-price {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<header class="mobile-header-logo-only">
    <a href="index.php" style="display: flex; align-items: center; text-decoration:none;">
        <i class="fas fa-arrow-left" style="font-size: 1.2rem; color: var(--dark-gray);"></i>
    </a>
    <img src="images/Pyaara Site Svg.svg" alt="Pyaara Logo" class="logo">
    <div class="search-icon">
        <i class="fas fa-search" style="font-size: 1.2rem; color: var(--dark-gray);"></i>
    </div>
</header>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Search Results</h1>
    </div>

    <form class="search-sort" method="GET" action="search_results.php">
        <div class="search-box">
            <input type="text" name="query" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search products..." aria-label="Search products">
            <button type="submit" class="search-button" aria-label="Search">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <select name="sort" onchange="this.form.submit()" aria-label="Sort products">
            <option value="">Sort By</option>
            <option value="price_asc" <?php if ($sort === 'price_asc') echo 'selected'; ?>>Price: Low to High</option>
            <option value="price_desc" <?php if ($sort === 'price_desc') echo 'selected'; ?>>Price: High to Low</option>
            <option value="name_asc" <?php if ($sort === 'name_asc') echo 'selected'; ?>>Name: A to Z</option>
            <option value="name_desc" <?php if ($sort === 'name_desc') echo 'selected'; ?>>Name: Z to A</option>
        </select>
    </form>

    <div class="products-grid">
        <?php if (!empty($products) && $products->num_rows > 0): ?>
            <?php while ($row = $products->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <a href="orders/product_detail.php?id=<?php echo $row['id']; ?>">
                            <img class="product-image" src="orders/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        </a>
                        <?php if ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0): ?>
                            <div class="discount-tag"><?php echo htmlspecialchars(number_format($row['discount_percent'], 0)); ?>% OFF</div>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
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
                            <a href="orders/product_detail.php?id=<?php echo $row['id']; ?>">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-products">
                <p>No products found matching your search</p>
                <p>We couldn't find any products for "<?php echo htmlspecialchars($search); ?>"</p>
                <div class="search-again">
                    <a href="search_results.php">Try a different search</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>