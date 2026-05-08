<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$categories = fetch_categories($pdo);
$latestBlogs = $pdo->query(
    'SELECT id, title, slug, short_description, image, category, published_at
     FROM blogs ORDER BY published_at DESC, id DESC LIMIT 12'
)->fetchAll();
$totalCount = (int) $pdo->query('SELECT COUNT(*) FROM blogs')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobYaari - Career Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="public-home">

<header class="site-header">
    <div class="container nav-shell">
        <a href="index.php" class="brand">JobYaari<span>Blogs</span></a>
        <nav class="top-nav">
            <a href="index.php" aria-current="page">Home</a>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <form class="search-filter-bar search-filter-bar--top" id="search-filter-bar" onsubmit="return false;">
            <div class="search-main-row">
                <div class="field-group field-group--search field-group--search-main field-group--inline">
                    <input type="search" id="search-input" name="q" placeholder="Search jobs, results, admit cards..." autocomplete="off">
                </div>
                <div class="field-group field-group--inline field-group--compact">
                    <select id="category-filter" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= e($category) ?>"><?= e($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field-group field-group--inline field-group--compact">
                    <input type="date" id="published-date" name="date">
                </div>
                <div class="search-actions">
                    <button type="button" id="search-button" class="btn btn-primary btn-search">Search</button>
                    <button type="button" id="reset-filters" class="btn btn-secondary">Reset</button>
                </div>
            </div>
            <div class="filter-feedback" id="filter-feedback" aria-live="polite"></div>
        </form>
    </div>

    <section class="blogs-section">
        <div class="container">
            <div class="section-head">
                <div id="results-meta"><?= $totalCount ?> post<?= $totalCount !== 1 ? 's' : '' ?></div>
            </div>

            <div id="blog-results" class="blog-grid">
                <?php if ($latestBlogs): ?>
                    <?php foreach ($latestBlogs as $blog): ?>
                        <?= render_blog_card($blog) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No posts available right now.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<div id="toast" class="toast"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
