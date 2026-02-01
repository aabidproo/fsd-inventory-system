<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/functions.php';
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        handle_csrf_failure();
    }
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        
        if ($stmt->fetch()) {
            $error = "Username already taken.";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            
            if ($stmt->execute([':username' => $username, ':password' => $hashed_password])) {
                $success = "User account created successfully! You can manage them in the <a href='users.php'>user list</a>.";
            } else {
                $error = "Failed to create user. Please try again.";
            }
        }
        $stmt = null;
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Create New User</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success-message">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="post" class="login-form-direct">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Choose a username" required autofocus autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat your password" required autocomplete="new-password">
            </div>

            <input type="submit" value="Create Account">
        </form>

        <p style="margin-top: 24px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
            <a href="users.php" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">← Back to User List</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
