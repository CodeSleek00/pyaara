<?php
include 'db_connect.php';

$order_id_unique = isset($_GET['order_id']) ? $conn->real_escape_string($_GET['order_id']) : '';
$order_details = null;
$order_items = [];

if ($order_id_unique) {
    // Fetch order details
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("s", $order_id_unique);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $order_details = $result->fetch_assoc();

        // Fetch order items
        $stmt_items = $conn->prepare("SELECT oi.quantity, oi.price, oi.size, p.name, p.image
                                     FROM order_items oi
                                     JOIN products p ON oi.product_id = p.id
                                     WHERE oi.order_id = ?");
        $stmt_items->bind_param("i", $order_details['id']);
        $stmt_items->execute();
        $items_result = $stmt_items->get_result();
        while ($row_item = $items_result->fetch_assoc()) {
            $order_items[] = $row_item;
        }
        $stmt_items->close();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Order Confirmation</h2>
        <?php if ($order_details): ?>
            <div class="order-confirmation-details">
                <p class="success">Your order has been placed successfully!</p>
                <h3>Order ID: <strong style="color: var(--yellow);"><?php echo htmlspecialchars($order_details['order_id']); ?></strong></h3>
                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order_details['first_name'] . ' ' . $order_details['last_name']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($order_details['phone_number']); ?></p>
                <p><strong>Shipping Address:</strong> <?php echo nl2br(htmlspecialchars($order_details['shipping_address'])); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order_details['payment_method']); ?></p>
                <p><strong>Total Amount:</strong> <span class="price">₹<?php echo htmlspecialchars(number_format($order_details['total_amount'], 2)); ?></span></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($order_details['order_date']))); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order_details['status']); ?></p>

                <h4>Order Items:</h4>
                <ul class="order-items-list">
                    <?php foreach ($order_items as $item): ?>
                        <li>
                            <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 50px; height: 50px; object-fit: cover; vertical-align: middle; margin-right: 10px;">
                            <?php echo htmlspecialchars($item['name']); ?> (Qty: <?php echo htmlspecialchars($item['quantity']); ?> x ₹<?php echo htmlspecialchars(number_format($item['price'], 2)); ?>)
                            <?php if ($item['size']): ?> - Size: <?php echo htmlspecialchars($item['size']); ?><?php endif; ?>
                            <span style="float:right;">₹<?php echo htmlspecialchars(number_format($item['quantity'] * $item['price'], 2)); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p style="margin-top: 20px;">We will process your order shortly.</p>
                <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="message error">
                <p>Order details not found. Please ensure you have a valid order ID.</p>
                <a href="../index.php" class="btn btn-primary">Go to Home</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>