<?php
include 'db_connect.php';

// --- VERY BASIC ADMIN AUTHENTICATION (PLACEHOLDER) ---
// In a real application, you'd have a login page and check session/user ID.
// For this example, anyone can access it. REMOVE THIS FOR PRODUCTION!
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header("Location: admin_login.php"); // Redirect to a login page
//     exit();
// }
// -----------------------------------------------------

$message = '';
$message_type = '';

// Handle order status update
if (isset($_POST['update_order_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $conn->real_escape_string($_POST['new_status']);

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute()) {
        $message = "Order status updated successfully!";
        $message_type = "success";
    } else {
        $message = "Error updating order status: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Display messages passed via GET after redirects (if any, though POST is used here)
if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['message']);
    $message_type = htmlspecialchars($_GET['type']);
}

// Fetch all orders for delivery management
$orders = [];
$result_orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
if ($result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Manager</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific styles for Delivery Manager if needed */
        .status-dot {
            height: 12px;
            width: 12px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .status-Pending { background-color: #ffc107; } /* Orange */
        .status-AwaitingPayment { background-color: #6c757d; } /* Gray */
        .status-Confirmed { background-color: #17a2b8; } /* Info Blue */
        .status-Shipped { background-color: #007bff; } /* Primary Blue */
        .status-Delivered { background-color: #28a745; } /* Green */
        .status-Cancelled { background-color: #dc3545; } /* Red */
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="index.php">My Awesome Store</a></h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                    <li><a href="delivery_manager.php">Delivery Manager</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Delivery Manager</h2>
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="admin-section">
            <h3>Manage Order Delivery Statuses</h3>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                        <th>Current Status</th>
                        <th>Update Status</th>
                        <th>View Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['phone_number']); ?></td>
                                <td>$<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($order['order_date']))); ?></td>
                                <td>
                                    <span class="status-dot status-<?php echo str_replace(' ', '', htmlspecialchars($order['status'])); ?>"></span>
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </td>
                                <td>
                                    <form action="delivery_manager.php" method="post" class="status-form">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="new_status" onchange="this.form.submit()">
                                            <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Awaiting Payment" <?php echo ($order['status'] == 'Awaiting Payment') ? 'selected' : ''; ?>>Awaiting Payment</option>
                                            <option value="Confirmed" <?php echo ($order['status'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="Shipped" <?php echo ($order['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="Delivered" <?php echo ($order['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <noscript><input type="submit" name="update_order_status" value="Update" class="btn-action"></noscript>
                                    </form>
                                </td>
                                <td>
                                    <a href="order_confirmation.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>" class="btn-action">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8">No orders placed yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> My Awesome Store. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php $conn->close(); ?>