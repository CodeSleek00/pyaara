<?php
include 'db_connect.php';
$message = '';
$message_type = '';

$pages = ['anime.php', 'exclusive.php', 'men.php', 'women.php' , 'round.php', 'full.php', 'polo.php', 'cutie.php' ,'classic_women.php','Crop_Top.php','Cropped_Hoodies.php', 'Shorts.php', 'Joggers.php', 'sweatshirt.php', 'hoodies.php', 'Acid_Washed.php'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $original_price = (float)$_POST['original_price'];
    $discount_price = (float)$_POST['discount_price'];
    $category_id = null;

    // ✅ HANDLE SIZES
    $sizes = '';
    if (!empty($_POST['sizes'])) {
        $sizes = implode(',', $_POST['sizes']);
    }

    // Handle category logic
    if (!empty($_POST['new_category'])) {
        $new_category = $conn->real_escape_string($_POST['new_category']);
        $check = $conn->query("SELECT id FROM categories WHERE name = '$new_category'");
        if ($check->num_rows > 0) {
            $category_id = $check->fetch_assoc()['id'];
        } else {
            $conn->query("INSERT INTO categories (name) VALUES ('$new_category')");
            $category_id = $conn->insert_id;
        }
    } elseif (!empty($_POST['category_id'])) {
        $category_id = (int)$_POST['category_id'];
    }

    // Handle image upload
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                $message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
                $message_type = "error";
            } else {
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            }
        } else {
            $message = "File is not a valid image.";
            $message_type = "error";
        }
    }

    // Handle page selection
    $page = '';
    if (!empty($_POST['new_page'])) {
        $page = basename($_POST['new_page']);
        if (!preg_match('/\.php$/', $page)) {
            $page .= '.php';
        }
        $new_page_path = __DIR__ . '/' . $page;
        if (!file_exists($new_page_path)) {
            file_put_contents($new_page_path, "<?php include 'product_template.php'; ?>");
        }
    } elseif (!empty($_POST['page'])) {
        $page = basename($_POST['page']);
    }

    if ($message_type !== "error") {
        $discount_percent = 0;
        if ($original_price > 0 && $discount_price < $original_price) {
            $discount_percent = (($original_price - $discount_price) / $original_price) * 100;
        } else {
            $discount_price = $original_price;
        }

        // ✅ UPDATED QUERY WITH SIZES
        $stmt = $conn->prepare("INSERT INTO products 
        (name, image, description, original_price, discount_price, discount_percent, category_id, page, sizes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssdiiiss", $name, $image_name, $description, $original_price, $discount_price, $discount_percent, $category_id, $page, $sizes);

        if ($stmt->execute()) {
            $message = "Product added successfully!";
            $message_type = "success";
            $_POST = array();
        } else {
            $message = "Error: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

$categories = $conn->query("SELECT id, name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f4f4;
    padding: 10px;
}

.container {
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #333;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input, textarea, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

input[type="submit"] {
    margin-top: 20px;
    background: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
}

.message.success { background:#d4edda; padding:10px; margin-bottom:10px; }
.message.error { background:#f8d7da; padding:10px; margin-bottom:10px; }

.size-box {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:10px;
}

.size-box label {
    font-weight:normal;
    background:#eee;
    padding:5px 10px;
    border-radius:5px;
    cursor:pointer;
}

</style>
</head>
<body>

<div class="container">
<h2>Add New Product</h2>

<?php if ($message): ?>
<div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
<?php endif; ?>

<form action="add_product.php" method="post" enctype="multipart/form-data">

<label>Product Name:</label>
<input type="text" name="name" required>

<label>Image:</label>
<input type="file" name="image" required>

<label>Description:</label>
<textarea name="description" required></textarea>

<label>Original Price:</label>
<input type="number" name="original_price" required>

<label>Discount Price:</label>
<input type="number" name="discount_price">

<label>Select Category:</label>
<select name="category_id">
<option value="">--Select--</option>
<?php while ($row = $categories->fetch_assoc()): ?>
<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
<?php endwhile; ?>
</select>

<label>New Category:</label>
<input type="text" name="new_category">

<label>Select Page:</label>
<select name="page">
<option value="">--Select--</option>
<?php foreach ($pages as $pg): ?>
<option value="<?php echo $pg; ?>"><?php echo ucfirst(str_replace('.php','',$pg)); ?></option>
<?php endforeach; ?>
</select>

<label>New Page:</label>
<input type="text" name="new_page">

<!-- ✅ SIZE SECTION -->
<label>Select Sizes:</label>
<div class="size-box">
<?php 
$sizesArr = ['XXS','XS','S','M','L','XL','XXL','XXXL'];
foreach($sizesArr as $s): ?>
<label><input type="checkbox" name="sizes[]" value="<?php echo $s; ?>"> <?php echo $s; ?></label>
<?php endforeach; ?>
</div>

<input type="submit" value="Add Product">

</form>
</div>

</body>
</html>

<?php $conn->close(); ?>