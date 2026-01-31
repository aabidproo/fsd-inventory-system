<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php';
?>
<?php include '../includes/header.php'; ?>

<?php
// Show success or error messages from redirects
if (isset($_GET['msg'])) {
    echo '<div class="success-message">'
        . htmlspecialchars($_GET['msg'])
        . '</div>';
}
if (isset($_GET['error'])) {
    echo '<div class="error-message">'
        . htmlspecialchars($_GET['error'])
        . '</div>';
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="margin: 0;">Suppliers List</h2>
    <a href="add.php" class="logout-btn" style="color: var(--primary-color); border-color: var(--primary-color); background: #f5f3ff;">+ Add Supplier</a>
</div>

<?php
$result = $conn->query("
    SELECT id, name, contact, address 
    FROM suppliers 
    ORDER BY name
");

if ($result->rowCount() > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['contact'] ?: '-') ?></td>
                    <td><?= htmlspecialchars($row['address'] ?: '-') ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="action">Edit</a> |
                        <a href="delete.php?id=<?= $row['id'] ?>&token=<?= $csrf_token ?>" class="action delete"
                            onclick="return confirm('Delete this supplier? Products linked to it may become unlinked.')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center; padding:30px; color:#7f8c8d;">
        No suppliers found. <a href="add.php">Add your first supplier</a>.
    </p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>