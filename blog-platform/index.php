<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ./frondend/dashboard.php');
} else {
    header('Location: ./frondend/login.php');
}
exit;
