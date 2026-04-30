<?php
header("Content-Type: application/json");

$user_id = intval($_POST['user_id']);

$flask_url = "http://127.0.0.1:5000/generate-schedule";

$data = json_encode(["user_id" => $user_id]);

$options = [
    "http" => [
        "header"  => "Content-type: application/json\r\n",
        "method"  => "POST",
        "content" => $data
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($flask_url, false, $context);

echo $result;
?>