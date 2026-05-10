<?php
$configs = include('config.php');
$conn = new mysqli($configs['dbservername'], $configs['dbusername'], $configs['dbpassword'], $configs['dbname']);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>