<?php
session_start();
require_once("../../includes/db_connection.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $message = "Registration successful! You can now login.";
    } else {
        $message = "Email already exists.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>

<h1>Register</h1>

<form method="POST">
    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Register</button>
</form>

<p><?= $message ?></p>

<a href="../login/index.php">Login here</a>

</body>
</html>
