<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    // Optional: set supplier_id = NULL in products (or do nothing - foreign key can allow NULL)
    $conn->query("UPDATE products SET supplier_id = NULL WHERE supplier_id = $id");
    
    // Delete supplier
    $conn->query("DELETE FROM suppliers WHERE id = $id");
}

header("Location: list.php");
exit;
?>