<?php
include 'db_connect.php';

// Get all Razorpay orders for the current user (you'll need to implement user authentication)
$stmt = $conn->prepare("SELECT ro.* FROM razorpay_orders ro 
                       JOIN orders o ON ro.merchant_order_id = o.order_id
                       WHERE o.phone_number = ? ORDER BY ro.created_at DESC");
$stmt->bind_param("s", $_SESSION['user_phone']); // Adjust based on your auth system
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!-- Display the orders in a table -->
<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['merchant_order_id']; ?></td>
                <td>₹<?php echo number_format($order['amount'], 2); ?></td>
                <td><?php echo ucfirst($order['status']); ?></td>
                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                <td><a href="view_razorpay_order.php?id=<?php echo $order['razorpay_order_id']; ?>">View Details</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>