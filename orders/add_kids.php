<?php
include 'db_connect.php';

if(isset($_POST['submit'])){

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

    $query = "INSERT INTO kids_products 
    (category_id, name, description, image, price, discount_price, age_group, stock)
    VALUES 
    ('$category_id','$name','$desc','$image','$price','$discount_price','$age_group','$stock')";

    if($conn->query($query)){
        echo "<script>alert('Kids Product Added Successfully');</script>";
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
body { font-family: Arial; background:#f5f5f5; }

.container {
    width: 400px;
    margin: 50px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
}

h2 { text-align:center; }

input, textarea, select {
    width:100%;
    padding:10px;
    margin:8px 0;
}

button {
    width:100%;
    padding:12px;
    background:black;
    color:white;
    border:none;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="container">
<h2>Add Kids Product 👶</h2>

<form method="POST" enctype="multipart/form-data">

<!-- CATEGORY -->
<select name="category_id" required>
<option value="">Select Category</option>

<?php
$cat = $conn->query("SELECT * FROM kids_categories");
while($row = $cat->fetch_assoc()){
    echo "<option value='".$row['id']."'>".$row['category_name']." (".$row['age_group'].")</option>";
}
?>

</select>

<input type="text" name="name" placeholder="Product Name" required>

<textarea name="description" placeholder="Description" required></textarea>

<input type="number" name="price" placeholder="Original Price" required>

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