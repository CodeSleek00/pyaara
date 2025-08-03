<?php
require 'db_connect.php';

// Check login and order ID
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch order details
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #d32f2f;
            --primary-dark: #b71c1c;
            --text: #333;
            --light-gray: #f5f5f5;
            --border: #e0e0e0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: #fff;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 30px;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        
        .order-title {
            color: var(--primary);
            font-size: 28px;
            font-weight: 600;
        }
        
        .order-status {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .order-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            background: var(--light-gray);
            padding: 20px;
            border-radius: 8px;
        }
        
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        
        .meta-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .meta-value {
            font-weight: 500;
        }
        
        .section-title {
            font-size: 20px;
            margin: 30px 0 20px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .items-container {
            display: grid;
            gap: 15px;
        }
        
        .item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            padding: 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .item-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 4px;
        }
        
        .item-details {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .item-name {
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .item-meta {
            display: flex;
            gap: 15px;
            font-size: 14px;
            color: #666;
        }
        
        .item-price {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-end;
            font-weight: 600;
            min-width: 100px;
        }
        
        .order-summary {
            margin-top: 30px;
            background: var(--light-gray);
            padding: 20px;
            border-radius: 8px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        
        .grand-total {
            font-weight: 700;
            font-size: 18px;
            border-top: 1px solid var(--border);
            margin-top: 10px;
            padding-top: 10px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
            padding: 12px 20px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.2s;
        }
        
        .back-link:hover {
            background: var(--primary-dark);
        }
        
        @media (max-width: 768px) {
            .item {
                grid-template-columns: 80px 1fr;
            }
            
            .item-price {
                grid-column: 2;
                align-items: flex-start;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="order-header">
            <h1 class="order-title">
                <i class="fas fa-receipt"></i> 
                Order #<?= htmlspecialchars($items[0]['order_id']) ?>
            </h1>
            <span class="order-status">
                <i class="fas fa-check-circle"></i> Completed
            </span>
        </div>
        
        <div class="order-meta">
            <div class="meta-item">
                <span class="meta-label">Order Date</span>
                <span class="meta-value"><?= date('F j, Y, g:i a', strtotime($items[0]['order_date'])) ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Payment Method</span>
                <span class="meta-value"><?= htmlspecialchars($items[0]['payment_method']) ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Items</span>
                <span class="meta-value"><?= count($items) ?></span>
            </div>
        </div>
        
        <h2 class="section-title">
            <i class="fas fa-box-open"></i> Order Items
        </h2>
        
        <div class="items-container">
            <?php foreach ($items as $item): ?>
                <div class="item">
                    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>" 
                         class="item-image">
                    
                    <div class="item-details">
                        <h3 class="item-name"><?= htmlspecialchars($item['name']) ?></h3>
                        <div class="item-meta">
                            <span>Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?></span>
                            <span>Qty: <?= htmlspecialchars($item['quantity']) ?></span>
                        </div>
                    </div>
                    
                    <div class="item-price">
                        ₹<?= number_format($item['price'] * $item['quantity'], 2) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="order-summary">
            <h2 class="section-title">
                <i class="fas fa-file-invoice-dollar"></i> Order Summary
            </h2>
            
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
        
        <a href="order_history.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</body>
</html>