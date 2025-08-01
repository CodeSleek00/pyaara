<?php
include 'db_connect.php';

$order_id = $_GET['order_id'] ?? '';
$payment_id = $_GET['payment_id'] ?? '';

if (empty($order_id) {
    header("Location: ../index.php");
    exit();
}

// Get order details
$stmt = $conn->prepare("SELECT o.*, ro.razorpay_order_id, ro.payment_id, ro.amount 
                       FROM orders o 
                       JOIN razorpay_orders ro ON o.order_id = ro.merchant_order_id
                       WHERE o.order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// Get order items
$stmt_items = $conn->prepare("SELECT oi.*, p.name, p.image 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.id 
                            WHERE oi.order_id = (SELECT id FROM orders WHERE order_id = ?)");
$stmt_items->bind_param("s", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
$stmt_items->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Payment</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .thank-you-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .thank-you-icon {
            font-size: 80px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .order-details {
            margin: 30px 0;
            text-align: left;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .order-items {
            margin-top: 20px;
        }
        .order-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #ddd;
        }
        .order-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 5px;
        }
        .order-item-details {
            flex-grow: 1;
        }
        .payment-details {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn-container {
            margin-top: 30px;
        }
        .btn {
            padding: 12px 25px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thank-you-container">
            <div class="thank-you-icon">✓</div>
            <h1>Thank You for Your Payment!</h1>
            <p>Your order has been successfully placed and payment has been received.</p>
            
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
                <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($payment_id); ?></p>
                <p><strong>Amount Paid:</strong> ₹<?php echo number_format($order['amount'], 2); ?></p>
                <p><strong>Payment Method:</strong> Razorpay</p>
                <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                
                <div class="order-items">
                    <h4>Order Items:</h4>
                    <?php foreach ($items as $item): ?>
                        <div class="order-item">
                            <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="order-item-details">
                                <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p>Size: <?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?> | Qty: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                            </div>
                            <div>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="payment-details">
                <h4>Payment Successful</h4>
                <p>Your payment of ₹<?php echo number_format($order['amount'], 2); ?> has been processed successfully.</p>
                <p>A confirmation email has been sent to your registered email address.</p>
            </div>
            
            <div class="btn-container">
                <a href="index.php" class="btn">Continue Shopping</a>
                <a href="order_history.php" class="btn">View Order History</a>
            </div>
        </div>
    </div>
</body>
</html>