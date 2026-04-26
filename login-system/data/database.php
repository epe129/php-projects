<?php
$configs = include('../config.php');
$dbservername = $configs['dbservername'];
$dbusername = $configs['dbusername'];
$dbpassword = $configs['dbpassword'];
$dbname = $configs['dbname'];
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE TABLE IF NOT EXISTS mydata (
  fname  TEXT NOT NULL,
  email  TEXT NOT NULL,
  pword  TEXT NOT NULL
)";
if ($conn->query($sql) === TRUE) {
  echo "Table MyGuests created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}
$conn->close();
?>