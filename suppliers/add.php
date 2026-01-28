<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';

$error = '';  // Will store error message if any

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name)) {
        $error = "Supplier name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO suppliers (name, contact, address) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $contact, $address);

        if ($stmt->execute()) {
            // Success → redirect to list with success message
            header("Location: list.php?msg=Supplier+added+successfully");
            exit;
        } else {
            $error = "Failed to add supplier: " . $stmt->error;
        }
        $stmt->close();
    }
    
    // If we reach here → there was an error, keep form values
    $form_name    = htmlspecialchars($name ?? '');
    $form_contact = htmlspecialchars($contact ?? '');
    $form_address = htmlspecialchars($address ?? '');
} else {
    // Fresh form load
    $form_name = $form_contact = $form_address = '';
}
?>

<?php include '../includes/header.php'; ?>

<h2>Add New Supplier</h2>

<?php if (!empty($error)): ?>
    <div class="error-message">
        <?= $error ?>
    </div>
<?php endif; ?>

<form method="post">
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
// Optional: close connection (good practice, though PHP auto-closes at end)
$conn->close();
?>