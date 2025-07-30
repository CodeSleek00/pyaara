<?php
require('../vendor/autoload.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
include 'db_connect.php';
session_start();

$api = new Api('rzp_test_TMaKHOLutXGYTH', 'eyvkr7ljPXve2MnuDjHXZQVE');

$razorpay_order_id = $_POST['razorpay_order_id'];
$razorpay_payment_id = $_POST['razorpay_payment_id'];
$razorpay_signature = $_POST['razorpay_signature'];

$session_order_data = $_SESSION['razorpay_order'] ?? null;

if (!$session_order_data) {
    die("No order session found.");
}

$generated_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, 'eyvkr7ljPXve2MnuDjHXZQVE');

if ($generated_signature !== $razorpay_signature) {
    die("Signature mismatch. Payment failed.");
}

// Insert into orders table
$order_id = $session_order_data['order_id'];
$stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, razorpay_order_id, razorpay_payment_id, razorpay_signature) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssddss",
    $order_id,
    $session_order_data['first_name'],
    $session_order_data['last_name'],
    $session_order_data['phone_number'],
    $session_order_data['shipping_address'],
    $session_order_data['pincode'],
    $session_order_data['payment_method'],
    $session_order_data['total_amount'],
    $razorpay_order_id,
    $razorpay_payment_id,
    $razorpay_signature
);
$stmt->execute();
$order_db_id = $conn->insert_id;

// Insert order items
$stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
foreach ($session_order_data['items'] as $item) {
    $stmt_items->bind_param("iiids", $order_db_id, $item['product_id'], $item['quantity'], $item['price'], $item['size']);
    $stmt_items->execute();
}

// Clear cart
$session_id = session_id();
$conn->query("DELETE FROM cart WHERE user_session_id = '$session_id'");

unset($_SESSION['razorpay_order']);

// Redirect to thank you
header("Location: thank_you.php?order_id=" . $order_id);
exit();
