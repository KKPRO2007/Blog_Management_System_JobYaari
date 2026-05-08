CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS blogs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    short_description TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    published_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    blog_id INT UNSIGNED NOT NULL,
    author_name VARCHAR(120) NOT NULL,
    browser_name VARCHAR(120) DEFAULT NULL,
    comment_body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_comments_blog_id (blog_id)
);

INSERT INTO admins (name, email, password)
VALUES
('Admin User', 'admin@jobyaari.com', 'ac2bb70ca8e094bbcf05a8d864937390d3b71cb3d25271c33ac278cef90ba384')
ON DUPLICATE KEY UPDATE
name = VALUES(name),
password = VALUES(password),
email = VALUES(email);

INSERT INTO blogs (title, slug, category, short_description, content, image, published_at)
VALUES
(
    'SSC CGL 2025 Notification Released',
    'ssc-cgl-2025-notification-released',
    'Latest Jobs',
    'Staff Selection Commission has published the SSC CGL 2025 notification with key dates and eligibility details.',
    'The SSC CGL 2025 notification is now live. Candidates can review the eligibility criteria, vacancy details, and exam timeline before applying through the official portal.',
    'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=80',
    '2026-05-05 10:00:00'
),
(
    'UPSC Admit Card Download Guide',
    'upsc-admit-card-download-guide',
    'Admit Card',
    'A step-by-step guide for downloading the UPSC admit card and checking important exam day instructions.',
    'Visit the official UPSC website, open the admit card section, and download the hall ticket using your registration details. Keep a valid photo ID ready for the exam day.',
    'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
    '2026-05-03 09:30:00'
),
(
    'IBPS Result 2026 Declared',
    'ibps-result-2026-declared',
    'Result',
    'IBPS has declared the latest result update with direct instructions for checking the scorecard online.',
    'Candidates can log in to the official IBPS portal to view their scorecards, category cut-offs, and next-stage instructions for the recruitment process.',
    'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
    '2026-05-01 12:15:00'
),
(
    'RRB NTPC 2026 Syllabus Overview',
    'rrb-ntpc-2026-syllabus-overview',
    'Syllabus',
    'A simple overview of the RRB NTPC syllabus, important sections, and preparation focus areas.',
    'The RRB NTPC syllabus includes mathematics, reasoning, and general awareness sections. Smart preparation starts with understanding the pattern.',
    'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1200&q=80',
    '2026-04-29 08:10:00'
),
(
    'NEET 2026 Answer Key Release Window',
    'neet-2026-answer-key-release-window',
    'Answer Key',
    'Expected NEET answer key release schedule and what students should check before raising objections.',
    'The NEET answer key helps students estimate scores before the official result. Review the provisional key carefully.',
    'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1200&q=80',
    '2026-04-27 11:00:00'
),
(
    'BPSC Teacher Vacancy Update 2026',
    'bpsc-teacher-vacancy-update-2026',
    'Latest Jobs',
    'BPSC is expected to announce a fresh teacher recruitment update with subject-wise vacancy details.',
    'The upcoming BPSC teacher recruitment cycle is drawing strong interest from candidates across Bihar and nearby states.',
    'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=1200&q=80',
    '2026-04-25 15:45:00'
),
(
    'CUET UG Preparation Tips for Final Month',
    'cuet-ug-preparation-tips-final-month',
    'General',
    'A focused revision strategy for the final month before CUET UG, including time management tips.',
    'The final month before CUET should be about revision, mock tests, and eliminating weak areas.',
    'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=1200&q=80',
    '2026-04-22 17:20:00'
),
(
    'State PSC Interview Round Checklist',
    'state-psc-interview-round-checklist',
    'General',
    'A practical checklist to help candidates prepare for state PSC interview rounds with confidence.',
    'Interview preparation should cover current affairs, your academic background, and role-specific questions.',
    'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80',
    '2026-04-20 13:05:00'
)
ON DUPLICATE KEY UPDATE
title = VALUES(title),
category = VALUES(category),
short_description = VALUES(short_description),
content = VALUES(content),
image = VALUES(image),
published_at = VALUES(published_at);
