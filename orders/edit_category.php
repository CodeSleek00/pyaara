<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int)$_POST['category_id'];
    $category_name = $conn->real_escape_string($_POST['category_name']);

    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $category_name, $category_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Category updated successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "error";
    }
    $stmt->close();
    header("Location: admin_dashboard.php");
    exit;
}
?>
