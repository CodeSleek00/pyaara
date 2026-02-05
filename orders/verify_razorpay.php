<?php
session_start();
require('../vendor/autoload.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

include 'db_connect.php';

// --- CONFIGURATION ---
// Ensure these match EXACTLY with your checkout.php test keys
$keyId = 'rzp_test_SCVeOeMLUaAx1o'; 
$keySecret = 'u2nE8xINsgaWQcpI63hQKUGC';

$api = new Api($keyId, $keySecret);

// --- DATA FETCHING ---
// Hum check kar rahe hain ki data GET se aaya hai ya POST se
$razorpay_order_id = $_REQUEST['razorpay_order_id'] ?? '';
$razorpay_payment_id = $_REQUEST['razorpay_payment_id'] ?? '';
$razorpay_signature = $_REQUEST['razorpay_signature'] ?? '';

if (empty($razorpay_order_id) || empty($razorpay_payment_id) || empty($razorpay_signature)) {
    die("Error: Razorpay response data missing. Check if the payment was completed.");
}

$attributes = [
    'razorpay_order_id' => $razorpay_order_id,
    'razorpay_payment_id' => $razorpay_payment_id,
    'razorpay_signature' => $razorpay_signature
];

try {
    // 1. Verify Signature
    $api->utility->verifyPaymentSignature($attributes);

    // 2. Session Validation
    if (!isset($_SESSION['razorpay_order'])) {
        die("Error: Session expired or Order data not found. Please try again.");
    }

    $orderData = $_SESSION['razorpay_order'];
    $formData = $orderData['post_data']; // checkout.php se save kiya hua data

    $conn->begin_transaction();

    // 3. Database Insertion
    // Check your 'orders' table columns: user_id, order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, razorpay_payment_id, payment_status
    $sql = "INSERT INTO orders (user_id, order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, razorpay_payment_id, payment_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed')";
    
    $stmt = $conn->prepare($sql);
    $user_id = $_SESSION['user_id'];
    $payment_method = 'Razorpay';

    $stmt->bind_param("isssssssds",
        $user_id,
        $orderData['order_id'],
        $formData['first_name'],
        $formData['last_name'],
        $formData['phone_number'],
        $formData['shipping_address'],
        $formData['pincode'],
        $payment_method,
        $orderData['total_amount'],
        $razorpay_payment_id
    );

    if ($stmt->execute()) {
        $db_order_id = $conn->insert_id;

        // 4. Insert Items
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");

        foreach ($orderData['items'] as $item) {
            $stmt_items->bind_param("iiids", $db_order_id, $item['product_id'], $item['quantity'], $item['price'], $item['size']);
            $stmt_items->execute();
        }

        // 5. Clear Cart
        $session_id = session_id();
        $clear_sql = "DELETE FROM cart WHERE user_session_id = ?";
        $clear_stmt = $conn->prepare($clear_sql);
        $clear_stmt->bind_param("s", $session_id);
        $clear_stmt->execute();

        $conn->commit();

        // Success - Clean up and Redirect
        unset($_SESSION['razorpay_order']);
        header("Location: thank_you.php?order_id=" . urlencode($orderData['order_id']));
        exit();

    } else {
        throw new Exception("Database Insert Failed: " . $stmt->error);
    }

} catch(SignatureVerificationError $e) {
    // Ye tab hota hai jab Secret Key galat ho ya data raste mein badal gaya ho
    echo "Verification Failed: " . $e->getMessage();
} catch(Exception $e) {
    $conn->rollback();
    echo "General Error: " . $e->getMessage();
}