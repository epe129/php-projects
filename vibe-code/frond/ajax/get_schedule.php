<?php
header("Content-Type: application/json");

$user_id = intval($_GET['user_id']);
$flask_url = "http://127.0.0.1:5000/schedule/" . $user_id;

$result = file_get_contents($flask_url);

echo $result;
?>