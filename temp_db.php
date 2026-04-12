<?php
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Local
    $host = "127.0.0.1";
    $user = "root";
    $pass = "";
    $db   = "pyaara_store";
} else {
    // Live
    $host = "srv1259.hstgr.io";
    $user = "u298112699_Anant";
    $pass = "Pyaara_store15";
    $db   = "u298112699_pyaara_store_A";
}

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>