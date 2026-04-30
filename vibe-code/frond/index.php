<?php
session_start();

// If user is logged in, redirect to dashboard
if (isset($_SESSION["user_id"])) {
    header("Location: pages/dashboard/index.php");
    exit;
}

// Otherwise show login page
header("Location: pages/login/index.php");
exit;
?>
