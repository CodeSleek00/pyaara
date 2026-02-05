<?php
require('vendor/autoload.php'); // This loads Razorpay SDK
use Razorpay\Api\Api;

session_start();

// Replace with your actual Razorpay credentials
$key_id = 'rzp_test_SCVeOeMLUaAx1o';
$key_secret = 'u2nE8xINsgaWQcpI63hQKUGC';

$api = new Api($key_id, $key_secret);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';
    $razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
    $razorpay_signature = $_POST['razorpay_signature'] ?? '';

    $attributes = [
        'razorpay_order_id' => $razorpay_order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature' => $razorpay_signature
    ];

    try {
        $api->utility->verifyPaymentSignature($attributes);

        // ✅ Signature is valid — mark order as paid in your DB
        // Example: update orders set status = 'Paid' where order_id = $razorpay_order_id

        $_SESSION['message'] = "Payment verified successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: thank_you.php?order_id=" . $razorpay_order_id);
        exit();

    } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
        $_SESSION['message'] = "Payment verification failed: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
}
