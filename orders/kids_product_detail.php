<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';
session_start();

$product = null;
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id > 0) {

    // ✅ FIX: kids_products table use karo
    $stmt = $conn->prepare("SELECT * FROM kids_products WHERE id = ?");

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }

    $stmt->close();
}

// ❌ agar product nahi mila
if (!$product) {
    header("Location: kids.php");
    exit();
}

// ✅ sizes
$all_sizes = ['XS','S','M','L','XL'];

$product_sizes = !empty($product['sizes'])
    ? explode(',', $product['sizes'])
    : $all_sizes;

// ✅ price logic
$display_price = (!empty($product['discount_price']) && $product['discount_price'] < $product['price'])
    ? $product['discount_price']
    : $product['price'];

// ✅ related products (same table)
$related_products = [];

$stmt2 = $conn->prepare("
    SELECT id, name, image, price, discount_price 
    FROM kids_products 
    WHERE id != ? 
    ORDER BY RAND() LIMIT 4
");

$stmt2->bind_param("i", $product_id);
$stmt2->execute();
$res2 = $stmt2->get_result();

while ($row = $res2->fetch_assoc()) {
    $related_products[] = $row;
}

$stmt2->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $product['name']; ?> | Kids Collection</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* SAME DESIGN – NO CHANGE */
body {
    font-family: 'Outfit', sans-serif;
    background: #fff;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.product-hero {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
}

.product-gallery {
    flex: 1;
}

.product-gallery img {
    width: 100%;
    border-radius: 10px;
}

.product-info {
    flex: 1;
}

.product-title {
    font-size: 28px;
    font-weight: 700;
}

.price {
    font-size: 22px;
    color: #E63946;
    margin: 10px 0;
}

.old-price {
    text-decoration: line-through;
    color: gray;
}

.btn {
    padding: 12px 20px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
}

.btn-primary {
    background: #E63946;
    color: #fff;
}

.btn-secondary {
    border: 1px solid #E63946;
    color: #E63946;
    background: transparent;
}

/* RELATED */
.related {
    margin-top: 60px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 20px;
}

.card {
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border-radius: 10px;
    overflow: hidden;
}

.card img {
    width: 100%;
}
</style>
</head>

<body>

<div class="container">

<a href="kids.php">← Back</a>

<div class="product-hero">

<div class="product-gallery">
    <img src="uploads/<?php echo $product['image']; ?>">
</div>

<div class="product-info">

<h1 class="product-title"><?php echo $product['name']; ?></h1>

<div class="price">
    ₹<?php echo $display_price; ?>
    <?php if($product['discount_price']): ?>
        <span class="old-price">₹<?php echo $product['original_price']; ?></span>
    <?php endif; ?>
</div>

<p><?php echo $product['description']; ?></p>

<form action="process_product_action.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

    <!-- SIZE -->
    <div>
        <b>Size:</b><br>
        <?php foreach($product_sizes as $size): ?>
            <label>
                <input type="radio" name="size" value="<?php echo $size; ?>" required>
                <?php echo $size; ?>
            </label>
        <?php endforeach; ?>
    </div>

    <!-- QUANTITY -->
    <div style="margin-top:10px;">
        <b>Qty:</b>
        <input type="number" name="quantity" value="1" min="1">
    </div>

    <button name="action" value="add_to_cart" class="btn btn-primary">Add to Cart</button>
    <button name="action" value="buy_now" class="btn btn-secondary">Buy Now</button>
</form>

</div>
</div>

<!-- RELATED -->
<div class="related">
<h2>Kids Collection</h2>

<div class="grid">
<?php foreach($related_products as $item): ?>
    <a href="kids_product_detail.php?id=<?php echo $item['id']; ?>" class="card">
        <img src="uploads/<?php echo $item['image']; ?>">
        <div style="padding:10px;">
            <h4><?php echo $item['name']; ?></h4>
            <p>₹<?php echo $item['discount_price'] ?: $item['original_price']; ?></p>
        </div>
    </a>
<?php endforeach; ?>
</div>

</div>

</div>

</body>
</html>

<?php $conn->close(); ?> 