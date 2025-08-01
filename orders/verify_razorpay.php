<?php
session_start();
require('../vendor/autoload.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

include 'db_connect.php';

// Check if required POST data exists
if (empty($_POST['razorpay_order_id']) || empty($_POST['razorpay_payment_id']) || empty($_POST['razorpay_signature'])) {
    die("Missing payment verification data.");
}

// Initialize Razorpay API
$api = new Api('rzp_test_TMaKHOLutXGYTH', 'eyvkr7ljPXve2MnuDjHXZQVE');

$razorpay_order_id = $_POST['razorpay_order_id'];
$razorpay_payment_id = $_POST['razorpay_payment_id'];
$razorpay_signature = $_POST['razorpay_signature'];

// Verify payment signature
try {
    $attributes = [
        'razorpay_order_id' => $razorpay_order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature' => $razorpay_signature
    ];

    $api->utility->verifyPaymentSignature($attributes);

    // Check if session data exists
    if (!isset($_SESSION['razorpay_order']) || $_SESSION['razorpay_order']['razorpay_order_id'] !== $razorpay_order_id) {
        die("Invalid or expired order session.");
    }

    $orderData = $_SESSION['razorpay_order'];
    $conn->begin_transaction();

    try {
        // Save order to DB
        $stmt = $conn->prepare("INSERT INTO orders 
            (order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, razorpay_payment_id, razorpay_order_id, razorpay_signature) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssssdsss",
            $orderData['order_id'],
            $orderData['first_name'],
            $orderData['last_name'],
            $orderData['phone_number'],
            $orderData['shipping_address'],
            $orderData['pincode'],
            $orderData['payment_method'],
            $orderData['total_amount'],
            $razorpay_payment_id,
            $razorpay_order_id,
            $razorpay_signature
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert order: " . $stmt->error);
        }

        $last_order_id = $conn->insert_id;
        $stmt->close();

        // Insert order items
        $stmt_items = $conn->prepare("INSERT INTO order_items 
            (order_id, product_id, product_name, product_image, quantity, price, size) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        foreach ($orderData['items'] as $item) {
            $stmt_items->bind_param("iissids", 
                $last_order_id,
                $item['product_id'],
                $item['product_name'],
                $item['product_image'],
                $item['quantity'],
                $item['price'],
                $item['size']
            );
            
            if (!$stmt_items->execute()) {
                throw new Exception("Failed to insert order items: " . $stmt_items->error);
            }
        }

        $stmt_items->close();

        // Clear cart
        $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
        $clear_cart_stmt->bind_param("s", session_id());
        
        if (!$clear_cart_stmt->execute()) {
            throw new Exception("Failed to clear cart: " . $clear_cart_stmt->error);
        }

        $clear_cart_stmt->close();

        // Commit transaction
        $conn->commit();

        // Clear session data
        unset($_SESSION['razorpay_order']);

        // Redirect to thank you page
        header("Location: thank_you.php?order_id=" . urlencode($orderData['order_id']));
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Order processing failed: " . $e->getMessage());
        die("Order processing failed. Please contact support.");
    }

} catch(SignatureVerificationError $e) {
    error_log("Payment verification failed: " . $e->getMessage());
    die("Payment verification failed. Please contact support.");
} catch(Exception $e) {
    error_log("Error processing payment: " . $e->getMessage());
    die("An error occurred. Please contact support.");
}

$conn->close();
?>