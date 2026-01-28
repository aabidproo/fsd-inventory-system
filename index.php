<?php
require_once 'auth/authenticate.php';
require_once 'includes/db_connect.php';

// Fetch stats
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_suppliers = $conn->query("SELECT COUNT(*) as count FROM suppliers")->fetch_assoc()['count'];
$total_stock = $conn->query("SELECT SUM(stock) as sum FROM products")->fetch_assoc()['sum'] ?? 0;
$low_stock_count = $conn->query("SELECT COUNT(*) as count FROM products WHERE stock <= low_stock_threshold")->fetch_assoc()['count'];
?>
<?php include 'includes/header.php'; ?>

<h2 style="margin-bottom: 32px;">Dashboard Overview</h2>

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-label">Total Products</span>
        <span class="stat-value"><?= $total_products ?></span>
        <a href="products/list.php" class="stat-link">View all products →</a>
    </div>
    <div class="stat-card">
        <span class="stat-label">Total Suppliers</span>
        <span class="stat-value"><?= $total_suppliers ?></span>
        <a href="suppliers/list.php" class="stat-link">Manage suppliers →</a>
    </div>
    <div class="stat-card">
        <span class="stat-label">Total Stock Items</span>
        <span class="stat-value"><?= number_format($total_stock) ?></span>
        <div class="stat-link" style="color: var(--text-muted); opacity: 0.7;">Units in warehouse</div>
    </div>
    <div class="stat-card <?= $low_stock_count > 0 ? 'alert-card' : '' ?>">
        <span class="stat-label">Low Stock Alerts</span>
        <span class="stat-value"><?= $low_stock_count ?></span>
        <a href="products/list.php" class="stat-link"><?= $low_stock_count > 0 ? 'Action required →' : 'Stock levels healthy' ?></a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>