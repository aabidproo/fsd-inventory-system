<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: /inventory-system/auth/login.php");
    exit;
}


$_SESSION['last_activity'] = time();
?>