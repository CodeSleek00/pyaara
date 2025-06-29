<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

$user_session_id = session_id();
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';
$shipping_address = $_POST['shipping_address'] ?? '';

$stmt = $conn->prepare("SELECT c.quantity, p.discount_price, p.original_price 
    FROM cart c JOIN products p ON c.product_id = p.id 
    WHERE c.user_session_id = ?");
$stmt->bind_param("s", $user_session_id);
$stmt->execute();
$res = $stmt->get_result();

$total = 0;
while ($row = $res->fetch_assoc()) {
    $price = ($row['discount_price'] > 0) ? $row['discount_price'] : $row['original_price'];
    $total += $price * $row['quantity'];
}
$stmt->close();

$order_id = 'ORD_' . time() . '_' . bin2hex(random_bytes(3));

echo json_encode([
    "status" => "razorpay",
    "order_id" => $order_id,
    "amount" => $total * 100,
    "currency" => "INR",
    "key" => "rzp_live_pA6jgjncp78sq7",
    "name" => "Pyaara",
    "description" => "Order Payment",
]);
exit;
