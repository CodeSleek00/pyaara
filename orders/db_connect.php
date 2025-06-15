<?php
// Start session for managing user cart and other session data
session_start();

$host = 'localhost';
$user = 'root';
$pass = ''; // Your password
$db = 'pyaara_store';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>