<?php
session_start();

// If not logged in → redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: /inventory-system/auth/login.php");
    exit;
}

// Optional: You can add role check later, e.g. if admin only
// if ($_SESSION['role'] !== 'admin') { ... }

// Refresh last activity (optional timeout)
$_SESSION['last_activity'] = time();
?>