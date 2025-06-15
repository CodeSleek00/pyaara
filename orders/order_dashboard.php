<?php
include 'db_connect.php';

// Ensure 'created_at' exists in the 'orders' table, or replace with 'id' if not
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC"); // Changed from 'created_at' to 'id'
$orders = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Status Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .dashboard-table th, .dashboard-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .status-button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-processing { background-color: #2196F3; color: white; }
        .btn-shipped    { background-color: #FF9800; color: white; }
        .btn-delivered  { background-color: #4CAF50; color: white; }
        .btn-disabled   { background-color: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Status Dashboard</h1>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Placed</th>
                    <th>Shipped</th>
                    <th>Delivered</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php 
                        $status = $order['status'] ?? 'Processing';
                        // Determine current level
                        $status_levels = ['Processing' => 1, 'Shipped' => 2, 'Delivered' => 3];
                        $current_level = $status_levels[$status] ?? 1;
                    ?>
                    <tr data-order-id="<?= $order['id'] ?>">
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                        <td><?= date('Y-m-d', strtotime($order['created_at'] ?? 'now')) ?></td>

                        <td>
                            <button class="status-button btn-processing <?= $current_level >= 1 ? '' : 'btn-disabled' ?>" disabled>✔</button>
                        </td>
                        <td>
                            <button class="status-button btn-shipped <?= $current_level >= 2 ? '' : ($current_level == 1 ? '' : 'btn-disabled') ?>" data-status="Shipped" <?= $current_level >= 2 ? 'disabled' : '' ?>>
                                <?= $current_level >= 2 ? '✔' : 'Mark Shipped' ?>
                            </button>
                        </td>
                        <td>
                            <button class="status-button btn-delivered <?= $current_level == 3 ? '' : ($current_level == 2 ? '' : 'btn-disabled') ?>" data-status="Delivered" <?= $current_level == 3 ? 'disabled' : '' ?>>
                                <?= $current_level == 3 ? '✔' : 'Mark Delivered' ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<script>
    document.querySelectorAll('.status-button').forEach(button => {
        button.addEventListener('click', function () {
            if (this.classList.contains('btn-disabled')) return;

            const row = this.closest('tr');
            const orderId = row.dataset.orderId;
            const newStatus = this.dataset.status;

            fetch('update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `order_id=${orderId}&status=${newStatus}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(`Order status updated to ${newStatus}`);
                    location.reload();
                } else {
                    alert('Failed to update status.');
                }
            });
        });
    });
</script>
</body>
</html>
