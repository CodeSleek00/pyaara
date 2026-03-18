<?php
include 'db.php';

$message = "";

if(isset($_POST['verify'])){
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    $query = "SELECT * FROM users WHERE email='$email' AND dob='$dob'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        // user found → redirect to reset page
        header("Location: reset_password.php?email=$email");
        exit();
    } else {
        $message = "Invalid Email or DOB!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

<h2>Forgot Password</h2>

<form method="POST">
    <input type="email" name="email" placeholder="Enter Email" required><br><br>
    <input type="date" name="dob" required><br><br>
    <button type="submit" name="verify">Verify</button>
</form>

<p style="color:red;"><?php echo $message; ?></p>

</body>
</html>