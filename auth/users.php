<?php
session_start();

// ONLY 'admin' (Super Admin) can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/db_connect.php';
include '../includes/header.php';

// Fetch users
$result = $conn->query("SELECT id, username FROM users ORDER BY id ASC");
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2>Manage User Accounts</h2>
    <a href="register.php" class="logout-btn" style="color: var(--primary-color); border-color: var(--primary-color); background: #f5f3ff;">+ Add New User</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="success-message"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="error-message"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <strong><?= htmlspecialchars($row['username']) ?></strong>
                    <?php if ($row['username'] === 'admin'): ?>
                        <span style="font-size: 0.7rem; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 10px; margin-left: 8px; font-weight: 800;">SUPER ADMIN</span>
                    <?php endif; ?>
                </td>
                <td><?= ($row['username'] === 'admin') ? 'Admin' : 'Employee' ?></td>
                <td>
                    <?php if ($row['username'] !== 'admin'): ?>
                        <a href="delete_user.php?id=<?= $row['id'] ?>" class="action delete" onclick="return confirm('Are you sure you want to delete this user? They will lose all access immediately.')">Delete</a>
                    <?php else: ?>
                        <span style="color: var(--text-muted); font-size: 0.8rem; font-style: italic;">Protected</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
