<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = 1; // Default quantity when adding to cart
    $size = isset($_POST['size']) ? $conn->real_escape_string($_POST['size']) : null; // Get size if exists

    // Use session ID to identify the cart, useful for guest users
    $user_session_id = session_id();

    // Check if the product (with specific size) is already in the cart
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE product_id = ? AND user_session_id = ? AND (size IS NULL OR size = ?)");
    $stmt->bind_param("iss", $product_id, $user_session_id, $size);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product already in cart, update quantity
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_quantity, $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
        $_SESSION['message'] = "Product quantity updated in cart!";
    } else {
        // Product not in cart, add new item
        $insert_stmt = $conn->prepare("INSERT INTO cart (product_id, quantity, user_session_id, size) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("iiss", $product_id, $quantity, $user_session_id, $size);
        $insert_stmt->execute();
        $insert_stmt->close();
        $_SESSION['message'] = "Product added to cart!";
    }

    $stmt->close();
    header("Location: ../index.php"); // Redirect back to product listing
    exit();
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: ../index.php");
    exit();
}

$conn->close();
?>