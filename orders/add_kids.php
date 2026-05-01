<?php
include 'db_connect.php';

/* ===================== ADD CATEGORY ===================== */
if(isset($_POST['add_category'])){
    $name = $_POST['category_name'];
    $age = $_POST['category_age'];

    $conn->query("INSERT INTO kids_categories (category_name, age_group)
                  VALUES ('$name','$age')");
}

/* ===================== ADD PRODUCT ===================== */
if(isset($_POST['add_product'])){

    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $discount_price = $_POST['discount_price'];
    $age_group = $_POST['age_group'];
    $stock = $_POST['stock'];

    // Image Upload
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "uploads/".$image);

    $conn->query("INSERT INTO kids_products 
    (category_id, name, description, image, price, discount_price, age_group, stock)
    VALUES 
    ('$category_id','$name','$desc','$image','$price','$discount_price','$age_group','$stock')");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Kids Admin Panel</title>

<style>
body {
    font-family: 'Outfit', sans-serif;
    background: #f5f5f5;
}

.container {
    width: 90%;
    margin: auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-top: 40px;
}

.box {
    background: white;
    padding: 20px;
    border-radius: 10px;
}

h2 {
    text-align: center;
    margin-bottom: 15px;
}

input, textarea, select {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
}

button {
    width: 100%;
    padding: 12px;
    background: black;
    color: white;
    border: none;
    cursor: pointer;
}

.success {
    text-align:center;
    color:green;
}
</style>
</head>

<body>

<div class="container">

<!-- ================= CATEGORY SECTION ================= -->
<div class="box">
<h2>Add Category 🧩</h2>

<form method="POST">

<input type="text" name="category_name" placeholder="Category Name" required>

<select name="category_age" required>
<option value="">Select Age Group</option>
<option>1-3 Years</option>
<option>4-6 Years</option>
<option>7-10 Years</option>
<option>10-14 Years</option>
</select>

<button name="add_category">Add Category</button>

</form>
</div>


<!-- ================= PRODUCT SECTION ================= -->
<div class="box">
<h2>Add Kids Product 👶</h2>

<form method="POST" enctype="multipart/form-data">

<!-- CATEGORY DROPDOWN -->
<select name="category_id" required>
<option value="">Select Category</option>

<?php
$cats = $conn->query("SELECT * FROM kids_categories");
while($c = $cats->fetch_assoc()){
    echo "<option value='{$c['id']}'>{$c['category_name']} ({$c['age_group']})</option>";
}
?>

</select>

<input type="text" name="name" placeholder="Product Name" required>

<textarea name="description" placeholder="Description" required></textarea>

<input type="number" name="price" placeholder="Original Price" required>

<input type="number" name="discount_price" placeholder="Discount Price" required>

<select name="age_group" required>
<option value="">Select Age Group</option>
<option>1-3 Years</option>
<option>4-6 Years</option>
<option>7-10 Years</option>
<option>10-14 Years</option>
</select>

<input type="number" name="stock" placeholder="Stock Quantity" required>

<input type="file" name="image" required>

<button name="add_product">Add Product</button>

</form>
</div>

</div>

</body>
</html>