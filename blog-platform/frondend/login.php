<?php
session_start();
require_once 'db.php';

$message = $hashedPassword = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === '' || $password === '') {
        $message = 'Please enter both email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare('SELECT id, username, pword FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($userId, $username, $hashedPassword);

        if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        }

        $message = 'Invalid email or password.';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Blog App</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #eef2f7; 
            margin: 0; 
            padding: 40px; 
        }
        .container { 
            max-width: 400px; 
            margin: 0 auto; 
            background: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        input[type=text], input[type=password] { 
            width: 100%; 
            padding: 10px; 
            margin: 8px 0; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }
        button { 
            width: 100%; 
            padding: 10px; 
            background: #28a745; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        button:hover { 
            background: #218838; 
        }
        .message { 
            margin: 10px 0; 
            color: #d8000c; 
        }
        .small { 
            font-size: 0.9em; 
            margin-top: 10px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p class="small">New here? <a href="register.php">Register now</a></p>
    </div>
</body>
</html>
