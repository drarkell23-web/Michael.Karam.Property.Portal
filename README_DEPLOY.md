# Karam Real Estate — PHP + SQLite Website (Render-ready)

This project is a simple PHP + SQLite real estate site with an admin dashboard.
Files in `public/` are served by your web server. The site stores data in `data/database.sqlite`
and uploads in `public/uploads/`.

## Quick deploy to Render (PHP Web Service)

1. Create a new Git repo with this project and push to GitHub.
2. In Render, create a **Web Service**.
   - Environment: PHP
   - Build Command: leave empty
   - Start Command: `php -S 0.0.0.0:$PORT -t public`
3. Ensure the `data/` and `public/uploads/` directories are writable by the web process.
4. Run `php init_db.php` once (you can run via SSH or build command) to create the database and default admin (user: admin / pass: admin123).
   - Alternatively the app will auto-run `init_db.php` on first request if DB missing.

## Admin
- URL: `/admin-login.php`
- Default credentials: `admin` / `admin123`
- From Admin you can add listings, upload images, publish/unpublish, and export leads.

## Notes & Next Steps
- For production, secure the admin with HTTPS, stronger passwords, and consider using an external DB.
- To forward leads to Telegram or email, add server-side code in `api.php` where leads are handled.
- For S3 image storage, modify the upload handling in `api.php` to send files to S3 and store the URL in DB.
