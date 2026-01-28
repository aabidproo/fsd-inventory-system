<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory & Stock Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/inventory-system/assets/css/style.css">
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
                        <a href="/inventory-system/auth/logout.php" class="logout-btn">Logout</a>
                    </div>
                <?php endif; ?>
            </div>

            <nav>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/inventory-system/index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">Dashboard</a>
                    <a href="/inventory-system/products/list.php" class="<?= (strpos($_SERVER['PHP_SELF'], 'products') !== false) ? 'active' : '' ?>">Products</a>
                    <a href="/inventory-system/suppliers/list.php" class="<?= (strpos($_SERVER['PHP_SELF'], 'suppliers') !== false) ? 'active' : '' ?>">Suppliers</a>
                    <a href="/inventory-system/search.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'search.php') ? 'active' : '' ?>">Search</a>
                    <?php if ($_SESSION['username'] === 'admin'): ?>
                        <a href="/inventory-system/auth/users.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'users.php' || basename($_SERVER['PHP_SELF']) == 'register.php') ? 'active' : '' ?>">Users</a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container">
        <main>