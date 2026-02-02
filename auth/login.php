<?php
session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/db_connect.php';
    require_once '../includes/functions.php';

    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        handle_csrf_failure();
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        // Hardcoded superadmin login for college server compatibility
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id']    = 1; // Assuming 1 is the admin ID
            $_SESSION['username']   = 'admin';
            $_SESSION['last_activity'] = time();
            header("Location: ../index.php?msg=Login+successful+(Hardcoded)");
            exit;
        }

        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['username']   = $user['username'];
                $_SESSION['last_activity'] = time();

                header("Location: ../index.php?msg=Login+successful");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        $stmt = null;
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="login-form-direct">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required autofocus autocomplete="username">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            </div>

            <input type="submit" value="Sign In">
        </form>

        <div class="teacher-box" style="margin-top: 30px; border-style: solid; background: #fdfdfd;">
            <p style="margin: 0; font-size: 0.85rem; color: var(--text-muted); text-align: center;">
                <span style="display: block; margin-bottom: 5px; color: var(--text-main); font-weight: 600;">Default Superadmin Credentials</span>
                User: <code style="background: #f1f5f9; padding: 2px 4px; border-radius: 4px;">admin</code> &nbsp; 
                Pass: <code style="background: #f1f5f9; padding: 2px 4px; border-radius: 4px;">admin123</code>
            </p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>