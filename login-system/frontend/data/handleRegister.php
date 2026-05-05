<?php
session_start();
$configs = include('C:\xampp\htdocs\php-projects\login-system\frontend\data\config.php');
$name = "";
$email = "";
$password = "";
$dbservername = $configs['dbservername'];
$dbusername = $configs['dbusername'];
$dbpassword = $configs['dbpassword'];
$dbname = $configs['dbname'];
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $password = htmlspecialchars($_POST["password"]);
    $email = htmlspecialchars($_POST["email"]);
    if (empty($name) or empty($email) or empty($password)) {
        echo "<h1>Jokin kohta oli tyhjä</h1>";
    } else {
        $stmt = $conn->prepare("INSERT INTO mydata (fname, email, pword) VALUES (?, ?, MD5(?))");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION["name"] = $name;
            header("Location: ../main/index.php");
            exit;
            } else {
                echo "Error: " . $stmt->error;
         }
    }
}