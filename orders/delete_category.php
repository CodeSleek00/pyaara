<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $category_id = (int)$_GET['id'];

    // Optional: Check if category has products
    $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->bind_result($product_count);
    $stmt->fetch();
    $stmt->close();

    if ($product_count > 0) {
        $_SESSION['message'] = "Cannot delete category with existing products.";
        $_SESSION['message_type'] = "error";
    } else {
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $category_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Category deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
            $_SESSION['message_type'] = "error";
        }
        $stmt->close();
    }
    header("Location: admin_dashboard.php");
    exit;
}
?>
