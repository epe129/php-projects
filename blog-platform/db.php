<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'blog_platform';
$mysqli = new mysqli($host, $user, $password, $database);
$createUsers = "CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
  username VARCHAR(100) NOT NULL UNIQUE,
  pword VARCHAR(255) NOT NULL
)";
$createVlogs = "CREATE TABLE IF NOT EXISTS vlogs (
  id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($mysqli->query($createUsers)) {
}
if ($mysqli->query($createVlogs)) {
}