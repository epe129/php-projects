<?php
$configs = include('database.php');
$name = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    $stmt = $conn->prepare("SELECT pword FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $stmt2 = $conn->prepare("SELECT nimi FROM users WHERE email = ?");
            $stmt2->bind_param("s", $email);
            $stmt2->execute();
            $stmt2->bind_result($name);
            $stmt2->fetch();
            
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            header("Location: ../main/index.php"); 
            exit();
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "Email not found";
    }
}
?>
