<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$token = $_GET['token'] ?? '';

if (!verify_csrf_token($token)) {
    handle_csrf_failure();
}

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $stmt = null;
}
header("Location: list.php");
exit;
?>