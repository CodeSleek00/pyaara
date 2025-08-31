<?php
include 'db_connect.php'; // apna DB connection file include karo

// Agar user login nahi hai
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Please login to submit a review."]);
    exit;
}

// Request method check
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Input sanitize
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $rating     = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $review     = isset($_POST['review']) ? trim($_POST['review']) : "";

    $user_id    = $_SESSION['user_id']; // logged in user ka ID

    // Validation
    if ($product_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid product."]);
        exit;
    }
    if ($rating < 1 || $rating > 5) {
        echo json_encode(["status" => "error", "message" => "Rating must be between 1 and 5."]);
        exit;
    }
    if (empty($review)) {
        echo json_encode(["status" => "error", "message" => "Review cannot be empty."]);
        exit;
    }

    // Insert query
    $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare query."]);
        exit;
    }

    $stmt->bind_param("iiis", $product_id, $user_id, $rating, $review);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Review submitted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error while submitting review."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
