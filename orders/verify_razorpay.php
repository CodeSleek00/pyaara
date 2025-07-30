<?php
require('../vendor/autoload.php'); // Razorpay SDK
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

session_start();
include '../db.php'; // 🟡 Make sure this connects to your DB

$key_id = 'rzp_test_Ox3tDG4PAJscLL';
$key_secret = '8y5toKVa5TXJ2zfOUvXaZnPs';

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
        // ✅ Step 1: Verify Signature
        $api->utility->verifyPaymentSignature($attributes);

        // ✅ Step 2: Fetch user and cart info from session
        $user_id = $_SESSION['user_id'] ?? null;
        $cart = $_SESSION['cart'] ?? [];

        if (!$user_id || empty($cart)) {
            throw new Exception("Session expired or cart is empty.");
        }

        // ✅ Step 3: Save order to database
        $order_date = date("Y-m-d H:i:s");
        $status = 'Paid';
        $total_amount = $_SESSION['total_amount']; // Set this during checkout before Razorpay call

        // Insert order into `orders` table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, razorpay_order_id, payment_id, payment_status, total_amount, order_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $razorpay_order_id, $razorpay_payment_id, $status, $total_amount, $order_date);
        $stmt->execute();
        $order_id = $stmt->insert_id; // DB Order ID

        // ✅ Step 4: Save order items (from cart)
        foreach ($cart as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt2->execute();
        }

        // ✅ Step 5: Clear cart
        unset($_SESSION['cart']);
        unset($_SESSION['total_amount']);

        $_SESSION['message'] = "Payment successful and order placed!";
        $_SESSION['message_type'] = "success";
        header("Location: thank_you.php?order_id=" . $order_id);
        exit();

    } catch (SignatureVerificationError $e) {
        $_SESSION['message'] = "Payment verification failed: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['message'] = "Something went wrong: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
}
?>
