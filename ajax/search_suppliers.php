<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) < 1) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT name FROM suppliers WHERE name LIKE :query LIMIT 10");
    $stmt->execute([':query' => "%$query%"]);
    $suppliers = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode($suppliers);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
