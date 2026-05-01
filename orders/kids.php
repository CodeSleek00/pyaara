<?php
include 'db_connect.php';

$page = basename($_SERVER['PHP_SELF']);
$result = $conn->query("SELECT * FROM products WHERE page = '$page' ORDER BY id DESC");

$products = [];
while($row = $result->fetch_assoc()){
    $products[] = $row;
}
shuffle($products);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo ucfirst($page); ?></title>

<style>
body { font-family: Arial; background:#f5f5f5; }
.container { width:90%; margin:auto; }

.products-grid {
 display:grid;
 grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
 gap:20px;
}

.card {
 background:#fff;
 padding:15px;
 border-radius:10px;
 text-align:center;
 position:relative;
}

.card img { width:100%; height:200px; object-fit:cover; }

.btn {
 display:inline-block;
 padding:8px 15px;
 margin-top:10px;
 background:black;
 color:white;
 text-decoration:none;
 border:none;
 cursor:pointer;
}
</style>
</head>

<body>

<div class="container">
<h2><?php echo ucfirst($page); ?></h2>

<div class="products-grid">

<?php foreach($products as $row): ?>

<div class="card">

<img src="uploads/<?php echo $row['image']; ?>">

<h3><?php echo $row['name']; ?></h3>

<p>
₹<?php echo $row['discount_price']; ?>
<del>₹<?php echo $row['original_price']; ?></del>
</p>

<!-- STOCK BADGE -->
<?php if($row['stock'] <= 5 && $row['stock'] > 0): ?>
<p style="color:red;">Only <?php echo $row['stock']; ?> left</p>
<?php elseif($row['stock'] == 0): ?>
<p style="color:red;">Out of Stock</p>
<?php endif; ?>

<a class="btn" href="product_detail.php?id=<?php echo $row['id']; ?>">View</a>

<?php if($row['stock'] > 0): ?>
<form method="POST" action="add_to_cart.php">
<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
<button class="btn">Add to Cart</button>
</form>
<?php else: ?>
<button class="btn" disabled style="background:gray;">Out of Stock</button>
<?php endif; ?>

</div>

<?php endforeach; ?>

</div>
</div>

</body>
</html>