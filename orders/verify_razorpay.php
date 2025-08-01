<?php
require('../vendor/autoload.php');
use Razorpay\Api\Api;
include 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';
    $razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
    $razorpay_signature = $_POST['razorpay_signature'] ?? '';

    if (empty($razorpay_payment_id) || empty($razorpay_order_id) || empty($razorpay_signature)) {
        die("Payment verification failed: Missing parameters");
    }

    $api = new Api('rzp_test_TMaKHOLutXGYTH', 'eyvkr7ljPXve2MnuDjHXZQVE');

    try {
        $attributes = [
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_signature' => $razorpay_signature
        ];
        $api->utility->verifyPaymentSignature($attributes);

        // Extract order from session
        $razorpay_order = $_SESSION['razorpay_order'];
        $order_id = $razorpay_order['order_id'];

        // Store values in variables before bind_param
        $first_name = $razorpay_order['first_name'];
        $last_name = $razorpay_order['last_name'];
        $phone_number = $razorpay_order['phone_number'];
        $shipping_address = $razorpay_order['shipping_address'];
        $pincode = $razorpay_order['pincode'];
        $payment_method = $razorpay_order['payment_method'];
        $total_amount = $razorpay_order['total_amount'];
        $payment_status = 'paid';

        // Save to main orders table
        $stmt = $conn->prepare("INSERT INTO orders (order_id, first_name, last_name, phone_number, shipping_address, pincode, payment_method, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssd", $order_id, $first_name, $last_name, $phone_number, $shipping_address, $pincode, $payment_method, $total_amount, $payment_status);

        if ($stmt->execute()) {
            $last_order_id = $conn->insert_id;

            // Save order items
            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");

            foreach ($razorpay_order['items'] as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $size = $item['size'];

                $stmt_items->bind_param("iiids", $last_order_id, $product_id, $quantity, $price, $size);
                $stmt_items->execute();

                // Get product details for Razorpay order items table
                $product_stmt = $conn->prepare("SELECT name, image FROM products WHERE id = ?");
                $product_stmt->bind_param("i", $product_id);
                $product_stmt->execute();
                $product_result = $product_stmt->get_result();
                $product = $product_result->fetch_assoc();
                $product_stmt->close();

                // Save to razorpay_order_items
                $stmt_razorpay_items = $conn->prepare("INSERT INTO razorpay_order_items (razorpay_order_id, product_id, product_name, product_image, size, quantity, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $product_name = $product['name'];
                $product_image = $product['image'];
                $stmt_razorpay_items->bind_param("sisssid", $razorpay_order_id, $product_id, $product_name, $product_image, $size, $quantity, $price);
                $stmt_razorpay_items->execute();
                $stmt_razorpay_items->close();
            }
            $stmt_items->close();

            // Save to razorpay_orders table
            $currency = 'INR';
            $status = 'success';
            $stmt_razorpay = $conn->prepare("INSERT INTO razorpay_orders (razorpay_order_id, merchant_order_id, payment_id, amount, currency, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_razorpay->bind_param("sssds", $razorpay_order_id, $order_id, $razorpay_payment_id, $total_amount, $currency, $status);
            $stmt_razorpay->execute();
            $stmt_razorpay->close();

            // Clear cart
            $session_id = session_id();
            $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_session_id = ?");
            $clear_cart_stmt->bind_param("s", $session_id);
            $clear_cart_stmt->execute();
            $clear_cart_stmt->close();

            // Clear session data
            unset($_SESSION['razorpay_order']);

            // Redirect to thank you page
            header("Location: thankyou_razorpay.php?order_id=$order_id&payment_id=$razorpay_payment_id");
            exit();
        } else {
            die("Error saving order: " . $stmt->error);
        }

    } catch (Exception $e) {
        die("Payment verification failed: " . $e->getMessage());
    }
} else {
    die("Invalid request method");
}
?>
