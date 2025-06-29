<?php
session_start();
include 'db_connect.php';
header('Content-Type: application/json');

// no echo, no HTML, just clean logic

try {
    // Sanitize and calculate amount
    $user_session_id = session_id();
    $total = 0;

    $stmt = $conn->prepare("SELECT c.quantity, p.discount_price, p.original_price 
        FROM cart c JOIN products p ON c.product_id = p.id 
        WHERE c.user_session_id = ?");
    $stmt->bind_param("s", $user_session_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $price = ($row['discount_price'] > 0) ? $row['discount_price'] : $row['original_price'];
        $total += $price * $row['quantity'];
    }

    $order_id = 'ORD_' . time() . '_' . bin2hex(random_bytes(3));

    echo json_encode([
        "status" => "razorpay",
        "key" => "rzp_live_pA6jgjncp78sq7",
        "amount" => $total * 100,
        "currency" => "INR",
        "order_id" => $order_id,
        "name" => "Pyaara Store",
        "description" => "Payment for your order"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
