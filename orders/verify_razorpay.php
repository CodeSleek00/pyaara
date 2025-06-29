<?php
session_start();

// Your Razorpay credentials
$key_secret = 'N7INcRU4l61iijQ2sOjL5YTs';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';
    $razorpay_signature = $_POST['razorpay_signature'] ?? '';

    // Signature string for HMAC
    $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $key_secret);

    if (hash_equals($generated_signature, $razorpay_signature)) {
        // ✅ Payment Verified
        $_SESSION['message'] = "Payment verified successfully!";
        $_SESSION['message_type'] = "success";

        // Redirect to thank you page with order ID
        header("Location: thank_you.php?order_id=" . urlencode($razorpay_order_id));
        exit();
    } else {
        // ❌ Payment Failed
        $_SESSION['message'] = "Payment verification failed!";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
}
?>
