<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function admin_logged_in(): bool
{
    return isset($_SESSION['admin_id']);
}

function require_admin_login(): void
{
    if (!admin_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function attempt_admin_login(PDO $pdo, string $email, string $password): bool
{
    $statement = $pdo->prepare('SELECT id, name, email, password FROM admins WHERE email = :email LIMIT 1');
    $statement->execute(['email' => $email]);
    $admin = $statement->fetch();

    if (!$admin || !hash_equals($admin['password'], hash('sha256', $password))) {
        return false;
    }

    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_email'] = $admin['email'];

    return true;
}

function logout_admin(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
