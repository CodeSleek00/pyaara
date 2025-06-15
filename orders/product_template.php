<?php
include 'db_connect.php';

$page = basename($_SERVER['PHP_SELF']);

$result = $conn->query("SELECT * FROM products WHERE page = '$page'");

echo "<h2>Products on " . ucfirst(str_replace('.php', '', $page)) . "</h2>";
echo "<div class='product-list'>";
while ($row = $result->fetch_assoc()) {
    echo "<div class='product-card'>";
    echo "<img src='uploads/{$row['image']}' alt='{$row['name']}' style='width:150px;height:150px;'><br>";
    echo "<strong>{$row['name']}</strong><br>";
    echo "₹<del>{$row['original_price']}</del> <b>{$row['discount_price']}</b><br>";
    echo "<p>{$row['description']}</p>";
    echo "</div>";
}
echo "</div>";

$conn->close();
?>
