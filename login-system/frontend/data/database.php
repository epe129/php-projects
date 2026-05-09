<?php
$configs = include('config.php');
$dbservername = $configs['dbservername'];
$dbusername = $configs['dbusername'];
$dbpassword = $configs['dbpassword'];
$dbname = $configs['dbname'];
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>