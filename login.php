<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['email_or_contact'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ? OR contact = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        
        // Redirect to checkout if coming from there
        if (isset($_SESSION['redirect_url'])) {
            $redirect_url = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']);
            header("Location: " . $redirect_url);
        } else {
            header("Location: profile.php"); // Default redirect
        }
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid credentials";
        header("Location: login.html");
        exit();
    }
}
?>