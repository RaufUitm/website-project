Tajdid API (local)
===================

This is a minimal PHP API for the TAJDID website. It provides endpoints used by the frontend in this repo:

- `api/news.php` — list, create, update, delete news items
- `api/upload.php` — upload images and return accessible URLs

Quick deploy steps (Laragon)
-----------------------------

1. Copy this folder into Laragon's `www` directory or configure a virtual host so the API is available at `http://localhost/tajdid-api/`.
   Example: `C:\laragon\www\tajdid-api`.
2. Ensure the database from `../database/news.sql` is imported (creates `tajdid_db` and `news` table).
3. Adjust `config.php` database credentials if your MySQL user/password/port differ.
4. Ensure the `uploads/` directory is writable by the web server. The API will create it automatically if missing.

Notes
-----
- The API assumes the frontend will send JSON for POST/PUT requests and that `upload.php` receives files under `images[]`.
- CORS is enabled for simplicity. For production, restrict origins.

Example endpoints
-----------------
- GET all: `GET http://localhost/tajdid-api/api/news.php`
- GET single: `GET http://localhost/tajdid-api/api/news.php?id=1`
- POST create: `POST http://localhost/tajdid-api/api/news.php` (JSON body)
- PUT update: `PUT http://localhost/tajdid-api/api/news.php` (JSON with `id`)
- DELETE: `DELETE http://localhost/tajdid-api/api/news.php?id=1`
- Upload: `POST http://localhost/tajdid-api/api/upload.php` (multipart form with `images[]`)
