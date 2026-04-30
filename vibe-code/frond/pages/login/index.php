<?php
session_start();
require_once("../../includes/db_connection.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            header("Location: ../dashboard/index.php");
            exit;

        } else {
            $message = "Incorrect password.";
        }

    } else {
        $message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>

<h1>Login</h1>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>

<p><?= $message ?></p>

<a href="../register/index.php">Register here</a>

</body>
</html>
