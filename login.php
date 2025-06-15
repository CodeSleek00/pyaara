<?php
session_start();
require 'db.php';

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
    header("Location: profile.php");
} else {
    echo "Invalid credentials.";
}
?>
