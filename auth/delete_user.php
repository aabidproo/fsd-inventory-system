<?php
session_start();

// ONLY 'admin' (Super Admin) can delete users
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/db_connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    // Check if we are trying to delete the 'admin' user
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['username'] === 'admin') {
            header("Location: users.php?error=The+Super+Admin+account+cannot+be+deleted.");
            exit;
        }

        $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            header("Location: users.php?msg=User+deleted+successfully.");
        } else {
            header("Location: users.php?error=Failed+to+delete+user.");
        }
        $delete_stmt->close();
    } else {
        header("Location: users.php?error=User+not+found.");
    }
    $stmt->close();
} else {
    header("Location: users.php");
}

$conn->close();
exit;
?>
