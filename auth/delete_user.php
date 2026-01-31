<?php
session_start();

// ONLY 'admin' (Super Admin) can delete users
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$token = $_GET['token'] ?? '';

if (!verify_csrf_token($token)) {
    handle_csrf_failure();
}

if ($id > 0) {
    // Check if we are trying to delete the 'admin' user
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['username'] === 'admin') {
            header("Location: users.php?error=The+Super+Admin+account+cannot+be+deleted.");
            exit;
        }

        $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        
        if ($delete_stmt->execute([':id' => $id])) {
            header("Location: users.php?msg=User+deleted+successfully.");
        } else {
            header("Location: users.php?error=Failed+to+delete+user.");
        }
        $delete_stmt = null;
    } else {
        header("Location: users.php?error=User+not+found.");
    }
    $stmt = null;
} else {
    header("Location: users.php");
}

exit;
?>
