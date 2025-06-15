<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['status'];

    $allowed = ['Processing', 'Shipped', 'Delivered'];
    if (!in_array($new_status, $allowed)) {
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(['success' => $success]);
}
?>
    