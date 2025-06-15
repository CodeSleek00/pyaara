<?php
require 'db.php'; // Connect to the database
// Fetch all users
$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);
// Count users
$total_users = $result->num_rows;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - All Users</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding: 20px; 
            background-color: #f5f5f5;
        }
        
        /* Password overlay styles */
        #passwordOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .password-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        
        .password-container h2 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .password-input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        
        .password-input:focus {
            outline: none;
            border-color: #007bff;
        }
        
        .password-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 10px;
        }
        
        .password-btn:hover {
            background-color: #0056b3;
        }
        
        .error-message {
            color: #dc3545;
            margin-top: 10px;
            font-size: 14px;
        }
        
        /* Main content styles */
        #mainContent {
            display: none;
        }
        
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-top: 20px; 
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        
        th { 
            background-color: #f8f9fa; 
            font-weight: bold;
            color: #333;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9ecef;
        }
        
        h2 { 
            color: #333; 
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 0;
        }
        
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
            margin-top: 10px;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <!-- Password Protection Overlay -->
    <div id="passwordOverlay">
        <div class="password-container">
            <h2>🔒 Admin Access Required</h2>
            <p>Please enter the admin password to continue:</p>
            <input type="password" id="passwordInput" class="password-input" placeholder="Enter password..." maxlength="50">
            <br>
            <button onclick="checkPassword()" class="password-btn">Access Panel</button>
            <button onclick="clearInput()" class="password-btn" style="background-color: #6c757d;">Clear</button>
            <div id="errorMessage" class="error-message"></div>
        </div>
    </div>

    <!-- Main Content (Hidden by default) -->
    <div id="mainContent">
        <h2>
            Total Registered Users: <?php echo $total_users; ?>

            <div style="clear: both;"></div>
        </h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>DOB</th>
                    <th>Address</th>
                    <th>Signup Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['contact']); ?></td>
                        <td><?php echo htmlspecialchars($user['dob']); ?></td>
                        <td><?php echo htmlspecialchars($user['address']); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Configuration - Change this to your desired password
        const ADMIN_PASSWORD = "Anant@123"; // Set your admin password here
        
        // Session timeout (in minutes)
        const SESSION_TIMEOUT = 30;
        
        // Check if user is already authenticated
        window.onload = function() {
            checkAuthentication();
            
            // Add enter key listener for password input
            document.getElementById('passwordInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    checkPassword();
                }
            });
        };
        
        function checkAuthentication() {
            const authTime = sessionStorage.getItem('adminAuthTime');
            const currentTime = new Date().getTime();
            
            if (authTime && (currentTime - authTime) < (SESSION_TIMEOUT * 60 * 1000)) {
                // User is still authenticated
                showMainContent();
            } else {
                // Clear expired session
                sessionStorage.removeItem('adminAuthTime');
                showPasswordPrompt();
            }
        }
        
        function checkPassword() {
            const enteredPassword = document.getElementById('passwordInput').value;
            const errorDiv = document.getElementById('errorMessage');
            
            if (enteredPassword === '') {
                showError('Please enter a password.');
                return;
            }
            
            if (enteredPassword === ADMIN_PASSWORD) {
                // Correct password
                sessionStorage.setItem('adminAuthTime', new Date().getTime());
                showMainContent();
                clearError();
            } else {
                // Wrong password
                showError('Incorrect password. Access denied.');
                document.getElementById('passwordInput').value = '';
                
                // Add a small delay and shake effect for better UX
                document.querySelector('.password-container').style.animation = 'shake 0.5s';
                setTimeout(() => {
                    document.querySelector('.password-container').style.animation = '';
                }, 500);
            }
        }
        
        function showMainContent() {
            document.getElementById('passwordOverlay').style.display = 'none';
            document.getElementById('mainContent').style.display = 'block';
            document.body.style.overflow = 'auto';
        }
        
        function showPasswordPrompt() {
            document.getElementById('passwordOverlay').style.display = 'flex';
            document.getElementById('mainContent').style.display = 'none';
            document.body.style.overflow = 'hidden';
            document.getElementById('passwordInput').focus();
        }
        
        function logout() {
            sessionStorage.removeItem('adminAuthTime');
            document.getElementById('passwordInput').value = '';
            clearError();
            showPasswordPrompt();
        }
        
        function clearInput() {
            document.getElementById('passwordInput').value = '';
            clearError();
        }
        
        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
        }
        
        function clearError() {
            document.getElementById('errorMessage').textContent = '';
        }
        
        // Add CSS animation for shake effect
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);
        
        // Prevent right-click context menu and common keyboard shortcuts
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        document.addEventListener('keydown', function(e) {
            // Disable F12, Ctrl+Shift+I, Ctrl+U, Ctrl+S
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u') ||
                (e.ctrlKey && e.key === 's')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>