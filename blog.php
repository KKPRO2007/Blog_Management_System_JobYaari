<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$slug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';
$blog = $slug !== '' ? find_blog_by_slug($pdo, $slug) : null;
$commentError = '';

if ($blog && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $authorName = trim((string) ($_POST['author_name'] ?? ''));
    $commentBody = trim((string) ($_POST['comment_body'] ?? ''));

    if ($commentBody === '') {
        $commentError = 'Comment is required.';
    } else {
        save_blog_comment(
            $pdo,
            (int) $blog['id'],
            $authorName !== '' ? $authorName : 'Anonymous User',
            $commentBody
        );
        header('Location: blog.php?slug=' . urlencode($slug) . '#comments');
        exit;
    }
}

if (!$blog) {
    http_response_code(404);
}

$comments = $blog ? fetch_comments_for_blog($pdo, (int) $blog['id']) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $blog ? e($blog['title']) . ' - JobYaari' : 'Not Found - JobYaari' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="public-detail">

<header class="site-header">
    <div class="container nav-shell">
        <a href="index.php" class="brand">JobYaari<span>Blogs</span></a>
        <nav class="top-nav">
            <a href="index.php">Blogs</a>
        </nav>
    </div>
</header>

<main class="blog-detail-page">
    <div class="container">
        <a href="index.php" class="back-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Back to blogs
        </a>

        <?php if (!$blog): ?>
            <div class="detail-card">
                <span class="eyebrow">404 - Not Found</span>
                <h1>Post not found</h1>
                <p class="detail-intro">The blog post you're looking for doesn't exist or may have been removed.</p>
                <a href="index.php" class="btn btn-primary" style="width:auto;display:inline-flex">Back to blog</a>
            </div>
        <?php else: ?>
            <article class="detail-card">
                <div class="detail-meta">
                    <span class="meta-chip cat"><?= e($blog['category']) ?></span>
                    <span class="meta-chip date">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:4px"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <?= e(format_date($blog['published_at'])) ?>
                    </span>
                </div>

                <h1><?= e($blog['title']) ?></h1>

                <?php if (!empty($blog['short_description'])): ?>
                    <p class="detail-intro"><?= e($blog['short_description']) ?></p>
                <?php endif; ?>

                <?php if (!empty($blog['image'])): ?>
                    <img src="<?= e($blog['image']) ?>" alt="<?= e($blog['title']) ?>" class="detail-image">
                <?php endif; ?>

                <div class="detail-content">
                    <?= $blog['content'] /* stored as HTML from rich editor */ ?>
                </div>

                <section class="comments-section" id="comments">
                    <div class="comments-section__head">
                        <div>
                            <span class="eyebrow">Community</span>
                            <h2>Comments</h2>
                        </div>
                        <span class="comments-count"><?= count($comments) ?> comment<?= count($comments) !== 1 ? 's' : '' ?></span>
                    </div>

                    <?php if ($commentError !== ''): ?>
                        <div class="alert alert--error"><?= e($commentError) ?></div>
                    <?php endif; ?>

                    <form method="post" class="comment-form">
                        <label class="field-stack">
                            <span>Name</span>
                            <input type="text" name="author_name" placeholder="Anonymous User">
                        </label>
                        <label class="field-stack">
                            <span>Comment</span>
                            <textarea name="comment_body" rows="4" placeholder="Share your thoughts on this post..."></textarea>
                        </label>
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </form>

                    <div class="comments-list">
                        <?php if (!$comments): ?>
                            <div class="empty-state comments-empty">No comments yet. Be the first to comment.</div>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <article class="comment-card">
                                    <div class="comment-card__head">
                                        <strong><?= e($comment['author_name']); ?></strong>
                                        <span><?= e(format_date($comment['created_at'])); ?></span>
                                    </div>
                                    <p><?= nl2br(e($comment['comment_body'])); ?></p>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>
            </article>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
