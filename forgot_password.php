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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/Pyaara Circle.png">
    <link rel="apple-touch-icon" href="images/Pyaara Circle.png">
    <title>Forgot Password - Authentication System</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: url('images/anime-eyes.webp') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0);
            z-index: -1;
        }

        .container {
            display: flex;
            width: 80%;
            max-width: 1200px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .image-section {
            flex: 1;
            background: url('images/image.png') no-repeat center center;
            background-size: cover;
            position: relative;
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .login-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-section h2 {
            color: #d32f2f;
            margin-bottom: 12px;
            text-align: center;
            font-weight: 600;
        }

        .login-section p {
            text-align: center;
            color: #666;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: #d32f2f;
            outline: none;
            box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.2);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #d32f2f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: #b71c1c;
        }

        .additional-options {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .additional-options a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .additional-options a:hover {
            color: #d32f2f;
        }

        .message {
            margin-top: 16px;
            text-align: center;
            color: #d32f2f;
            font-weight: 500;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .back-btn:hover {
            background-color: #f5f5f5;
            transform: translateX(-2px);
        }

        .back-btn i {
            color: #333;
            font-size: 18px;
        }

        @media (max-width: 992px) {
            .container {
                width: 90%;
                flex-direction: column;
            }

            .image-section {
                height: 200px;
            }
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            .login-section {
                padding: 30px 20px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <button class="back-btn" onclick="window.location.href='login.php'">
        <i class="fas fa-arrow-left"></i>
    </button>

    <div class="container">
        <div class="image-section"></div>
        <div class="login-section">
            <h2>Forgot Password</h2>
            <p>Enter your email and date of birth to continue.</p>
            <form method="POST">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Enter Email" required>
                </div>
                <div class="form-group">
                    <input type="date" name="dob" required>
                </div>
                <button type="submit" name="verify" class="login-btn">Verify</button>
            </form>
            <?php if (!empty($message)) { ?>
                <div class="message"><?php echo $message; ?></div>
            <?php } ?>
            <div class="additional-options">
                <a href="login.php">Back to Login</a>
            </div>
        </div>
    </div>

</body>
</html>
