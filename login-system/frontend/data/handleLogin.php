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
    if ($name != "" and $password != "") {        
        $sql = "SELECT * FROM mydata WHERE fname = '$name' AND pword = MD5('$password')";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $_SESSION["name"] = "$name";
            header("Location: ../main/index.php"); 
            exit;
        } else {
            echo "<h1>Invalid email or password</h1>";
        }
    }   
}
?>
