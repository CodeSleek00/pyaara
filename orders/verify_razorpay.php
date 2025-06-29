<?php
session_start();
include 'db_connect.php';

// Razorpay secret
$secret = "YOUR_SECRET_KEY";

// Collect post data
$razorpay_order_id = $_POST['razorpay_order_id'];
$razorpay_payment_id = $_POST['razorpay_payment_id'];
$razorpay_signature = $_POST['razorpay_signature'];

// Generate expected signature
$generated_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $secret);

// Verify signature
if ($generated_signature === $razorpay_signature) {
    // Payment success - store order in DB
    echo "<h2>Payment Successful</h2><p>Order ID: $razorpay_order_id</p>";
    // Save to orders table, etc.
} else {
    echo "<h2>Payment Failed!</h2><p>Signature verification failed.</p>";
}
?>
