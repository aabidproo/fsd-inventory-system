<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';

$error = '';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/functions.php';
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        handle_csrf_failure();
    }
    $name    = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name)) {
        $error = "Supplier name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO suppliers (name, contact, address) VALUES (:name, :contact, :address)");
        
        if ($stmt->execute([':name' => $name, ':contact' => $contact, ':address' => $address])) {
            // Success 
            header("Location: list.php?msg=Supplier+added+successfully");
            exit;
        } else {
            $error = "Failed to add supplier.";
        }
        $stmt = null;
    }
    

    $form_name    = htmlspecialchars($name ?? '');
    $form_contact = htmlspecialchars($contact ?? '');
    $form_address = htmlspecialchars($address ?? '');
} else {

    $form_name = $form_contact = $form_address = '';
}
?>

<?php include '../includes/header.php'; ?>

<h2>Add New Supplier</h2>

<?php if (!empty($error)): ?>
    <div class="error-message">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <label>Supplier Name <span style="color:#e74c3c;">*</span></label>
    <input type="text" 
           name="name" 
           required 
           autofocus 
           value="<?= $form_name ?>">

    <label>Contact (phone / email)</label>
    <input type="text" 
           name="contact" 
           value="<?= $form_contact ?>">

    <label>Address</label>
    <textarea name="address" rows="3"><?= $form_address ?></textarea>

    <input type="submit" value="Save Supplier">
</form>

<p style="margin-top: 20px; font-size: 0.9em; color: #7f8c8d;">
    <a href="list.php">← Back to Suppliers List</a>
</p>

<?php include '../includes/footer.php'; ?>

<?php

$conn = null;
?>