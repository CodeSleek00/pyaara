<?php
require 'db_connect.php'; // Make sure this file handles session_start()

// Check login and order ID
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch order details (only if it belongs to the user)
$sql = "SELECT o.order_id, o.order_date, o.total_amount, o.payment_method, 
               oi.quantity, oi.price, oi.size,
               p.name, p.image 
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.id = ? AND o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $_GET['id'], $_SESSION['user_id']);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($items)) {
    header("Location: order_history.php");
    exit();
}

// Calculate subtotal
$subtotal = array_reduce($items, function($carry, $item) {
    return $carry + ($item['price'] * $item['quantity']);
}, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/Pyaara Circle.png">
    <link rel="apple-touch-icon" href="../images/Pyaara Circle.png">
    <title>Order Details | Pyaara</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
             font-family: "Outfit", sans-serif;
            line-height: 1.6;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #d32f2f;
            margin-bottom: 20px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .order-meta {
            margin-bottom: 20px;
        }
        .items-container {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }
        .item {
            display: flex;
            gap: 20px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        .item img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-price {
            font-weight: bold;
        }
        .order-totals {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .grand-total {
            font-weight: bold;
            font-size: 1.1em;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 15px;
            background: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="order-header">
        <h1><i class="fas fa-receipt"></i> Order #<?= htmlspecialchars($items[0]['order_id']) ?></h1>
        <div class="order-status">Status: Completed</div>
    </div>
    
    <div class="order-meta">
        <p><strong>Order Date:</strong> <?= date('F j, Y, g:i a', strtotime($items[0]['order_date'])) ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($items[0]['payment_method']) ?></p>
    </div>
    
    <h2><i class="fas fa-box-open"></i> Order Items</h2>
    <div class="items-container">
        <?php foreach ($items as $item): ?>
            <div class="item">
                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <div class="item-details">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p>Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?></p>
                    <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                </div>
                <div class="item-price">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="order-totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>₹<?= number_format($subtotal, 2) ?></span>
        </div>
        <?php if ($items[0]['payment_method'] === 'COD'): ?>
        <div class="total-row">
            <span>COD Fee:</span>
            <span>₹49.00</span>
        </div>
        <?php endif; ?>
        <div class="total-row grand-total">
            <span>Total:</span>
            <span>₹<?= number_format($items[0]['total_amount'], 2) ?></span>
        </div>
    </div>
    
    <a href="order_history.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Orders</a>
</body>
</html>