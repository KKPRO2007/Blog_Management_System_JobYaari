<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');

$conditions = [];
$params = [];

$search = trim((string) ($_GET['q'] ?? ''));
$category = trim((string) ($_GET['category'] ?? ''));
$date = trim((string) ($_GET['date'] ?? ''));

if ($search !== '') {
    $conditions[] = '(title LIKE :search OR short_description LIKE :search OR category LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

if ($category !== '') {
    $conditions[] = 'category = :category';
    $params['category'] = $category;
}

if ($date !== '') {
    $conditions[] = 'DATE(published_at) = :date';
    $params['date'] = $date;
}

$sql = 'SELECT id, title, slug, short_description, image, category, published_at FROM blogs';
if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY published_at DESC, id DESC';

$statement = $pdo->prepare($sql);
$statement->execute($params);
$blogs = $statement->fetchAll();

$html = '';
foreach ($blogs as $blog) {
    $html .= render_blog_card($blog);
}

if ($html === '') {
    $html = '<div class="empty-state">No blogs matched your search or filters.</div>';
}

echo json_encode([
    'count' => count($blogs),
    'html' => $html,
]);
