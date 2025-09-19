<?php
session_start();
include 'db_connect.php';

// ----------------- ADMIN PASSWORD (CHANGE BEFORE PRODUCTION) -----------------
// Change this to a strong password before deploying. For better security,
// store password in environment variable or config file outside web root.
$ADMIN_PASSWORD = 'admin123';
// ---------------------------------------------------------------------------

// Handle logout (use ?action=logout)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: admin_dashboard.php");
    exit();
}

// If not logged in, handle login form submission or show login form
$login_error = '';
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
        $entered = $_POST['admin_password'];
        if ($entered === $ADMIN_PASSWORD) {
            // Successful login
            $_SESSION['admin_logged_in'] = true;
            // Redirect to avoid form resubmission
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $login_error = "Invalid password. Try again.";
        }
    }

    // Show login form and stop further execution
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login</title>
        <style>
            :root {
                --primary-color: #4a6bff;
                --border-radius: 6px;
                --box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            }
            body {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                margin: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #f5f7fa;
            }
            .login-card {
                background: white;
                padding: 30px;
                border-radius: var(--border-radius);
                width: 360px;
                box-shadow: var(--box-shadow);
                text-align: center;
            }
            .login-card h2 {
                margin-bottom: 18px;
                color: var(--primary-color);
            }
            .login-card input[type="password"] {
                width: 100%;
                padding: 10px;
                border-radius: 6px;
                border: 1px solid #ddd;
                margin-bottom: 12px;
                font-size: 1rem;
            }
            .login-card button {
                background: var(--primary-color);
                color: white;
                padding: 10px 14px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 1rem;
                width: 100%;
            }
            .error {
                margin-bottom: 12px;
                color: #721c24;
                background: #f8d7da;
                padding: 8px;
                border-radius: 6px;
                border: 1px solid #f5c6cb;
            }
            .note {
                margin-top: 12px;
                font-size: 0.9rem;
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class="login-card" role="main" aria-labelledby="loginHeading">
            <h2 id="loginHeading">Admin Login</h2>
            <?php if ($login_error): ?>
                <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
            <?php endif; ?>
            <form method="post" action="admin_dashboard.php">
                <input type="password" name="admin_password" placeholder="Enter admin password" required autofocus>
                <button type="submit">Login</button>
            </form>
            <p class="note">Change <code>$ADMIN_PASSWORD</code> in this file before production.</p>
        </div>
    </body>
    </html>
    <?php
    $conn->close();
    exit();
}

// ------------------------- (From here user is authenticated) -------------------------

$message = '';
$message_type = '';

// Handle product deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete_product' && isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $message = "Product deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Error deleting product: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
    // Redirect to clear GET parameters
    header("Location: admin_dashboard.php?message=" . urlencode($message) . "&type=" . urlencode($message_type));
    exit();
}

// Handle order status update
if (isset($_POST['update_order_status']) || (isset($_POST['new_status']) && isset($_POST['order_id']))) {
    // allow both cases (JS auto-submit via select or explicit submit)
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

    // Redirect to show message and avoid resubmission
    header("Location: admin_dashboard.php?message=" . urlencode($message) . "&type=" . urlencode($message_type));
    exit();
}

// Display messages passed via GET after redirects
if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['message']);
    $message_type = htmlspecialchars($_GET['type']);
}

// Fetch Dashboard Stats
$total_sales = 0;
$stmt_sales = $conn->query("SELECT SUM(total_amount) AS total_sales FROM orders WHERE status = 'Confirmed' OR status = 'Shipped' OR status = 'Delivered'");
if ($stmt_sales) {
    $row_sales = $stmt_sales->fetch_assoc();
    $total_sales = $row_sales['total_sales'] ?? 0;
}

$pending_orders_count = 0;
$stmt_pending = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'Pending' OR status = 'Awaiting Payment'");
if ($stmt_pending) {
    $row_pending = $stmt_pending->fetch_assoc();
    $pending_orders_count = $row_pending['count'] ?? 0;
}

$total_products_count = 0;
$stmt_products = $conn->query("SELECT COUNT(*) AS count FROM products");
if ($stmt_products) {
    $row_products = $stmt_products->fetch_assoc();
    $total_products_count = $row_products['count'] ?? 0;
}

// Fetch all products
$products = [];
$result_products = $conn->query("SELECT * FROM products ORDER BY id DESC");
if ($result_products && $result_products->num_rows > 0) {
    while ($row = $result_products->fetch_assoc()) {
        $products[] = $row;
    }
}

