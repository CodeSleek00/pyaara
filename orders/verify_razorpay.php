<?php
session_start();
require('../vendor/autoload.php');
include 'db_connect.php';
use Razorpay\Api\Api;

$api = new Api('rzp_test_TMaKHOLutXGYTH', 'eyvkr7ljPXve2MnuDjHXZQVE');

$success = true;
$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false) {
    try {
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
        
        // Update order status to 'completed'
        $order_id = $_SESSION['merchant_order_id'];
        $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE order_id = ?");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $stmt->close();
        
        // Clear session variables
        unset($_SESSION['razorpay_order_id']);
        unset($_SESSION['merchant_order_id']);
        
        // Redirect to thank you page
        $_SESSION['message'] = "Payment successful! Your Order ID: " . $order_id;
        $_SESSION['message_type'] = "success";
        header("Location: thank_you.php?order_id=" . $order_id);
        exit();
    } catch(SignatureVerificationError $e) {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

// If payment fails
$_SESSION['message'] = $error;
$_SESSION['message_type'] = "error";
header("Location: checkout.php");
exit();
?>