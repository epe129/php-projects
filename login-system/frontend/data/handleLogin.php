<?php
session_set_cookie_params([
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();
$configs = include('database.php');
$name = $email = $password = $db_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    
    if (!isset($_POST["csrf_token"], $_SESSION["csrf_token"]) || !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
        header("Location: ../login/index.php"); 
        exit();
    } else {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("SELECT nimi, pword FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($name, $db_password);
                $stmt->fetch();

                if (password_verify($password, $db_password)) {
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
    header("Location: ../login/index.php"); 
    exit();
}
?>
