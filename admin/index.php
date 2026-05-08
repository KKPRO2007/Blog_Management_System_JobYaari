<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

if (admin_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (attempt_admin_login($pdo, $email, $password)) {
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | JobYaari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">
    <main class="login-shell">
        <div class="login-card">
            <span class="eyebrow">Admin Access</span>
            <h1>Sign in to manage blogs</h1>
            <p>Use the seeded admin account from the SQL file, or update it before deployment.</p>
            <?php if ($error !== ''): ?>
                <div class="alert alert--error"><?= e($error); ?></div>
            <?php endif; ?>
            <form method="post" class="login-form">
                <label>
                    <span>Email</span>
                    <input type="email" name="email" required>
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" required>
                </label>
                <button type="submit" class="primary-btn">Login</button>
            </form>
            <a href="../index.php" class="back-link">Back to website</a>
        </div>
    </main>
</body>
</html>
