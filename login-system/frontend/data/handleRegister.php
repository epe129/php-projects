<?php
session_start();
$configs = include('../config.php');
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
        $sql = "INSERT INTO mydata (fname, email, pword) VALUES ('$name', '$email', MD5('$password'))";
        if ($conn->query($sql) === TRUE) {
            $_SESSION["name"] = "$name";
            header("Location: ../main/index.php"); 
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}