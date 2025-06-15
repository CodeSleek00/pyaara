<?php
include 'db_connect.php';

$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'success';
    unset($_SESSION['message'], $_SESSION['message_type']);
}

// Get category filter if selected
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Fetch categories
$categories_result = $conn->query("SELECT id, name FROM categories");

// Fetch products with optional category filtering
$products = [];
$sql = "SELECT id, name, image, original_price, discount_price, discount_percent FROM products";
if ($category_id > 0) {
    $sql .= " WHERE category_id = $category_id";
}
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Awesome Store</title>
    <link rel="stylesheet" href="style.css">
      <style>
            /* General Styles for Layout and Design */
            :root {
                --primary-color: #4CAF50; /* Green */
                --secondary-color: #FFC107; /* Amber */
                --dark-grey: #333;
                --medium-grey: #666;
                --light-grey: #f4f4f4;
                --white: #fff;
                --red: #f44336;
                --yellow: #FFC107;
            }

            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 0;
                background-color: var(--light-grey);
                color: var(--dark-grey);
            }

            .container {
                width: 90%;
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px 0;
            }

            header {
                background-color: var(--dark-grey);
                color: var(--white);
                padding: 15px 0;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }

            header h1 {
                margin: 0;
                float: left;
            }

            header h1 a {
                color: var(--white);
                text-decoration: none;
            }

            nav {
                float: right;
            }

            nav ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            nav ul li {
                display: inline-block;
                margin-left: 20px;
            }

            nav ul li a {
                color: var(--white);
                text-decoration: none;
                font-weight: bold;
                padding: 5px 0;
                transition: color 0.3s ease;
            }

            nav ul li a:hover {
                color: var(--secondary-color);
            }

            .clear {
                clear: both;
            }

            footer {
                background-color: var(--dark-grey);
                color: var(--white);
                text-align: center;
                padding: 20px 0;
                margin-top: 40px;
                box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
            }

            /* Message Styles */
            .message {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
                font-weight: bold;
                text-align: center;
            }

            .message.success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .message.error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

            .message.info {
                background-color: #d1ecf1;
                color: #0c5460;
                border: 1px solid #bee5eb;
            }

            /* Product Grid */
            .product-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 30px;
                margin-top: 30px;
            }

            .product-card {
                background-color: var(--white);
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                overflow: hidden;
                text-align: center;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                display: flex;
                flex-direction: column;
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            }

            .product-card img {
                max-width: 100%;
                height: 200px; /* Fixed height for consistency */
                object-fit: contain; /* Ensures entire image is visible */
                border-bottom: 1px solid var(--light-grey);
                padding: 15px;
                background-color: #fcfcfc;
            }

            .product-card h3 {
                font-size: 1.4em;
                margin: 15px 10px 5px;
                color: var(--dark-grey);
                min-height: 50px; /* Ensure consistent height for titles */
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .product-card h3 a {
                text-decoration: none;
                color: inherit;
            }

            .product-card .product-prices {
                padding: 0 15px 15px;
                text-align: center;
            }

            .product-prices .original-price {
                text-decoration: line-through;
                color: var(--medium-grey);
                margin-right: 10px;
                font-size: 0.9em;
            }

            .product-prices .discount-price {
                color: var(--red);
                font-weight: bold;
                font-size: 1.2em;
            }

            .product-prices .discount-percent {
                color: var(--primary-color);
                font-weight: bold;
                font-size: 0.8em;
                margin-left: 5px;
            }
            .product-card .btn {
                display: block;
                width: calc(100% - 30px); /* Adjust for padding */
                padding: 12px 15px;
                margin: 10px 15px 15px;
                background-color: var(--primary-color);
                color: var(--white);
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s ease;
                border: none;
                cursor: pointer;
                font-size: 1em;
                box-sizing: border-box; /* Include padding and border in the element's total width and height */
            }

            .product-card .btn:hover {
                background-color: #45a049;
            }
            /* Specific styles for message box */
            .message {
                padding: 15px;
                margin: 20px 0;
                border-radius: 5px;
                font-weight: bold;
                text-align: center;
            }

            .message.success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .message.error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

            .message.info {
                background-color: #d1ecf1;
                color: #0c5460;
                border: 1px solid #bee5eb;
            }
        </style>
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="index.php">My Awesome Store</a></h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                </ul>
            </nav>
            <div class="clear"></div>
        </div>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="get" style="margin-top: 20px;">
            <label for="category_id"><strong>Filter by Category:</strong></label>
            <select name="category_id" id="category_id" onchange="this.form.submit()">
                <option value="0">All Categories</option>
                <?php while ($cat = $categories_result->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $category_id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <h2 style="margin-top: 30px;">Featured Products</h2>
        <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $row): ?>
                    <div class="product-card">
                        <a href="product_detail.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        </a>
                        <div class="product-prices">
                            <?php if ($row['discount_price'] < $row['original_price'] && $row['discount_price'] > 0): ?>
                                <span class="original-price">$<?php echo htmlspecialchars(number_format($row['original_price'], 2)); ?></span>
                                <span class="discount-price">$<?php echo htmlspecialchars(number_format($row['discount_price'], 2)); ?></span>
                                <span class="discount-percent">(<?php echo htmlspecialchars(number_format($row['discount_percent'], 2)); ?>% Off)</span>
                            <?php else: ?>
                                <span class="discount-price">$<?php echo htmlspecialchars(number_format($row['original_price'], 2)); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="product_detail.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn">View Details / Buy Now</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> My Awesome Store. All rights reserved.</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
