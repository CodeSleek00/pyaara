<?php
session_start();
require 'db_connect.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch orders for the logged-in user
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <link rel="stylesheet" href="css/order_history.css">
</head>
<body>
    <h1>Your Orders</h1>
    <?php if (empty($orders)): ?>
        <p>No orders found.</p>
    <?php else: ?>
        <div class="orders">
            <?php foreach ($orders as $order): ?>
                <div class="order">
                    <p>Order #<?= $order['order_id'] ?></p>
                    <p>Date: <?= $order['order_date'] ?></p>
                    <p>Total: ₹<?= $order['total_amount'] ?></p>
                    <a href="order_details.php?id=<?= $order['id'] ?>">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>