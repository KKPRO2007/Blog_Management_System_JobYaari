# JobYaari Blog Management System

A PHP + MySQL blog management system with:

- public blog listing
- AJAX search, category, and date filtering
- blog detail pages
- admin login and blog CRUD
- Docker-based deployment for Render

## Stack

- PHP 8
- MySQL
- jQuery / AJAX
- Docker

## Project Structure

```text
admin/
ajax/
assets/
config/
database/
docker/
includes/
uploads/
.env.example
Dockerfile
render.yaml
index.php
blog.php
```

## Local Setup

1. Install XAMPP or Laragon.
2. Create a MySQL database named `blog_management_system`.
3. Import [database/blog_management_system.sql](/d:/Blog_Management_System_JobYaari/database/blog_management_system.sql).
4. Open the app:
- `http://localhost/Blog_Management_System_JobYaari/`

## Render Deployment

This repo is prepared for Render with:

- [Dockerfile](/d:/Blog_Management_System_JobYaari/Dockerfile)
- [render.yaml](/d:/Blog_Management_System_JobYaari/render.yaml)
- [config/database.php](/d:/Blog_Management_System_JobYaari/config/database.php)

### 1. Create the web app

1. Push this repo to GitHub.
2. In Render, create a new `Web Service`.
3. Choose runtime `Docker`.
4. Render can use the included `Dockerfile`.

### 2. Create MySQL on Render

Create a `Private Service` for MySQL using Render's MySQL guide:

- https://render.com/docs/deploy-mysql
- https://render.com/templates/mysql

Use these MySQL values if you want the app to work with the built-in Render fallback and no manual DB env setup on the web service:

Important:

- Name the MySQL private service `mysql`
- Add a persistent disk mounted at `/var/lib/mysql`
- Keep the MySQL service in the same Render workspace and region as the web app

### 3. Import the database

Import [database/blog_management_system.sql](/d:/Blog_Management_System_JobYaari/database/blog_management_system.sql) into your Render MySQL instance.

### 4. Open the site

After deploy:

- Home: `https://your-service-name.onrender.com/`
- Admin: `https://your-service-name.onrender.com/admin/index.php`

## Admin Access

The seeded admin account is created from the SQL file. Change it before real production use.

Admin route:

- `/admin/index.php`

## Notes

- `uploads/` must be writable
- The app reads environment variables from [config/database.php](/d:/Blog_Management_System_JobYaari/config/database.php)
- Render Docker startup is handled by [docker/apache/render-port.sh](/d:/Blog_Management_System_JobYaari/docker/apache/render-port.sh)
- The public homepage uses AJAX via [ajax/blogs.php](/d:/Blog_Management_System_JobYaari/ajax/blogs.php)
