<?php
include 'db_connect.php';

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $desc = $_POST['description'];
    $original_price = $_POST['original_price'];
    $discount_price = $_POST['discount_price'];
    $age_group = $_POST['age_group'];
    $stock = $_POST['stock'];

    $discount_percent = 0;
    if($original_price > 0){
        $discount_percent = (($original_price - $discount_price) / $original_price) * 100;
    }

    // Page FIXED for kids
    $page = "kids.php";

    // Image Upload
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "uploads/".$image);

    $query = "INSERT INTO products 
    (name, description, image, original_price, discount_price, discount_percent, page, stock)
    VALUES 
    ('$name','$desc','$image','$original_price','$discount_price','$discount_percent','$page','$stock')";

    if($conn->query($query)){
        echo "<script>alert('Product Added Successfully');</script>";
    } else {
        echo "Error: ".$conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Kids Product</title>

<style>
body {
    font-family: Arial;
    background: #f5f5f5;
}

.container {
    width: 400px;
    margin: 50px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
}

h2 {
    text-align: center;
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
</style>
</head>

<body>

<div class="container">
<h2>Add Kids Product 👶</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" placeholder="Product Name" required>

<textarea name="description" placeholder="Description" required></textarea>

<input type="number" name="original_price" placeholder="Original Price" required>

<input type="number" name="discount_price" placeholder="Discount Price" required>

<!-- AGE GROUP -->
<select name="age_group" required>
<option value="">Select Age Group</option>
<option>1-3 Years</option>
<option>4-6 Years</option>
<option>7-10 Years</option>
<option>10-14 Years</option>
</select>

<!-- STOCK -->
<input type="number" name="stock" placeholder="Stock Quantity" required>

<!-- IMAGE -->
<input type="file" name="image" required>

<button type="submit" name="submit">Add Product</button>

</form>
</div>

</body>
</html>