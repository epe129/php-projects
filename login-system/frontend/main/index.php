<?php 
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>main</title>
        <style>
        </style>
    </head>
    <body>
        <?php
        $name = $_SESSION["name"];
        echo "Hello " . $name;
        ?>        
    </body>
</html>

