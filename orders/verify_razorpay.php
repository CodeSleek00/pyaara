<?php
require('../vendor/autoload.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

session_start();
include 'db_connect.php';

// Configuration
$key_id = 'rzp_test_Ox3tDG4PAJscLL'; // Replace with your key
$key_secret = '8y5toKVa5TXJ2zfOUvXaZnPs'; // Replace with your secret
$webhook_secret = 'Pyaara'; // Set this in Razorpay dashboard

$api = new Api($key_id, $key_secret);

// Check if this is a webhook call
if (isset($_SERVER['HTTP_X_RAZORPAY_SIGNATURE'])) {
    // Webhook processing
    $webhook_body = file_get_contents('php://input');
    $received_signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'];
    
    try {
        // Verify webhook signature
        $api->utility->verifyWebhookSignature($webhook_body, $received_signature, $webhook_secret);
        
        $data = json_decode($webhook_body, true);
        $event = $data['event'];
        
        if ($event === 'payment.captured') {
            $payment = $data['payload']['payment']['entity'];
            $payment_id = $payment['id'];
            $order_id = $payment['order_id'];
            $amount = $payment['amount'] / 100; // Convert from paise
            
            // Check if payment already processed
            $stmt = $conn->prepare("SELECT id FROM orders WHERE payment_id = ?");
            $stmt->bind_param("s", $payment_id);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows === 0) {
                // Get Razorpay order details
                $razorpay_order = $api->order->fetch($order_id);
                $merchant_order_id = $razorpay_order->notes['merchant_order_id'] ?? '';
                $user_session_id = $razorpay_order->notes['user_session'] ?? '';
                
                // Try to get session data if available
                $order_data = $_SESSION['razorpay_order'] ?? null;
                
                if ($order_data && $order_data['order_id'] === $merchant_order_id) {
                    // Use session data
                    $first_name = $order_data['first_name'];
                    $last_name = $order_data['last_name'];
                    $phone_number = $order_data['phone_number'];
                    $shipping_address = $order_data['shipping_address'];
                    $items = $order_data['items'];
                } else {
                    // Fallback to basic data
                    $first_name = 'Customer';
                    $last_name = '';
                    $phone_number = $payment['contact'] ?? '';
                    $shipping_address = 'Address not captured';
                    $items = [];
                }
                
                // Create order in database
                $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, payment_method, total_amount, payment_status, razorpay_order_id, payment_id) 
                                      VALUES (?, ?, ?, ?, ?, 'Razorpay', ?, 'paid', ?, ?)");
                $stmt->bind_param("ssssssss", $merchant_order_id, $first_name, $last_name, $phone_number, $shipping_address, $amount, $order_id, $payment_id);
                
                if ($stmt->execute()) {
                    $db_order_id = $stmt->insert_id;
                    
                    // Insert order items if available
                    if (!empty($items)) {
                        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
                        
                        foreach ($items as $item) {
                            $size = $item['size'] ?? '';
                            $stmt_items->bind_param("iiids", $db_order_id, $item['product_id'], $item['quantity'], $item['price'], $size);
                            $stmt_items->execute();
                        }
                    }
                    
                    // Clear cart if we have session info
                    if (!empty($user_session_id)) {
                        $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
                        $clear_cart->bind_param("s", $user_session_id);
                        $clear_cart->execute();
                    }
                }
            }
        }
        
        // Respond to webhook
        header("HTTP/1.1 200 OK");
        exit();
        
    } catch (SignatureVerificationError $e) {
        error_log("Webhook signature verification failed: " . $e->getMessage());
        header("HTTP/1.1 400 Bad Request");
        exit();
    } catch (Exception $e) {
        error_log("Webhook processing error: " . $e->getMessage());
        header("HTTP/1.1 500 Internal Server Error");
        exit();
    }
} 
// Normal payment callback
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';
    $razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
    $razorpay_signature = $_POST['razorpay_signature'] ?? '';
    
    try {
        // Verify payment signature
        $api->utility->verifyPaymentSignature([
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_signature' => $razorpay_signature
        ]);
        
        // Check if payment already processed
        $stmt = $conn->prepare("SELECT id FROM orders WHERE payment_id = ?");
        $stmt->bind_param("s", $razorpay_payment_id);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Payment already processed");
        }
        
        // Verify payment status
        $payment = $api->payment->fetch($razorpay_payment_id);
        if ($payment->status !== 'captured') {
            throw new Exception("Payment not captured. Status: " . $payment->status);
        }
        
        // Get order data from session
        if (!isset($_SESSION['razorpay_order'])) {
            throw new Exception("Session data missing");
        }
        
        $order_data = $_SESSION['razorpay_order'];
        $amount = $payment->amount / 100;
        
        // Verify amount matches
        if (abs($amount - $order_data['total_amount']) > 0.01) {
            throw new Exception("Amount mismatch");
        }
        
        // Create order in database
        $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, payment_method, total_amount, payment_status, razorpay_order_id, payment_id) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, 'paid', ?, ?)");
        $stmt->bind_param("sssssssss", $order_data['order_id'], $order_data['first_name'], $order_data['last_name'], 
                         $order_data['phone_number'], $order_data['shipping_address'], $order_data['payment_method'], 
                         $order_data['total_amount'], $razorpay_order_id, $razorpay_payment_id);
        
        if ($stmt->execute()) {
            $db_order_id = $stmt->insert_id;
            
            // Insert order items
            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
            foreach ($order_data['items'] as $item) {
                $size = $item['size'] ?? '';
                $stmt_items->bind_param("iiids", $db_order_id, $item['product_id'], $item['quantity'], $item['price'], $size);
                $stmt_items->execute();
            }
            
            // Clear session and cart
            unset($_SESSION['razorpay_order']);
            $user_session_id = session_id();
            $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
            $clear_cart->bind_param("s", $user_session_id);
            $clear_cart->execute();
            
            // Redirect to thank you page
            $_SESSION['message'] = "Payment successful! Order #" . $order_data['order_id'];
            $_SESSION['message_type'] = "success";
            header("Location: thank_you.php?order_id=" . $order_data['order_id']);
            exit();
        } else {
            throw new Exception("Database error: " . $stmt->error);
        }
        
    } catch (SignatureVerificationError $e) {
        error_log("Payment signature verification failed: " . $e->getMessage());
        $_SESSION['message'] = "Payment verification failed";
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    } catch (Exception $e) {
        error_log("Order processing error: " . $e->getMessage());
        $_SESSION['message'] = "Order processing failed: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
} else {
    header("Location: checkout.php");
    exit();
}
?>