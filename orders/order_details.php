<?php
session_start();
require 'db_connect.php';

// Check login and order ID
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch order details (only if it belongs to the user)
$sql = "SELECT o.*, oi.*, p.name, p.image 
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
</head>
<body>
    <h1>Order #<?= $items[0]['order_id'] ?></h1>
    <div class="items">
        <?php foreach ($items as $item): ?>
            <div class="item">
                <img src="uploads/<?= $item['image'] ?>" width="100">
                <p><?= $item['name'] ?></p>
                <p>₹<?= $item['price'] ?> x <?= $item['quantity'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="order_history.php">Back to Orders</a>
</body>
</html>