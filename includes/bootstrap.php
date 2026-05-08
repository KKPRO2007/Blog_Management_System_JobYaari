<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$dbConfig = require __DIR__ . '/../config/database.php';

function db_connection(array $config): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['port'],
        $config['database'],
        $config['charset']
    );

    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?? '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'blog-post';
}

function format_date(string $date): string
{
    $timestamp = strtotime($date);

    return $timestamp ? date('d M Y', $timestamp) : $date;
}

function fetch_categories(PDO $pdo): array
{
    $statement = $pdo->query('SELECT DISTINCT category FROM blogs ORDER BY category ASC');

    return array_column($statement->fetchAll(), 'category');
}

function find_blog_by_slug(PDO $pdo, string $slug): ?array
{
    $statement = $pdo->prepare('SELECT * FROM blogs WHERE slug = :slug LIMIT 1');
    $statement->execute(['slug' => $slug]);
    $blog = $statement->fetch();

    return $blog ?: null;
}

function unique_slug(PDO $pdo, string $title, ?int $ignoreId = null): string
{
    $baseSlug = slugify($title);
    $slug = $baseSlug;
    $counter = 2;

    while (true) {
        $sql = 'SELECT id FROM blogs WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($ignoreId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $ignoreId;
        }

        $statement = $pdo->prepare($sql . ' LIMIT 1');
        $statement->execute($params);

        if (!$statement->fetch()) {
            return $slug;
        }

        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
}

function render_blog_card(array $blog): string
{
    $imageHtml = $blog['image']
        ? '<img src="' . e($blog['image']) . '" alt="' . e($blog['title']) . '" class="blog-card__image">'
        : '<div class="blog-card__placeholder">No Image</div>';

    return '
        <article class="blog-card">
            <a class="blog-card__visual" href="blog.php?slug=' . e($blog['slug']) . '">' . $imageHtml . '</a>
            <div class="blog-card__body">
                <span class="blog-card__category">' . e($blog['category']) . '</span>
                <h3><a href="blog.php?slug=' . e($blog['slug']) . '">' . e($blog['title']) . '</a></h3>
                <p>' . e($blog['short_description']) . '</p>
                <div class="blog-card__footer">
                    <span>' . e(format_date($blog['published_at'])) . '</span>
                    <a href="blog.php?slug=' . e($blog['slug']) . '">Read More</a>
                </div>
            </div>
        </article>
    ';
}

