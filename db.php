<?php
$host = "178.16.136.97";
$user = "u298112699_Anant";
$pass = "Pyaara_store15";
$db = "u298112699_pyaara_store_A";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
