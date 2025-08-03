<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: url('https://images.unsplash.com/photo-1519681393784-d120267933ba?ixlib=rb-1.2.1&auto=format&fit=crop&w=1500&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.85);
            z-index: -1;
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
            z-index: 10;
        }

        .back-btn:hover {
            background-color: #f5f5f5;
            transform: translateX(-2px);
        }

        .back-btn i {
            color: #333;
            font-size: 18px;
        }

        .dashboard-container {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #d32f2f;
            padding: 30px;
            color: white;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar-header h2 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            opacity: 0.8;
            font-size: 14px;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: white;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d32f2f;
            font-size: 32px;
            font-weight: bold;
        }

        .nav-menu {
            list-style: none;
            margin-top: 40px;
        }

        .nav-menu li {
            margin-bottom: 15px;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .nav-menu a:hover, .nav-menu a.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .nav-menu i {
            margin-right: 10px;
            font-size: 18px;
        }

        .main-content {
            flex: 1;
            padding: 40px;
        }

        .welcome-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .welcome-header h1 {
            color: #333;
            font-weight: 600;
        }

        .logout-btn {
            background-color: #ffc107;
            color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background-color: #ffb300;
        }

        .user-info-card {
            background-color: #f9f9f9;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .info-group {
            margin-bottom: 20px;
        }

        .info-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
            display: block;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            padding: 10px 15px;
            background-color: white;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .two-columns {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .two-columns .info-group {
            flex: 1;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 20px;
            }

            .user-avatar {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .nav-menu {
                margin-top: 20px;
                display: flex;
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .nav-menu li {
                margin-right: 15px;
                margin-bottom: 0;
                flex-shrink: 0;
            }

            .main-content {
                padding: 30px 20px;
            }

            .two-columns {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <button class="back-btn" onclick="window.location.href='index.php'">
        <i class="fas fa-arrow-left"></i>
    </button>

    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                <p>Member Since: <?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?></p>
            </div>

            <ul class="nav-menu">
                <li><a href="#" class="active"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="orders/order_history.php"><i class="fa-solid fa-list"></i> My Orders</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="welcome-header">
                <h1>Dashboard</h1>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <div class="user-info-card">
                <h3 style="margin-bottom: 25px; color: #d32f2f;">Personal Information</h3>
                
                <div class="two-columns">
                    <div class="info-group">
                        <span class="info-label">Full Name</span>
                        <div class="info-value"><?php echo htmlspecialchars($user['name']); ?></div>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Date of Birth</span>
                        <div class="info-value"><?php echo htmlspecialchars(date('F j, Y', strtotime($user['dob']))); ?></div>
                    </div>
                </div>

                <div class="two-columns">
                    <div class="info-group">
                        <span class="info-label">Email Address</span>
                        <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Contact Number</span>
                        <div class="info-value"><?php echo htmlspecialchars($user['contact']); ?></div>
                    </div>
                </div>

                <div class="info-group">
                    <span class="info-label">Address</span>
                    <div class="info-value" style="min-height: 80px;"><?php echo nl2br(htmlspecialchars($user['address'])); ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>