
<?php
// db_connect.php
$host = "178.16.136.97";
$user = "u298112699_Anant";
$pass = "Pyaara_store15";
$db   = "u298112699_pyaara_store_A";

if (!isset($GLOBALS['db'])) {
    $GLOBALS['db'] = new mysqli($host, $user, $pass, $db);

    if ($GLOBALS['db']->connect_error) {
        die("Database connection failed: " . $GLOBALS['db']->connect_error);
    }
}

$conn = $GLOBALS['db'];
?>
