# JobYaari Blog Management System

PHP/MySQL Blog Management System built for the JobYaari developer assessment. It includes a public blog listing page, blog detail page, admin login, blog CRUD, and AJAX filtering with jQuery.

## Features

- Public blog listing page backed by MySQL
- Blog detail page for full content
- Live search with jQuery AJAX
- AJAX category filter without page reload
- AJAX date range filter without page reload
- Responsive layout for mobile and laptop
- Admin login with PHP sessions
- Admin can add, edit, and delete blogs
- Image support via URL or local upload

## Tech Stack

- PHP
- MySQL
- HTML5
- CSS3
- jQuery / AJAX

## Project Structure

```text
admin/
ajax/
assets/
config/
database/
includes/
uploads/
index.php
blog.php
```

## Installation

### Option 1: XAMPP

1. Install `XAMPP`.
2. Copy this project folder into `C:\xampp\htdocs\`.
3. Open the XAMPP Control Panel and start `Apache` and `MySQL`.
4. Visit `http://localhost/phpmyadmin`.
5. Create a database named `blog_management_system`.
6. Import [`database/blog_management_system.sql`](database/blog_management_system.sql).
7. Open [`config/database.php`](config/database.php) and confirm these values match your local MySQL setup:
   - `host`
   - `port`
   - `database`
   - `username`
   - `password`
8. Open the project in your browser:
   - `http://localhost/Blog_Management_System_JobYaari/`

### Option 2: Laragon

1. Install `Laragon`.
2. Copy this project folder into `C:\laragon\www\`.
3. Start `Apache` and `MySQL` from Laragon.
4. Open `http://localhost/phpmyadmin`.
5. Create a database named `blog_management_system`.
6. Import [`database/blog_management_system.sql`](database/blog_management_system.sql).
7. Open [`config/database.php`](config/database.php) and update the database credentials if needed.
8. Open the project in your browser:
   - `http://localhost/Blog_Management_System_JobYaari/`

### Final Checks

1. Make sure the `uploads/` folder is writable.
2. Visit the homepage and confirm blogs are loading from the database.
3. Visit the admin login page and test blog create, edit, and delete.

## Admin Login

- URL: `/admin/index.php`
- Email: `admin@jobyaari.com`
- Password: `jobyaari123`

## AJAX Filtering

The public homepage uses jQuery AJAX to fetch filtered blog results from [`ajax/blogs.php`](ajax/blogs.php) without reloading the page.

Supported filters:

- Search by title, category, or short description
- Filter by category
- Filter by date range

## Database Notes

- Seeded admin account is included in the SQL file.
- Sample blog posts are included for demo/testing.
- Uploaded images are stored in `uploads/`.

## Deployment Notes

This project is suitable for free PHP/MySQL hosting such as:

- InfinityFree
- 000webhost
- Render with PHP support

Before deployment:

1. Import the SQL file on the live database.
2. Update `config/database.php` with live database credentials.
3. Confirm the `uploads/` directory is writable.
4. Share the live home URL, GitHub repo URL, and admin login credentials in your submission.

## Important Files

- Public homepage: [`index.php`](index.php)
- Blog detail page: [`blog.php`](blog.php)
- AJAX filter endpoint: [`ajax/blogs.php`](ajax/blogs.php)
- Admin login: [`admin/index.php`](admin/index.php)
- Admin dashboard and CRUD UI: [`admin/dashboard.php`](admin/dashboard.php)
- Blog save handler: [`admin/blog-save.php`](admin/blog-save.php)
- SQL schema and seed data: [`database/blog_management_system.sql`](database/blog_management_system.sql)
