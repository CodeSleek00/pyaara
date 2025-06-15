<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name_raw = $_POST['category_name'];

    // Sanitize category name: allow only letters, numbers, underscores
    $category_name = preg_replace('/[^a-zA-Z0-9_]/', '', $category_name_raw);

    if (empty($category_name)) {
        $_SESSION['message'] = "Invalid category name.";
        $_SESSION['message_type'] = "error";
        header("Location: admin_dashboard.php");
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $category_name);

    if ($stmt->execute()) {
        // Create a new PHP page for the category
        $filename = strtolower($category_name) . '.php';
        $template = <<<PHP
<?php
include 'header.php';
?>
<h1>Welcome to the {$category_name} Category</h1>
<p>This is the {$category_name} category page.</p>
<?php
include 'footer.php';
?>
PHP;

        file_put_contents($filename, $template);

        $_SESSION['message'] = "Category '{$category_name}' added and page '{$filename}' created!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    header("Location: admin_dashboard.php");
    exit;
}
?>
