<?php
include 'db_connect.php';

/* ================= FETCH FROM KIDS TABLE ================= */
$products = $conn->query("
SELECT kp.*, kc.category_name 
FROM kids_products kp
LEFT JOIN kids_categories kc ON kp.category_id = kc.id
ORDER BY kp.id DESC
");

/* ================= ARRAY + SHUFFLE ================= */
$products_array = [];
if ($products && $products->num_rows > 0) {
    while ($row = $products->fetch_assoc()) {
        $products_array[] = $row;
    }
    shuffle($products_array);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kids Collection | Pyaara Store</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --primary-red: #E63946;
  --primary-yellow: #FFD166;
  --primary-white: #FFFFFF;
  --dark-gray: #2D3436;
  --light-gray: #F1FAEE;
  --border-radius: 8px;
  --box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
* {margin:0;padding:0;box-sizing:border-box;font-family:'Outfit',sans-serif;}
body {background:#fff;}

.container {max-width:1400px;margin:auto;padding:20px;}

.products-grid {
display:grid;
grid-template-columns:repeat(4,1fr);
gap:25px;
}

.product-card {
background:#fff;
border-radius:8px;
box-shadow:0 4px 20px rgba(0,0,0,0.08);
overflow:hidden;
transition:.3s;
}
.product-card:hover {transform:translateY(-5px);}

.product-image-container {
width:100%;
padding-top:120%;
position:relative;
background:#f1f1f1;
}

.product-image {
position:absolute;
width:100%;
height:100%;
object-fit:cover;
}

.discount-tag {
position:absolute;
top:10px;
right:10px;
background:#FFD166;
padding:4px 10px;
border-radius:20px;
font-size:12px;
}

.stock-tag {
position:absolute;
bottom:10px;
left:10px;
background:#E63946;
color:#fff;
padding:4px 8px;
border-radius:5px;
font-size:12px;
}

.product-info {padding:15px;}

.product-name {
font-weight:600;
height:40px;
overflow:hidden;
}

.current-price {
color:#E63946;
font-weight:bold;
}

.original-price {
text-decoration:line-through;
font-size:13px;
color:#888;
}

.product-actions {
display:flex;
gap:10px;
margin-top:10px;
}

.btn {
flex:1;
padding:10px;
border:none;
cursor:pointer;
border-radius:5px;
text-decoration:none;
text-align:center;
}

.btn-primary {background:#E63946;color:#fff;}
.btn-secondary {border:1px solid #E63946;color:#E63946;}

@media(max-width:768px){
.products-grid {grid-template-columns:repeat(2,1fr);}
}
</style>
</head>

<body>

<div class="container">
<h2 style="text-align:center;margin-bottom:20px;">Kids Collection 👶</h2>

<div class="products-grid">

<?php if(!empty($products_array)): ?>

<?php foreach($products_array as $row): 

/* DISCOUNT CALCULATION */
$discount = 0;
if($row['price'] > 0){
$discount = (($row['price'] - $row['discount_price']) / $row['price']) * 100;
}
?>

<div class="product-card">

<div class="product-image-container">
<a href="product_detail.php?id=<?php echo $row['id']; ?>">
<img class="product-image" src="uploads/<?php echo htmlspecialchars($row['image']); ?>">
</a>

<?php if($row['discount_price'] < $row['price']): ?>
<div class="discount-tag"><?php echo number_format($discount); ?>% OFF</div>
<?php endif; ?>

<?php if($row['stock'] <= 5 && $row['stock'] > 0): ?>
<div class="stock-tag">Only <?php echo $row['stock']; ?> left</div>
<?php elseif($row['stock'] == 0): ?>
<div class="stock-tag" style="background:black;">Out of Stock</div>
<?php endif; ?>

</div>

<div class="product-info">

<div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>

<p style="font-size:12px;color:#666;">
<?php echo $row['category_name']; ?> | <?php echo $row['age_group']; ?>
</p>

<p>
<span class="current-price">₹<?php echo $row['discount_price']; ?></span>
<span class="original-price">₹<?php echo $row['price']; ?></span>
</p>

<div class="product-actions">

<a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">
View
</a>

<?php if($row['stock'] > 0): ?>
<form method="POST" action="add_to_cart.php" style="flex:1;">
<input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
<button class="btn btn-primary">Add</button>
</form>
<?php else: ?>
<button class="btn btn-primary" style="background:gray;" disabled>
Out of Stock
</button>
<?php endif; ?>

</div>

</div>
</div>

<?php endforeach; ?>

<?php else: ?>
<p>No products found</p>
<?php endif; ?>

</div>
</div>

</body>
</html>

<?php $conn->close(); ?>