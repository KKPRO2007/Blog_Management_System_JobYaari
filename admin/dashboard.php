<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

$editingId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editingBlog = null;

if ($editingId > 0) {
    $statement = $pdo->prepare('SELECT * FROM blogs WHERE id = :id LIMIT 1');
    $statement->execute(['id' => $editingId]);
    $editingBlog = $statement->fetch() ?: null;
}

$blogs = $pdo->query('SELECT * FROM blogs ORDER BY published_at DESC, id DESC')->fetchAll();
$categories = fetch_categories($pdo);
$stats = [
    'total' => count($blogs),
    'categories' => count($categories),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | JobYaari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">
    <header class="site-header">
        <div class="container nav-shell">
            <a href="../index.php" class="brand">JobYaari<span>Blogs</span></a>
            <nav class="top-nav">
                <a href="../index.php">Public Site</a>
                <a href="dashboard.php" aria-current="page">Admin Panel</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <main class="admin-page-shell">
        <div class="container admin-content-shell">
            <section class="admin-topbar admin-topbar--panel">
                <div class="admin-topbar__left">
                    <div>
                        <span class="eyebrow">Admin Dashboard</span>
                        <h1>Manage blog posts</h1>
                    </div>
                    <span class="admin-chip"><?= e((string) ($_SESSION['admin_name'] ?? 'Admin')); ?></span>
                </div>
                <div class="admin-topbar__actions">
                    <a href="dashboard.php" class="secondary-btn">All Posts</a>
                    <a href="dashboard.php" class="primary-btn">Add New</a>
                </div>
            </section>

            <div class="stats-row">
                <div class="stat-box">
                    <strong><?= $stats['total']; ?></strong>
                    <span>Total Blogs</span>
                </div>
                <div class="stat-box">
                    <strong><?= $stats['categories']; ?></strong>
                    <span>Categories</span>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert--success">Blog saved successfully.</div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert--success">Blog deleted successfully.</div>
            <?php endif; ?>

            <div class="admin-layout admin-layout--spacious">
                <section class="admin-panel">
                    <div class="table-card admin-panel-card">
                        <div class="table-card__head">
                            <div>
                                <span class="eyebrow">Library</span>
                                <h2>All blogs</h2>
                            </div>
                            <span class="admin-chip"><?= $stats['total']; ?> Posts</span>
                        </div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!$blogs): ?>
                                        <tr>
                                            <td colspan="4">No blog posts available.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($blogs as $blog): ?>
                                            <tr>
                                                <td class="table-title-cell"><?= e($blog['title']); ?></td>
                                                <td><span class="admin-chip"><?= e($blog['category']); ?></span></td>
                                                <td><?= e(format_date($blog['published_at'])); ?></td>
                                                <td class="table-actions">
                                                    <a href="dashboard.php?edit=<?= (int) $blog['id']; ?>" class="table-link">Edit</a>
                                                    <form action="blog-delete.php" method="post" onsubmit="return confirm('Delete this blog?');">
                                                        <input type="hidden" name="id" value="<?= (int) $blog['id']; ?>">
                                                        <button type="submit" class="table-link table-link--danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <aside class="editor-card editor-card--spacious">
                    <div class="editor-card__hero">
                        <span class="eyebrow"><?= $editingBlog ? 'Edit Blog' : 'New Blog'; ?></span>
                        <h2><?= $editingBlog ? 'Update blog post' : 'Create blog post'; ?></h2>
                        <p><?= $editingBlog ? 'Refine the existing content and publish the latest version.' : 'Draft a polished article with clear content, category, and media.'; ?></p>
                    </div>
                    <form action="blog-save.php" method="post" enctype="multipart/form-data" class="editor-form editor-form--spacious">
                        <input type="hidden" name="id" value="<?= (int) ($editingBlog['id'] ?? 0); ?>">
                        <label class="field-stack">
                            <span>Title</span>
                            <input type="text" name="title" required value="<?= e($editingBlog['title'] ?? ''); ?>">
                        </label>
                        <label class="field-stack">
                            <span>Category</span>
                            <select name="category" required>
                                <?php
                                $defaultCategories = ['Admit Card', 'Result', 'Latest Jobs', 'Syllabus', 'Exam Update'];
                                $allCategories = array_values(array_unique(array_merge($defaultCategories, $categories)));
                                foreach ($allCategories as $category):
                                ?>
                                    <option value="<?= e($category); ?>" <?= (($editingBlog['category'] ?? 'Latest Jobs') === $category) ? 'selected' : ''; ?>>
                                        <?= e($category); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="field-stack">
                            <span>Short Description</span>
                            <textarea name="short_description" rows="4" required><?= e($editingBlog['short_description'] ?? ''); ?></textarea>
                        </label>
                        <label class="field-stack">
                            <span>Content</span>
                            <textarea name="content" rows="12" required><?= e($editingBlog['content'] ?? ''); ?></textarea>
                        </label>
                        <div class="editor-form__row">
                            <label class="field-stack">
                                <span>Published Date</span>
                                <input type="date" name="published_at" value="<?= e(isset($editingBlog['published_at']) ? date('Y-m-d', strtotime($editingBlog['published_at'])) : date('Y-m-d')); ?>">
                            </label>
                            <label class="field-stack">
                                <span>Image Upload</span>
                                <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp">
                            </label>
                        </div>
                        <label class="field-stack">
                            <span>Image URL</span>
                            <input type="url" name="image_url" value="<?= e($editingBlog['image'] ?? ''); ?>" placeholder="https://example.com/image.jpg">
                        </label>
                        <div class="editor-actions">
                            <a href="dashboard.php" class="secondary-btn">Discard</a>
                            <button type="submit" class="primary-btn"><?= $editingBlog ? 'Update Blog' : 'Publish Blog'; ?></button>
                        </div>
                    </form>
                </aside>
            </div>
        </div>
    </main>
</body>
</html>
