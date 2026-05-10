<?php 
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>main</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            nav {
                background-color: #333;
                color: white;
                padding: 10px;
            }
            nav a {
                color: white;
                text-decoration: none;
                margin-right: 15px;
            }
            nav a:hover {
                text-decoration: underline;
            }
            .container {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .user-info {
                margin-bottom: 20px;
            }
            .logout-btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #dc3545;
                color: white;
                text-decoration: none;
                border-radius: 4px;
            }
            .logout-btn:hover {
                background-color: #c82333;
            }
        </style>
    </head>
    <body>
        <nav>
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="settings.php">Settings</a>
            <a href="../data/handleLogout.php" class="logout-btn">Logout</a>
        </nav>
        <div class="container">
            <h1>Welcome to the Main Page</h1>
            <div class="user-info">
                <?php
                $name = $_SESSION["name"];
                $email = $_SESSION["email"];
                echo "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
                // Note: Last login not stored in database; add if needed
                ?>
            </div>
            <!-- Add more content here as needed -->
        </div>
    </body>
</html>

