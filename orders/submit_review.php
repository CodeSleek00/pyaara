<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name       = trim($_POST['review_name'] ?? "");
    $email      = trim($_POST['review_email'] ?? "");
    $rating     = intval($_POST['review_rating'] ?? 0);
    $title      = trim($_POST['review_title'] ?? "");
    $comment    = trim($_POST['review_comment'] ?? "");

    // Validation
    if ($product_id <= 0) {
        $_SESSION['message'] = "Invalid product.";
        $_SESSION['message_type'] = "error";
        header("Location: product_detail.php?id=" . $product_id);
        exit();
    }
    if ($rating < 1 || $rating > 5) {
        $_SESSION['message'] = "Please select a rating between 1 and 5.";
        $_SESSION['message_type'] = "error";
        header("Location: product_detail.php?id=" . $product_id . "#reviews");
        exit();
    }
    if (empty($comment)) {
        $_SESSION['message'] = "Review cannot be empty.";
        $_SESSION['message_type'] = "error";
        header("Location: product_detail.php?id=" . $product_id . "#reviews");
        exit();
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO product_reviews 
        (product_id, name, email, rating, title, comment) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $product_id, $name, $email, $rating, $title, $comment);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Thank you! Your review has been submitted.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error while submitting review.";
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    header("Location: product_detail.php?id=" . $product_id . "#reviews");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
