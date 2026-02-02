<?php
require_once 'auth/authenticate.php';
require_once 'includes/db_connect.php'; 
include 'includes/header.php';
?>

<style>
    .autocomplete-wrapper {
        position: relative;
        width: 100%;
    }
    .autocomplete-dropdown {
        position: absolute !important;
        top: 100%;
        left: 0;
        width: 100%;
        z-index: 9999 !important;
        background: #ffffff !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 4px !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        display: none;
        max-height: 250px;
        overflow-y: auto;
        margin-top: -15px; 
    }
    .suggestion-item {
        padding: 12px 16px;
        cursor: pointer;
        font-size: 0.9rem;
        color: var(--text-main);
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
    }
    .suggestion-item:hover {
        background: #f8fafc !important;
        color: var(--primary-color) !important;
    }
    .suggestion-item:last-child {
        border-bottom: none;
    }
</style>

<div class="search-section">
    <h2>Search Products</h2>
    <form method="GET" class="search-form-grid">
        <div class="filter-group">
            <label>Supplier Name</label>
            <div class="autocomplete-wrapper">
                <input type="text" id="supplier-input" name="supplier" placeholder="e.g. Supplier Name" value="<?= htmlspecialchars($_GET['supplier'] ?? '') ?>" autocomplete="off">
                <div id="autocomplete-suggestions" class="autocomplete-dropdown"></div>
            </div>
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
        $params = [];

        if (!empty($_GET['supplier'])) {
            $where[] = "s.name LIKE ?";
            $params[] = "%" . $_GET['supplier'] . "%";
        }
        if (!empty($_GET['min_price'])) {
            $where[] = "p.price >= ?";
            $params[] = $_GET['min_price'];
        }
        if (!empty($_GET['max_price'])) {
            $where[] = "p.price <= ?";
            $params[] = $_GET['max_price'];
        }
        if (!empty($_GET['min_stock'])) {
            $where[] = "p.stock >= ?";
            $params[] = $_GET['min_stock'];
        }
        if (!empty($_GET['max_stock'])) {
            $where[] = "p.stock <= ?";
            $params[] = $_GET['max_stock'];
        }

        $where_clause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $sql = "SELECT p.id, p.name, s.name AS supplier, p.price, p.stock 
                FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id $where_clause";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt; 
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
        
        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch()) {
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

<script src="assets/js/autocomplete_suppliers.js"></script>
<?php 
include 'includes/footer.php'; 
?>