function ensure_comments_table(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS comments (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            blog_id INT UNSIGNED NOT NULL,
            author_name VARCHAR(120) NOT NULL,
            browser_name VARCHAR(120) DEFAULT NULL,
            comment_body TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_comments_blog_id (blog_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );
}

function seed_blogs(PDO $pdo): void
{
    $seedBlogs = [
        ['title' => 'SSC CGL 2025 Notification Released', 'slug' => 'ssc-cgl-2025-notification-released', 'category' => 'Latest Jobs', 'short_description' => 'Staff Selection Commission has published the SSC CGL 2025 notification with key dates and eligibility details.', 'content' => '<p>The SSC CGL 2025 notification is live with vacancy details, exam stages, and application dates. Candidates should review the official notification carefully before applying.</p><p>Make sure you verify eligibility, important documents, and deadlines before submission.</p>', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=80', 'published_at' => '2026-05-05 10:00:00'],
        ['title' => 'UPSC Admit Card Download Guide', 'slug' => 'upsc-admit-card-download-guide', 'category' => 'Admit Card', 'short_description' => 'A step-by-step guide for downloading the UPSC admit card and checking important exam day instructions.', 'content' => '<p>Visit the official UPSC portal, open the admit card section, and download the hall ticket using your registration details.</p><p>Carry the printed admit card and a valid photo ID to the exam center.</p>', 'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80', 'published_at' => '2026-05-03 09:30:00'],
        ['title' => 'IBPS Result 2026 Declared', 'slug' => 'ibps-result-2026-declared', 'category' => 'Result', 'short_description' => 'IBPS has declared the latest result update with direct instructions for checking the scorecard online.', 'content' => '<p>Candidates can log in to the official IBPS portal to view scorecards, cut-offs, and next-stage instructions.</p><p>Keep your registration details ready while checking the result.</p>', 'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80', 'published_at' => '2026-05-01 12:15:00'],
        ['title' => 'RRB NTPC 2026 Syllabus Overview', 'slug' => 'rrb-ntpc-2026-syllabus-overview', 'category' => 'Syllabus', 'short_description' => 'A simple overview of the RRB NTPC syllabus, important sections, and preparation focus areas.', 'content' => '<p>The RRB NTPC syllabus includes mathematics, reasoning, and general awareness sections. Smart preparation starts with understanding the pattern.</p><p>Create a subject-wise revision plan and solve previous-year papers regularly.</p>', 'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1200&q=80', 'published_at' => '2026-04-29 08:10:00'],
        ['title' => 'NEET 2026 Answer Key Release Window', 'slug' => 'neet-2026-answer-key-release-window', 'category' => 'Answer Key', 'short_description' => 'Expected NEET answer key release schedule and what students should check before raising objections.', 'content' => '<p>The NEET answer key helps students estimate scores before the official result. Review the provisional key carefully.</p><p>If you spot a mismatch, keep evidence ready before filing an objection.</p>', 'image' => 'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1200&q=80', 'published_at' => '2026-04-27 11:00:00'],
        ['title' => 'BPSC Teacher Vacancy Update 2026', 'slug' => 'bpsc-teacher-vacancy-update-2026', 'category' => 'Latest Jobs', 'short_description' => 'BPSC is expected to announce a fresh teacher recruitment update with subject-wise vacancy details.', 'content' => '<p>The upcoming BPSC teacher recruitment cycle is drawing strong interest from candidates across Bihar and nearby states.</p><p>Track the official portal for district-wise and subject-wise vacancy updates.</p>', 'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=1200&q=80', 'published_at' => '2026-04-25 15:45:00'],
        ['title' => 'CUET UG Preparation Tips for Final Month', 'slug' => 'cuet-ug-preparation-tips-final-month', 'category' => 'General', 'short_description' => 'A focused revision strategy for the final month before CUET UG, including time management tips.', 'content' => '<p>The final month before CUET should be about revision, mock tests, and eliminating weak areas.</p><p>Spend more time on accuracy and less on learning entirely new topics.</p>', 'image' => 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=1200&q=80', 'published_at' => '2026-04-22 17:20:00'],
        ['title' => 'State PSC Interview Round Checklist', 'slug' => 'state-psc-interview-round-checklist', 'category' => 'General', 'short_description' => 'A practical checklist to help candidates prepare for state PSC interview rounds with confidence.', 'content' => '<p>Interview preparation should cover current affairs, your academic background, and role-specific questions.</p><p>Practice short structured answers and keep your documents organized in advance.</p>', 'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80', 'published_at' => '2026-04-20 13:05:00'],
    ];

    $statement = $pdo->prepare(
        'INSERT INTO blogs (title, slug, category, short_description, content, image, published_at, created_at, updated_at)
         VALUES (:title, :slug, :category, :short_description, :content, :image, :published_at, NOW(), NOW())
         ON DUPLICATE KEY UPDATE
         title = VALUES(title),
         category = VALUES(category),
         short_description = VALUES(short_description),
         content = VALUES(content),
         image = VALUES(image),
         published_at = VALUES(published_at),
         updated_at = NOW()'
    );

    foreach ($seedBlogs as $blog) {
        $statement->execute($blog);
    }
}

function fetch_comments_for_blog(PDO $pdo, int $blogId): array
{
    $statement = $pdo->prepare('SELECT author_name, comment_body, created_at FROM comments WHERE blog_id = :blog_id ORDER BY created_at DESC, id DESC');
    $statement->execute(['blog_id' => $blogId]);

    return $statement->fetchAll();
}

function save_blog_comment(PDO $pdo, int $blogId, string $authorName, string $commentBody): void
{
    $statement = $pdo->prepare('INSERT INTO comments (blog_id, author_name, comment_body) VALUES (:blog_id, :author_name, :comment_body)');
    $statement->execute([
        'blog_id' => $blogId,
        'author_name' => $authorName,
        'comment_body' => $commentBody,
    ]);
}

$pdo = db_connection($dbConfig);
ensure_comments_table($pdo);
seed_blogs($pdo);
