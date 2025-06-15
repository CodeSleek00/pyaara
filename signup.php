<?php
session_start();
require 'db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$dob = $_POST['dob'];
$address = $_POST['address'];

if (empty($email) && empty($contact)) {
    die("Email or Contact is required.");
}

$sql = "INSERT INTO users (name, email, contact, password, dob, address)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $email, $contact, $password, $dob, $address);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $conn->insert_id;
    header("Location: profile.php");
} else {
    echo "Error: " . $stmt->error;
}
?>
