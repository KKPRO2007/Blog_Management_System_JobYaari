<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$title = trim((string) ($_POST['title'] ?? ''));
$category = trim((string) ($_POST['category'] ?? ''));
$shortDescription = trim((string) ($_POST['short_description'] ?? ''));
$content = trim((string) ($_POST['content'] ?? ''));
$publishedAt = trim((string) ($_POST['published_at'] ?? ''));
$imageUrl = trim((string) ($_POST['image_url'] ?? ''));

if ($title === '' || $category === '' || $shortDescription === '' || $content === '') {
    header('Location: dashboard.php');
    exit;
}

$finalImage = $imageUrl;

if (isset($_FILES['image_file']) && is_array($_FILES['image_file']) && ($_FILES['image_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
    $uploadDirectory = __DIR__ . '/../uploads';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0775, true);
    }

    $tmpName = (string) $_FILES['image_file']['tmp_name'];
    $originalName = (string) $_FILES['image_file']['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $safeFileName = slugify(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
    $targetPath = $uploadDirectory . '/' . $safeFileName;

    if (in_array($extension, $allowedExtensions, true) && move_uploaded_file($tmpName, $targetPath)) {
        $finalImage = 'uploads/' . $safeFileName;
    }
}

$publishedAt = $publishedAt !== '' ? $publishedAt . ' 00:00:00' : date('Y-m-d H:i:s');

if ($id > 0) {
    $slug = unique_slug($pdo, $title, $id);
    $statement = $pdo->prepare('
        UPDATE blogs
        SET title = :title, slug = :slug, category = :category, short_description = :short_description,
            content = :content, image = :image, published_at = :published_at, updated_at = NOW()
        WHERE id = :id
    ');
    $statement->execute([
        'id' => $id,
        'title' => $title,
        'slug' => $slug,
        'category' => $category,
        'short_description' => $shortDescription,
        'content' => $content,
        'image' => $finalImage,
        'published_at' => $publishedAt,
    ]);
} else {
    $slug = unique_slug($pdo, $title);
    $statement = $pdo->prepare('
        INSERT INTO blogs (title, slug, category, short_description, content, image, published_at, created_at, updated_at)
        VALUES (:title, :slug, :category, :short_description, :content, :image, :published_at, NOW(), NOW())
    ');
    $statement->execute([
        'title' => $title,
        'slug' => $slug,
        'category' => $category,
        'short_description' => $shortDescription,
        'content' => $content,
        'image' => $finalImage,
        'published_at' => $publishedAt,
    ]);
}

header('Location: dashboard.php?success=1');
exit;
