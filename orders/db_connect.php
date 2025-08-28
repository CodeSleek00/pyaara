<?php
// Start session for cart, login, etc.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "178.16.136.97";
$user = "u298112699_Anant";
$pass = "Pyaara_store15";
$db   = "u298112699_pyaara_store_A";

// Only create connection if not already set
if (!isset($conn) || !$conn instanceof mysqli) {
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }

    // Register auto-close function (no need to call $conn->close() manually)
    register_shutdown_function(function() use ($conn) {
        if ($conn && $conn instanceof mysqli) {
            $conn->close();
        }
    });
}
?>
