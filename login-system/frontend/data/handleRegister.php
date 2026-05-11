<?php
session_set_cookie_params([
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();
$configs = include('database.php');
$name = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $isAlphanumeric_name = preg_match('/^[a-zA-ZåäöÅÄÖ0-9\s-]{2,50}$/u', $name);

    if (!isset($_POST["csrf_token"], $_SESSION["csrf_token"]) || !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
        header("Location: ../index.php"); 
        exit();
    } else {
        if ($isAlphanumeric_name and filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
            $checkEmailStmt->bind_param("s", $email);
            $checkEmailStmt->execute();
            $checkEmailStmt->store_result();


            if ($checkEmailStmt->num_rows > 0) {
                header("Location: ../index.php"); 
                exit();
            } else {
                $stmt = $conn->prepare("INSERT INTO users (nimi, email, pword) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hashedPassword);

                if ($stmt->execute()) {
                    session_regenerate_id(true);
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
                    $conn->close();
                    header("Location: ../main/index.php"); 
                    exit();
                } 
            }
        } 
    }
    $conn->close();
    header("Location: ../index.php"); 
    exit();
}