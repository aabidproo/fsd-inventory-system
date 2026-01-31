<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$token = $_GET['token'] ?? '';

if (!verify_csrf_token($token)) {
    handle_csrf_failure();
}

if ($id > 0) {
    // Update products to set supplier_id to NULL
    $stmt1 = $conn->prepare("UPDATE products SET supplier_id = NULL WHERE supplier_id = :id");
    $stmt1->execute([':id' => $id]);
    $stmt1 = null;
    
    // Delete supplier
    $stmt2 = $conn->prepare("DELETE FROM suppliers WHERE id = :id");
    $stmt2->execute([':id' => $id]);
    $stmt2 = null;
}

header("Location: list.php");
exit;
?>