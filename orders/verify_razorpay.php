<?php
session_start();
require('../vendor/autoload.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

include 'db_connect.php';

$api = new Api('rzp_live_pA6jgjncp78sq7', 'N7INcRU4l61iijQ2sOjL5YTs');

$razorpay_order_id = $_POST['razorpay_order_id'];
$razorpay_payment_id = $_POST['razorpay_payment_id'];
$razorpay_signature = $_POST['razorpay_signature'];

$attributes = [
    'razorpay_order_id' => $razorpay_order_id,
    'razorpay_payment_id' => $razorpay_payment_id,
    'razorpay_signature' => $razorpay_signature
];

try {
    $api->utility->verifyPaymentSignature($attributes);

    // ✅ Payment is verified
    if (!isset($_SESSION['razorpay_order'])) {
        die("Order details not found in session.");
    }

    $orderData = $_SESSION['razorpay_order'];

    // Save order to DB with pincode field included
    $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, razorpay_payment_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed')");
    $stmt->bind_param("sssssssds",
        $orderData['order_id'],
        $orderData['first_name'],
        $orderData['last_name'],
        $orderData['phone_number'],
        $orderData['shipping_address'],
        $orderData['pincode'], // ✅ Added pincode
        $orderData['payment_method'],
        $orderData['total_amount'],
        $razorpay_payment_id
    );

    if ($stmt->execute()) {
        $last_order_id = $conn->insert_id;

        // Insert order items
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");

        foreach ($orderData['items'] as $item) {
            $stmt_items->bind_param("iiids", $last_order_id, $item['product_id'], $item['quantity'], $item['price'], $item['size']);
            $stmt_items->execute();
        }

        $stmt_items->close();

        // Clear cart
        $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
        $clear_cart_stmt->bind_param("s", session_id());
        $clear_cart_stmt->execute();
        $clear_cart_stmt->close();

        // Clear the razorpay order session data
        unset($_SESSION['razorpay_order']);

        // Set success message
        $_SESSION['message'] = "Payment successful! Your Order ID: " . $orderData['order_id'];
        $_SESSION['message_type'] = "success";

        // ✅ Redirect to thank you page
        header("Location: thank_you.php?order_id=" . $orderData['order_id']);
        exit();
    } else {
        echo "Failed to insert order in DB: " . $stmt->error;
        exit();
    }

    $stmt->close();

} catch(SignatureVerificationError $e) {
    echo "Payment verification failed: " . $e->getMessage();
    // You might want to redirect to an error page here
    $_SESSION['message'] = "Payment verification failed. Please contact support.";
    $_SESSION['message_type'] = "error";
    header("Location: checkout.php");
    exit();
} catch(Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    $_SESSION['message'] = "An error occurred while processing payment. Please contact support.";
    $_SESSION['message_type'] = "error";
    header("Location: checkout.php");
    exit();
}
?>