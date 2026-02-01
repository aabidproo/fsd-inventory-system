<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';
?>
<?php include '../includes/header.php'; ?>

<?php
$sql = "SELECT name, stock, low_stock_threshold 
        FROM products 
        WHERE stock <= low_stock_threshold";
$alerts = $conn->query($sql);

if ($alerts->rowCount() > 0): ?>
    <div class="alert" style="margin-bottom: 30px;">
        <strong style="display: block; margin-bottom: 10px;">Priority Resupply List</strong>
        <ul style="margin: 0; padding-left: 20px;">
            <?php while ($row = $alerts->fetch()): ?>
                <li style="margin-bottom: 5px;">
                    <strong><?= htmlspecialchars($row['name']) ?></strong>: 
                    Only <span style="font-weight:800; color:var(--danger);"><?= $row['stock'] ?></span> left 
                    (Threshold: <?= $row['low_stock_threshold'] ?>)
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
<?php endif; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="margin: 0;">Products List</h2>
    <a href="add.php" class="logout-btn" style="color: var(--primary-color); border-color: var(--primary-color); background: #f5f3ff;">+ Add Product</a>
</div>

<div id="products-table">
    <?php 

    include '../ajax/get_products_table.php'; 
    ?>
</div>

<?php include '../includes/footer.php'; ?>
