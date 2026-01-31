<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0)
    die("Invalid supplier ID.");

$stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = :id");
$stmt->execute([':id' => $id]);
$supplier = $stmt->fetch();

if (!$supplier)
    die("Supplier not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/functions.php';
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        handle_csrf_failure();
    }
    $name = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name)) {
        $error = "Supplier name is required.";
    } else {
        $stmt = $conn->prepare("UPDATE suppliers SET name = :name, contact = :contact, address = :address WHERE id = :id");
        
        if ($stmt->execute([
            ':name'    => $name,
            ':contact' => $contact,
            ':address' => $address,
            ':id'      => $id
        ])) {
            header("Location: list.php?msg=Supplier+updated");
            exit;
        } else {
            $error = "Database error: Could not update supplier.";
        }
        $stmt = null;
    }
}
?>
<?php include '../includes/header.php'; ?>

<h2>Edit Supplier</h2>

<?php if (isset($error)): ?>
    <div class="alert" style="background:#f9dede; border-left-color:#c0392b;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Supplier Name <span style="color:#e74c3c;">*</span></label>
    <input type="text" name="name" required value="<?= htmlspecialchars($supplier['name']) ?>">

    <label>Contact (phone/email)</label>
    <input type="text" name="contact" value="<?= htmlspecialchars($supplier['contact']) ?>">

    <label>Address</label>
    <textarea name="address" rows="3"><?= htmlspecialchars($supplier['address']) ?></textarea>

    <input type="submit" value="Update Supplier">
</form>

<?php include '../includes/footer.php'; ?>