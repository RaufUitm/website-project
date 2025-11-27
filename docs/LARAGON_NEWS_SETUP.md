Laragon local setup for News API
================================

Steps to run the local News API with Laragon and connect the frontend:

1. Install Laragon (if not already installed)
   - Default path: `C:\laragon`
   - Start Laragon and make sure Apache (or Nginx) and MySQL are running.

2. Place the API files where Laragon can serve them
   - Copy the `api` folder from this project into Laragon's `www` folder, for example:
     - `C:\laragon\www\tajdid-api\api\news.php`
   - Alternatively, create a Laragon site/vhost that points directly to this repository and ensure `/api/news.php` is reachable.

3. Create the database and table
   - Open `http://localhost/phpmyadmin` (Laragon includes phpMyAdmin) or use the MySQL CLI.
   - Run the SQL file: `database/news.sql` located in the repo. This creates the `tajdid` DB and `news` table and inserts a sample record.

4. Verify the API
   - Open in browser or use curl:
     - List: `http://localhost/tajdid-api/api/news.php` should return a JSON array of news items.
     - Single by id: `http://localhost/tajdid-api/api/news.php?id=1`
     - Single by slug: `http://localhost/tajdid-api/api/news.php?slug=welcome-to-tajdid`

5. Update frontend to use local API
   - In `src/views/News.vue` and `src/views/NewsDetail.vue`, set `API_BASE_URL` to your local URL, for example:
     - `const API_BASE_URL = 'http://localhost/tajdid-api/api/news.php'`
   - If your dev frontend runs on a different origin (e.g., `http://localhost:5173`), CORS headers are already enabled in the PHP API for development.

6. (Optional) File uploads
   - The sample `News.vue` expects an `upload.php` endpoint for images. You can implement a simple `api/upload.php` that stores files and returns their URLs.

7. Test the full flow
   - Start Laragon, load the About/News pages in your browser and verify news list and detail pages load from the local API.
   - Use phpMyAdmin to confirm rows are inserted when creating news (if you enable admin saving via POST).

Notes
-----
- The PHP API uses default MySQL credentials `root` with empty password (Laragon defaults). If you use different credentials, update `api/news.php` accordingly.
- This API is minimal and intended for local development. For production, add authentication, input validation, and secure file upload handling.
