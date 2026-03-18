<?php
include 'db.php';

$email = $_GET['email'];

if(isset($_POST['reset'])){
    $new_password = $_POST['password'];

    // ❌ Plain password (NOT recommended)
    $query = "UPDATE users SET password='$new_password' WHERE email='$email'";

    // ✅ Recommended (use this instead)
    // $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    // $query = "UPDATE users SET password='$hashed' WHERE email='$email'";

    if(mysqli_query($conn, $query)){
        echo "Password Updated Successfully!";
    } else {
        echo "Error!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>

<h2>Reset Password</h2>

<form method="POST">
    <input type="password" name="password" placeholder="New Password" required><br><br>
    <button type="submit" name="reset">Change Password</button>
</form>

</body>
</html>