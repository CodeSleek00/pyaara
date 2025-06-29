<?php
require('vendor/autoload.php'); // Razorpay SDK
use Razorpay\Api\Api;

session_start();
include 'db_connect.php'; // Your database connection file

// Replace with your Razorpay Key ID and Secret
$key_id = 'rzp_live_pA6jgjncp78sq7';
$key_secret = 'N7INcRU4l61iijQ2sOjL5YTs';

$api = new Api($key_id, $key_secret);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';
    $razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
    $razorpay_signature = $_POST['razorpay_signature'] ?? '';

    // ✅ Basic validation
    if (!$razorpay_payment_id || !$razorpay_order_id || !$razorpay_signature) {
        $_SESSION['message'] = "Invalid payment data.";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }

    $attributes = [
        'razorpay_order_id'   => $razorpay_order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature'  => $razorpay_signature
    ];

    try {
        // ✅ Verify the signature
        $api->utility->verifyPaymentSignature($attributes);

        // ✅ Fetch original merchant_order_id from Razorpay order notes
        $order = $api->order->fetch($razorpay_order_id);
        $merchant_order_id = $order['notes']['merchant_order_id'] ?? '';

        if (!$merchant_order_id) {
            throw new Exception("Merchant order ID not found in Razorpay notes.");
        }

        // ✅ Update your orders table and mark payment as successful
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'Paid', razorpay_payment_id = ? WHERE order_id = ?");
        $stmt->bind_param("ss", $razorpay_payment_id, $merchant_order_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        // ✅ Redirect to Thank You page
        $_SESSION['message'] = "Payment verified successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: thank_you.php?order_id=" . urlencode($merchant_order_id));
        exit();

    } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
        // ❌ Razorpay signature verification failed
        $_SESSION['message'] = "Payment verification failed: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    } catch (Exception $e) {
        // ❌ Any other error (e.g., merchant_order_id missing)
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
}
?>
