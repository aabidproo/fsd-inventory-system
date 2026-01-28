<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id > 0) {
    $conn->query("DELETE FROM products WHERE id = $id");
}
header("Location: list.php");
exit;
?>