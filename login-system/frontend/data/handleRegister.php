<?php
$configs = include('database.php');
$name = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $hashedPassword = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT);

    $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();


    if ($checkEmailStmt->num_rows > 0) {
        echo "Email ID already exists";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (nimi, email, pword) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            header("Location: ../main/index.php"); 
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

}