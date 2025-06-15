<?php
// order_tracking.php
include 'db_connect.php';

$order = null;
$error = '';
$order_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = trim($_POST['order_id']);

    if (empty($order_id)) {
        $error = 'Please enter an order ID';
    } else {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $order = $result->fetch_assoc();

            $items_stmt = $conn->prepare("
                SELECT oi.*, p.name, p.image, p.description 
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?
            ");
            $items_stmt->bind_param("i", $order['id']);
            $items_stmt->execute();
            $items_result = $items_stmt->get_result();
            $order_items = $items_result->fetch_all(MYSQLI_ASSOC);
            $order['items'] = $order_items;

            $items_stmt->close();
        } else {
            $error = 'Order not found. Please check your order ID and try again.';
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - Pyaara Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:rgb(255, 0, 0);
            --accent:rgba(255, 228, 121, 0.25);
            --light: #fff;
            --dark: #333;
            --font: 'Outfit', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font);
        }

        body {
            background: var(--light);
            color: var(--dark);
            padding: 30px 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .tracking-box {
            background: var(--light);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 30px;
            border-top: 8px solid var(--primary);
        }

        .tracking-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .tracking-header h1 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .tracking-header p {
            color: #555;
            font-size: 1rem;
        }

        .search-form {
            display: flex;
            gap: 10px;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        .search-form input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-form button {
            background: var(--primary);
            color: var(--light);
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .search-form button:hover {
            background: #e60036;
        }

        .error-message {
            color: var(--primary);
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .order-details {
            margin-top: 30px;
        }

        .order-status {
            background: var(--accent);
            padding: 15px 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .status-badge {
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            color: var(--light);
            background: var(--primary);
            text-transform: uppercase;
        }

        .order-timeline {
            border-left: 3px solid #ddd;
            padding-left: 20px;
            margin-bottom: 40px;
        }

        .timeline-step {
            margin-bottom: 30px;
            position: relative;
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            left: -11px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #bbb;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px var(--accent);
        }

        .timeline-step.active::before {
            background: var(--primary);
        }

        .timeline-content {
            background: #f9f9f9;
            padding: 15px 20px;
            border-radius: 8px;
        }

        .customer-shipping-info {
            margin-top: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            row-gap: 15px;
        }

        .info-label {
            font-weight: 600;
            color: var(--dark);
        }

        .order-items {
            margin-top: 30px;
        }

        .order-item {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
        }

        .item-meta {
            font-size: 0.9rem;
            color: #666;
        }

        .item-price {
            font-weight: bold;
            color: var(--primary);
        }

        .order-summary {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .total-row {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .next-steps {
            margin-top: 40px;
            background: #fef6e0;
            padding: 25px;
            border-radius: 10px;
        }

        .next-steps h3 {
            color: var(--primary);
            margin-bottom: 10px;
        }

        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--light);
        }

        .btn-secondary {
            background: #333;
            color: var(--light);
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }

            .order-status {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .order-item {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tracking-box">
            <div class="tracking-header">
                <h1>Track Your Order</h1>
                <p>Enter your Order ID to view your order status and details.</p>
                <form class="search-form" method="POST">
                    <input type="text" name="order_id" placeholder="ORDER_123456" 
                           value="<?php echo htmlspecialchars($order_id); ?>" required>
                    <button type="submit">Track</button>
                </form>
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
            </div>

            <?php if ($order): ?>
            <div class="order-details">
                <div class="order-status">
                    <div>
                        <strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?><br>
                        <strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                    </div>
                    <div class="status-badge"><?php echo ucfirst($order['status']); ?></div>
                </div>

                <div class="order-timeline">
                    <div class="timeline-step active">
                        <div class="timeline-content">
                            <strong>Order Placed</strong><br>
                            We've received your order.
                        </div>
                    </div>

                    <div class="timeline-step <?php echo in_array($order['status'], ['Shipped', 'Delivered']) ? 'active' : ''; ?>">
                        <div class="timeline-content">
                            <strong>Shipped</strong><br>
                            <?php echo $order['status'] === 'Shipped' ? 'Your order is on the way.' : 'Awaiting shipment.' ?>
                        </div>
                    </div>

                    <div class="timeline-step <?php echo $order['status'] === 'Delivered' ? 'active' : ''; ?>">
                        <div class="timeline-content">
                            <strong>Delivered</strong><br>
                            <?php echo $order['status'] === 'Delivered' ? 'Package delivered.' : 'Not yet delivered.' ?>
                        </div>
                    </div>
                </div>

                <div class="customer-shipping-info">
                    <div class="info-grid">
                        <div class="info-label">Customer:</div>
                        <div><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></div>
                        <div class="info-label">Phone:</div>
                        <div><?php echo htmlspecialchars($order['phone_number']); ?></div>
                        <div class="info-label">Shipping Address:</div>
                        <div><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></div>
                        <div class="info-label">Payment:</div>
                        <div><?php echo htmlspecialchars($order['payment_method']); ?></div>
                    </div>
                </div>

                <h3 style="margin-top: 30px;">Order Items</h3>
                <div class="order-items">
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="order-item">
                            <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" class="item-image">
                            <div class="item-details">
                                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="item-meta">Size: <?php echo $item['size']; ?> | Qty: <?php echo $item['quantity']; ?></div>
                                <div class="item-desc"><?php echo substr(htmlspecialchars($item['description']), 0, 100) . '...'; ?></div>
                            </div>
                            <div class="item-price">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-summary">
                    <div class="summary-row"><span>Subtotal:</span><span>₹<?php echo number_format($order['total_amount'], 2); ?></span></div>
                    <div class="summary-row"><span>Shipping:</span><span>₹0.00</span></div>
                    <div class="summary-row total-row"><span>Total:</span><span>₹<?php echo number_format($order['total_amount'], 2); ?></span></div>
                </div>

                <div class="next-steps">
                    <h3>Need Help?</h3>
                    <p>Contact us at <a href="mailto:support@pyaarastore.com">support@pyaarastore.com</a></p>
                    <div class="action-buttons">
                        <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
                        <a href="orders/contact.php" class="btn btn-secondary">Contact Support</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
