<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        $statement = $pdo->prepare('DELETE FROM blogs WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}

header('Location: dashboard.php?deleted=1');
exit;
