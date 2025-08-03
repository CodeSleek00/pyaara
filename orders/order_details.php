<?php
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT o.order_id, o.order_date, o.total_amount, o.payment_method, 
               oi.quantity, oi.price, oi.size, p.name, p.image 
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
    <title>Order Details | Pyaara</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #d32f2f;
            --primary-dark: #b71c1c;
            --text: #333;
            --light-gray: #f9f9f9;
            --border: #e0e0e0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            line-height: 1.5;
            color: var(--text);
            background-color: var(--light-gray);
            padding: 0;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
        }
        
        .header {
            background-color: var(--primary);
            color: white;
            padding: 16px;
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .back-btn {
            color: white;
            font-size: 20px;
        }
        
        .header-title {
            font-size: 18px;
            font-weight: 500;
        }
        
        .order-card {
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .order-id {
            font-weight: 600;
            font-size: 16px;
        }
        
        .order-status {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .order-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            font-size: 14px;
            margin-bottom: 16px;
        }
        
        .meta-label {
            color: #666;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            padding: 16px;
            background-color: var(--light-gray);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .items-list {
            padding: 0 16px;
        }
        
        .item {
            display: flex;
            gap: 12px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border);
        }
        
        .item:last-child {
            border-bottom: none;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 4px;
            border: 1px solid var(--border);
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 15px;
        }
        
        .item-meta {
            display: flex;
            gap: 12px;
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .item-price {
            font-weight: 600;
            font-size: 15px;
        }
        
        .order-summary {
            padding: 16px;
            background-color: white;
            margin-top: 8px;
            border-top: 1px solid var(--border);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .grand-total {
            font-weight: 700;
            font-size: 16px;
            border-top: 1px solid var(--border);
            margin-top: 8px;
            padding-top: 8px;
        }
        
        .footer {
            padding: 16px;
            background-color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        
        .back-link {
            display: block;
            text-align: center;
            padding: 12px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="order_history.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="header-title">Order Details</div>
        </div>
        
        <div class="order-card">
            <div class="order-header">
                <div class="order-id">Order #<?= htmlspecialchars($items[0]['order_id']) ?></div>
                <div class="order-status">Completed</div>
            </div>
            
            <div class="order-meta">
                <div>
                    <div class="meta-label">Order Date</div>
                    <div><?= date('M j, Y', strtotime($items[0]['order_date'])) ?></div>
                </div>
                <div>
                    <div class="meta-label">Payment</div>
                    <div><?= htmlspecialchars($items[0]['payment_method']) ?></div>
                </div>
                <div>
                    <div class="meta-label">Items</div>
                    <div><?= count($items) ?></div>
                </div>
                <div>
                    <div class="meta-label">Total</div>
                    <div>₹<?= number_format($items[0]['total_amount'], 2) ?></div>
                </div>
            </div>
        </div>
        
        <div class="section-title">
            <i class="fas fa-box-open"></i> Items Ordered
        </div>
        
        <div class="items-list">
            <?php foreach ($items as $item): ?>
                <div class="item">
                    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>" 
                         class="item-image">
                    
                    <div class="item-details">
                        <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="item-meta">
                            <span>Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?></span>
                            <span>Qty: <?= htmlspecialchars($item['quantity']) ?></span>
                        </div>
                        <div class="item-price">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="order-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>₹<?= number_format($subtotal, 2) ?></span>
            </div>
            
            <?php if ($items[0]['payment_method'] === 'COD'): ?>
            <div class="summary-row">
                <span>COD Fee</span>
                <span>₹49.00</span>
            </div>
            <?php endif; ?>
            
            <div class="summary-row grand-total">
                <span>Total Amount</span>
                <span>₹<?= number_format($items[0]['total_amount'], 2) ?></span>
            </div>
        </div>
        
        <div class="footer">
            <a href="order_history.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>
</body>
</html>