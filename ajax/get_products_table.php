<?php
require_once __DIR__ . '/../includes/db_connect.php';

$result = $conn->query("
    SELECT p.id, p.name, s.name AS supplier_name, p.price, p.stock, p.low_stock_threshold
    FROM products p
    LEFT JOIN suppliers s ON p.supplier_id = s.id
    ORDER BY p.name
");

echo '<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Supplier</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $is_low = ($row['stock'] <= $row['low_stock_threshold']) ? ' class="low-stock"' : '';
        echo "<tr$is_low>";
        echo "<td>{$row['id']}</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . ($row['supplier_name'] ? htmlspecialchars($row['supplier_name']) : '-') . "</td>";
        echo "<td>" . number_format($row['price'], 2) . "</td>";
        echo "<td>{$row['stock']}</td>";
        echo "<td>
            <a href='/inventory-system/products/edit.php?id={$row['id']}' class='action'>Edit</a> |
            <a href='/inventory-system/products/delete.php?id={$row['id']}' class='action delete' onclick='return confirm(\"Delete this product?\")'>Delete</a>
        </td>";
        echo "</tr>";
    }
} else {
    echo '<tr><td colspan="6" style="text-align:center;padding:30px;">No products found.</td></tr>';
}

echo '</tbody></table>';

$conn->close();
?>