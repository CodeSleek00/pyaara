<?php
require('../vendor/autoload.php');
use Razorpay\Api\Api;
include 'db_connect.php';

// Check session status before starting
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify Razorpay payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = ['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("Payment verification failed: Missing parameters");
        }
    }

    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];
    
    $api = new Api('rzp_test_TMaKHOLutXGYTH', 'eyvkr7ljPXve2MnuDjHXZQVE');
    
    try {
        $attributes = [
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_signature' => $razorpay_signature
        ];
        
        $api->utility->verifyPaymentSignature($attributes);
        
        // Payment is successful and verified
        if (!isset($_SESSION['razorpay_order'])) {
            die("Session data missing. Please restart checkout process.");
        }

        $razorpay_order = $_SESSION['razorpay_order'];
        $order_id = $razorpay_order['order_id'];
        
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Save to main orders table
            $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'paid')");
            
            // Create variables to bind (not passing array elements directly)
            $firstName = $razorpay_order['first_name'];
            $lastName = $razorpay_order['last_name'];
            $phoneNumber = $razorpay_order['phone_number'];
            $shippingAddress = $razorpay_order['shipping_address'];
            $pincode = $razorpay_order['pincode'];
            $paymentMethod = $razorpay_order['payment_method'];
            $totalAmount = $razorpay_order['total_amount'];
            
            $stmt->bind_param("sssssssd", 
                $order_id,
                $firstName,
                $lastName,
                $phoneNumber,
                $shippingAddress,
                $pincode,
                $paymentMethod,
                $totalAmount
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Error saving order: " . $stmt->error);
            }
            
            $last_order_id = $conn->insert_id;
            $stmt->close();

            // Save order items
            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
            
            foreach ($razorpay_order['items'] as $item) {
                // Create variables for item binding
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $size = $item['size'];
                
                $stmt_items->bind_param("iiids", 
                    $last_order_id,
                    $productId,
                    $quantity,
                    $price,
                    $size
                );
                
                if (!$stmt_items->execute()) {
                    throw new Exception("Error saving order items: " . $stmt_items->error);
                }
            }
            $stmt_items->close();

            // Save to razorpay_orders table
            $stmt_razorpay = $conn->prepare("INSERT INTO razorpay_orders (razorpay_order_id, merchant_order_id, payment_id, amount, currency, status) VALUES (?, ?, ?, ?, ?, 'success')");
            
            // Create variables for binding
            $amount = $razorpay_order['total_amount'];
            $currency = 'INR';
            $status = 'success';
            
            $stmt_razorpay->bind_param("sssds", 
                $razorpay_order_id,
                $order_id,
                $razorpay_payment_id,
                $amount,
                $currency
            );
            
            if (!$stmt_razorpay->execute()) {
                throw new Exception("Error saving Razorpay order: " . $stmt_razorpay->error);
            }
            $stmt_razorpay->close();

            // Save to razorpay_order_items
            foreach ($razorpay_order['items'] as $item) {
                // Get product details
                $product_stmt = $conn->prepare("SELECT name, image FROM products WHERE id = ?");
                $product_stmt->bind_param("i", $item['product_id']);
                $product_stmt->execute();
                $product_result = $product_stmt->get_result();
                $product = $product_result->fetch_assoc();
                $product_stmt->close();
                
                // Create variables for binding
                $productName = $product['name'];
                $productImage = $product['image'];
                $itemSize = $item['size'];
                $itemQuantity = $item['quantity'];
                $itemPrice = $item['price'];
                
                $stmt_razorpay_items = $conn->prepare("INSERT INTO razorpay_order_items (razorpay_order_id, product_id, product_name, product_image, size, quantity, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
                
                $stmt_razorpay_items->bind_param("sisssid", 
                    $razorpay_order_id,
                    $item['product_id'],
                    $productName,
                    $productImage,
                    $itemSize,
                    $itemQuantity,
                    $itemPrice
                );
                
                if (!$stmt_razorpay_items->execute()) {
                    throw new Exception("Error saving Razorpay order items: " . $stmt_razorpay_items->error);
                }
                $stmt_razorpay_items->close();
            }

            // Clear cart
            $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
            $sessionId = session_id();
            $clear_cart_stmt->bind_param("s", $sessionId);
            
            if (!$clear_cart_stmt->execute()) {
                throw new Exception("Error clearing cart: " . $clear_cart_stmt->error);
            }
            $clear_cart_stmt->close();

            // Commit transaction
            $conn->commit();

            // Clear session data
            unset($_SESSION['razorpay_order']);

            // Redirect to thank you page
            header("Location: thankyou_razorpay.php?order_id=" . urlencode($order_id) . "&payment_id=" . urlencode($razorpay_payment_id));
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            error_log("Order Processing Error: " . $e->getMessage());
            die("Error processing your order. Please contact support.");
        }

    } catch (Exception $e) {
        error_log("Payment Verification Error: " . $e->getMessage());
        die("Payment verification failed: " . $e->getMessage());
    }
} else {
    die("Invalid request method");
}