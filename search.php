<?php
require_once 'auth/authenticate.php';
require_once 'includes/db_connect.php'; // Correct path from root
include 'includes/header.php';
?>

<div class="search-section">
    <h2>Search Products</h2>
    <form method="GET" class="search-form-grid">
        <div class="filter-group">
            <label>Supplier Name</label>
            <input type="text" name="supplier" placeholder="e.g. Supplier Name" value="<?= htmlspecialchars($_GET['supplier'] ?? '') ?>">
        </div>
        <div class="filter-group">
            <label>Min Price</label>
            <input type="number" step="0.01" name="min_price" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
        </div>
        <div class="filter-group">
            <label>Max Price</label>
            <input type="number" step="0.01" name="max_price" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
        </div>
        <div class="filter-group">
            <label>Min Stock</label>
            <input type="number" name="min_stock" value="<?= htmlspecialchars($_GET['min_stock'] ?? '') ?>">
        </div>
        <div class="filter-group">
            <label>Max Stock</label>
            <input type="number" name="max_stock" value="<?= htmlspecialchars($_GET['max_stock'] ?? '') ?>">
        </div>
        <div class="filter-group action-group">
            <input type="submit" value="Search">
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
        $where = [];
        $types = "";
        $params = [];

        if (!empty($_GET['supplier'])) {
            $where[] = "s.name LIKE ?";
            $types .= "s";
            $params[] = "%" . $_GET['supplier'] . "%";
        }
        if (!empty($_GET['min_price'])) {
            $where[] = "p.price >= ?";
            $types .= "d";
            $params[] = $_GET['min_price'];
        }
        if (!empty($_GET['max_price'])) {
            $where[] = "p.price <= ?";
            $types .= "d";
            $params[] = $_GET['max_price'];
        }
        if (!empty($_GET['min_stock'])) {
            $where[] = "p.stock >= ?";
            $types .= "i";
            $params[] = $_GET['min_stock'];
        }
        if (!empty($_GET['max_stock'])) {
            $where[] = "p.stock <= ?";
            $types .= "i";
            $params[] = $_GET['max_stock'];
        }

        $where_clause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $sql = "SELECT p.id, p.name, s.name AS supplier, p.price, p.stock 
                FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id $where_clause";

        if (!empty($params)) {
             $stmt = $conn->prepare($sql);
             $stmt->bind_param($types, ...$params);
             $stmt->execute();
             $result = $stmt->get_result();
        } else {
             $result = $conn->query($sql);
        }

        echo "<h3>Results</h3>";
        echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>";
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["id"]) . "</td>
                        <td>" . htmlspecialchars($row["name"]) . "</td>
                        <td>" . htmlspecialchars($row["supplier"] ?? '-') . "</td>
                        <td>" . number_format($row["price"], 2) . "</td>
                        <td>" . htmlspecialchars($row["stock"]) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>No results found.</td></tr>";
        }
        echo "</tbody></table>";
    }
    ?>
    <p><a href="index.php">Back to Dashboard</a></p>
</div>

<?php 
$conn->close(); 
include 'includes/footer.php'; 
?>