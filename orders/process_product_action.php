<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $size = $_POST['size'];
    $action = $_POST['action'];
    
    // Validate inputs
    if ($product_id <= 0 || $quantity <= 0 || !in_array($size, ['XS','S', 'M', 'L', 'XL','XXL'])) {
        $_SESSION['message'] = "Invalid product data.";
        $_SESSION['message_type'] = "error";
        header("Location: ../index.php");
        exit();
    }
    
    // Get product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['message'] = "Product not found.";
        $_SESSION['message_type'] = "error";
        header("Location: ../index.php");
        exit();
    }
    
    $product = $result->fetch_assoc();
    $stmt->close();
    
    $user_session_id = session_id();
    
    if ($action === 'add_to_cart') {
        // Check if product already in cart
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_session_id = ? AND product_id = ? AND size = ?");
        $stmt->bind_param("sis", $user_session_id, $product_id, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update quantity if already in cart
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_quantity, $row['id']);
        } else {
            // Add new item to cart
            $stmt = $conn->prepare("INSERT INTO cart (user_session_id, product_id, quantity, size) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siis", $user_session_id, $product_id, $quantity, $size);
        }
        
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['message'] = "Product added to cart successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: cart.php");
        exit();
        
    } elseif ($action === 'buy_now') {
        // For Razorpay integration, we'll store the product in session and redirect to checkout
        
        // Store product details in session for checkout
        $_SESSION['buy_now_product'] = [
            'product_id' => $product_id,
            'name' => $product['name'],
            'image' => $product['image'],
            'quantity' => $quantity,
            'size' => $size,
            'price' => ($product['discount_price'] > 0 && $product['discount_price'] < $product['original_price']) 
                        ? $product['discount_price'] 
                        : $product['original_price']
        ];
        
        // Redirect directly to Razorpay checkout
        header("Location: razorpay_checkout.php");
        exit();
    }
}

// If not POST or invalid action, redirect to home
header("Location: ../index.php");
exit();
?>