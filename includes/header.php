<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';
$csrf_token = generate_csrf_token();

// Dynamically calculate the base path to handle subdirectories
$current_path = $_SERVER['PHP_SELF'];
$base_pos = strpos($current_path, '/inventory-system/');
if ($base_pos !== false) {
    $after_base = substr($current_path, $base_pos + strlen('/inventory-system/'));
    $depth = substr_count($after_base, '/');
    $path_prefix = str_repeat('../', $depth);
} else {
    $path_prefix = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory & Stock Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $path_prefix ?>assets/css/style.css">
    <script>const BASE_PATH = '<?= $path_prefix ?>';</script>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-main">
                <div class="branding">
                    <h1>Inventory & Stock Tracking System</h1>
                    <p class="subtitle">Useful for shops or warehouses</p>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-info">
                        <span>Welcome, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></strong></span>
                        <a href="<?= $path_prefix ?>auth/logout.php" class="logout-btn">Logout</a>
                    </div>
                <?php endif; ?>
            </div>

            <nav>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= $path_prefix ?>index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">Dashboard</a>
                    <a href="<?= $path_prefix ?>products/list.php" class="<?= (strpos($_SERVER['PHP_SELF'], 'products') !== false) ? 'active' : '' ?>">Products</a>
                    <a href="<?= $path_prefix ?>suppliers/list.php" class="<?= (strpos($_SERVER['PHP_SELF'], 'suppliers') !== false) ? 'active' : '' ?>">Suppliers</a>
                    <a href="<?= $path_prefix ?>search.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'search.php') ? 'active' : '' ?>">Search</a>
                    <?php if ($_SESSION['username'] === 'admin'): ?>
                        <a href="<?= $path_prefix ?>auth/users.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'users.php' || basename($_SERVER['PHP_SELF']) == 'register.php') ? 'active' : '' ?>">Users</a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container">
        <main>