// Fetch all orders
$orders = [];
$result_orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
if ($result_orders && $result_orders->num_rows > 0) {
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
    <title>Admin Dashboard</title>
    
</head><style>
    :root {
        --primary-color: #4a6bff;
        --secondary-color: #f8f9fa;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-radius: 5px;
        --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f7fa;
        color: #333;
        line-height: 1.6;
    }

    .container {
        width: 95%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    header {
        background-color: var(--primary-color);
        color: white;
        padding: 15px 0;
        box-shadow: var(--box-shadow);
        margin-bottom: 30px;
    }

    header nav ul {
        display: flex;
        list-style: none;
        align-items: center;
        gap: 12px;
    }

    header nav ul li a {
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: var(--border-radius);
        transition: background-color 0.3s;
    }

    header nav ul li a:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    h2 {
        margin-bottom: 20px;
        color: var(--dark-color);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 10px;
    }

    h3 {
        margin-bottom: 10px;
        color: var(--dark-color);
    }

    .message {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: var(--border-radius);
    }

    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .admin-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background-color: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        text-align: center;
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card h3 {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .stat-card p {
        font-size: 1.8rem;
        font-weight: bold;
        margin-top: 10px;
    }

    .admin-section {
        background-color: white;
        padding: 25px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th {
        background-color: var(--primary-color);
        color: white;
        padding: 12px;
        text-align: left;
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    .btn-primary {
        display: inline-block;
        background-color: var(--primary-color);
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: var(--border-radius);
        transition: background-color 0.3s;
        margin-bottom: 15px;
    }

    .btn-primary:hover {
        background-color: #3a56d4;
    }

    .btn-action {
        display: inline-block;
        padding: 6px 12px;
        text-decoration: none;
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-action.red {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-action.red:hover {
        background-color: #c82333;
    }

    select {
        padding: 8px;
        border-radius: var(--border-radius);
        border: 1px solid #ddd;
        background-color: white;
        cursor: pointer;
    }

    select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    img {
        max-width: 100%;
        height: auto;
    }

    footer {
        background-color: var(--dark-color);
        color: white;
        text-align: center;
        padding: 20px 0;
        margin-top: 30px;
    }

    .update_order_status {
        background-color: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 20px;
    }

    .update_order_status a {
        display: inline-block;
        background-color: var(--info-color);
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: var(--border-radius);
        transition: background-color 0.3s;
    }

    .update_order_status a:hover {
        background-color: #138496;
    }

    @media (max-width: 768px) {
        .admin-stats {
            grid-template-columns: 1fr;
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><a href="files.html"> access all the files</a></li>
                    <li><a href="add_product.php">Add Product</a></li>
                    <li><a href="admin_dashboard.php?action=logout" style="background:#ff6b6b;padding:8px 12px;border-radius:6px;">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Admin Dashboard</h2>
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
            <div class="update_order_status">
            <h2>Update Order Status</h2>
            <a href="order_dashboard.php">Update the Delivery</a>
            </div>
            <div class="update_order_status">
            <h2>Update The User Details</h2>
            <a href="../admin_users.php">Update User Details</a>
            </h2>
            </div>
        <div class="admin-stats">
            <div class="stat-card">
                <h3>Total Sales</h3>
                <p>₹<?php echo htmlspecialchars(number_format($total_sales, 2)); ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Orders</h3>
                <p><?php echo htmlspecialchars($pending_orders_count); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Products</h3>
                <p><?php echo htmlspecialchars($total_products_count); ?></p>
            </div>
        </div>

        <div class="admin-section">
            <h2>Products Management</h2>
            <p><a href="add_product.php" class="btn-primary">Add New Product</a></p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Original Price</th>
                        <th>Discount Price</th>
                        <th>Discount %</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['id']); ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                <td>₹<?php echo htmlspecialchars(number_format($product['original_price'], 2)); ?></td>
                                <td>₹<?php echo htmlspecialchars(number_format($product['discount_price'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($product['discount_percent'], 2)); ?>%</td>
                                <td>
                                    <a href="admin_dashboard.php?action=delete_product&id=<?php echo $product['id']; ?>" class="btn-action red" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No products added yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-section">
            <h2>Orders Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Pincode</th> 
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['pincode']); ?></td>
                                <td>₹<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($order['order_date']))); ?></td>
                                <td>
                                    <form action="admin_dashboard.php" method="post">
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
