<?php
$host = "srv1234.hostinger.com"; // Hostinger MySQL host
$user = "u298112699_Anant";
$pass = "Pyaara_store15";
$db   = "u298112699_pyaara_store_A";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>