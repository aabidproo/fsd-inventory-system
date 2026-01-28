<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $supplier_id = (int) $_POST['supplier_id'];
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $threshold = (int) $_POST['threshold'];

    $stmt = $conn->prepare("INSERT INTO products (name, supplier_id, price, stock, low_stock_threshold) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sidii", $name, $supplier_id, $price, $stock, $threshold);

    if ($stmt->execute()) {
        header("Location: list.php?msg=Product+added");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<?php include '../includes/header.php'; ?>

<h2>Add New Product</h2>

<?php if (isset($error)): ?>
    <div class="alert" style="background:#f9dede; border-left-color:#c0392b;"><?= $error ?></div>
<?php endif; ?>

<form method="post">
    <label>Product Name</label>
    <input type="text" name="name" required autofocus>

    <label>Supplier</label>
    <select name="supplier_id" required>
        <option value="">— Select Supplier —</option>
        <?php
        $sup = $conn->query("SELECT id, name FROM suppliers ORDER BY name");
        while ($s = $sup->fetch_assoc()) {
            echo "<option value='{$s['id']}'>" . htmlspecialchars($s['name']) . "</option>";
        }
        ?>
    </select>

    <label>Price</label>
    <input type="number" name="price" step="0.01" min="0" required>

    <label>Current Stock</label>
    <input type="number" name="stock" min="0" required>

    <label>Low Stock Warning Level</label>
    <input type="number" name="threshold" value="10" min="1" required>

    <input type="submit" value="Save Product">
</form>

<?php include '../includes/footer.php'; ?>