<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/functions.php';
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        handle_csrf_failure();
    }
    $name = trim($_POST['name']);
    $supplier_id = (int) $_POST['supplier_id'];
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $threshold = (int) $_POST['threshold'];

    $stmt = $conn->prepare("UPDATE products SET name=:name, supplier_id=:supplier_id, price=:price, stock=:stock, low_stock_threshold=:threshold WHERE id=:id");
    
    if ($stmt->execute([
        ':name' => $name,
        ':supplier_id' => $supplier_id,
        ':price' => $price,
        ':stock' => $stock,
        ':threshold' => $threshold,
        ':id' => $id
    ])) {
        header("Location: list.php?msg=Product+updated");
        exit;
    } else {
        $error = "Error: Could not update product.";
    }
    $stmt = null;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch();
if (!$product)
    die("Product not found.");
?>
<?php include '../includes/header.php'; ?>

<h2>Edit Product</h2>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Product Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

    <label>Supplier</label>
    <select name="supplier_id" required>
        <?php
        $sup = $conn->query("SELECT id, name FROM suppliers ORDER BY name");
        while ($s = $sup->fetch()) {
            $sel = ($s['id'] == $product['supplier_id']) ? 'selected' : '';
            echo "<option value='{$s['id']}' $sel>" . htmlspecialchars($s['name']) . "</option>";
        }
        ?>
    </select>

    <label>Price</label>
    <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required>

    <label>Current Stock</label>
    <input type="number" name="stock" value="<?= $product['stock'] ?>" min="0" required>

    <label>Low Stock Warning Level</label>
    <input type="number" name="threshold" value="<?= $product['low_stock_threshold'] ?>" min="1" required>

    <input type="submit" value="Update Product">
</form>

<?php include '../includes/footer.php'; ?>