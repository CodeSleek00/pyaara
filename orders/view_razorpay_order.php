<?php
include 'db_connect.php';

$razorpay_order_id = $_GET['id'] ?? '';

if (empty($razorpay_order_id)) {
    header("Location: order_history.php");
    exit();
}

// Get order details
$stmt = $conn->prepare("SELECT ro.*, o.* FROM razorpay_orders ro
                      JOIN orders o ON ro.merchant_order_id = o.order_id
                      WHERE ro.razorpay_order_id = ?");
$stmt->bind_param("s", $razorpay_order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// Get order items
$stmt_items = $conn->prepare("SELECT * FROM razorpay_order_items 
                            WHERE razorpay_order_id = ?");
$stmt_items->bind_param("s", $razorpay_order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
$stmt_items->close();

$conn->close();
?>

<!-- Display order details similar to thankyou_razorpay.php -->