<?php
require 'db_connect.php'; // Make sure this file handles session_start()

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch orders for the logged-in user
$sql = "SELECT o.id, o.order_id, o.order_date, o.total_amount, 
               COUNT(oi.id) as item_count
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.user_id = ?
        GROUP BY o.id
        ORDER BY o.order_date DESC";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order History | Pyaara</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #d32f2f;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .orders-container {
            display: grid;
            gap: 20px;
        }
        .order-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .order-id {
            font-weight: 600;
            color: #d32f2f;
        }
        .order-date {
            color: #666;
        }
        .order-total {
            font-weight: bold;
        }
        .view-details {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 15px;
            background: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .empty-orders {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1><i class="fas fa-history"></i> Your Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <i class="fas fa-box-open"></i>
            <p>You haven't placed any orders yet.</p>
            <a href="../products.php" class="view-details">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="orders-container">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">Order #<?= htmlspecialchars($order['order_id']) ?></span>
                        <span class="order-date"><?= date('M d, Y', strtotime($order['order_date'])) ?></span>
                    </div>
                    <div class="order-body">
                        <p><?= $order['item_count'] ?> item<?= $order['item_count'] != 1 ? 's' : '' ?></p>
                        <p class="order-total">Total: ₹<?= number_format($order['total_amount'], 2) ?></p>
                        <a href="order_details.php?id=<?= $order['id'] ?>" class="view-details">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>