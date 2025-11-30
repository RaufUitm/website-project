# Database import for Tajdid (Local Laragon)

This file explains how to import the `database/news.sql` file into your local MySQL server using Laragon.

Files
- `news.sql` — creates database `tajdid_db`, `news` table and inserts a sample row.

Prerequisites
- Laragon installed and running (MySQL service active).

Option A — Using HeidiSQL (recommended with recent Laragon)

1. Start Laragon and ensure MySQL is running (Laragon UI: `Start All` or `Start MySQL`).
2. Open HeidiSQL from Laragon menu: `Menu > MySQL > HeidiSQL` (or open HeidiSQL directly).
3. In the session manager, create or use a session with these connection details:
   - Hostname / IP: `127.0.0.1`
   - User: `root`
   - Password: (leave empty by default in Laragon)
   - Port: `3306`
4. Connect to the server.
5. Run the SQL file:
   - From the menu: `File > Run SQL file...` (or `Query > Load SQL file`), then choose
     `c:\Users\Lenovo\Desktop\Intern\Website\my-website\database\news.sql`.
   - Click `Run` to execute the script. This will create `tajdid_db` and the `news` table.
6. Verify: expand the `tajdid_db` database in the left tree, open `news` and click `Data` or
   `Browse` to see the sample row.

Option B — MySQL CLI (PowerShell)

Use this if you prefer the command-line. It's easiest from the Laragon terminal (it sets PATH),
or a regular PowerShell if `mysql.exe` is available in PATH.

Open Laragon Terminal or PowerShell and run one of the following (single-line commands):

```powershell
# If mysql is available in PATH (Laragon Terminal usually sets PATH)
mysql -u root < "C:\Users\Lenovo\Desktop\Intern\Website\my-website\database\news.sql"

# Or explicitly point to the mysql binary (example path — adjust if different):
"C:\laragon\bin\mysql\mysql-8.0.31-winx64\bin\mysql.exe" -u root < "C:\Users\Lenovo\Desktop\Intern\Website\my-website\database\news.sql"
```

After import, verify quickly from CLI:

```powershell
mysql -u root -e "SHOW DATABASES LIKE 'tajdid_db'; USE tajdid_db; SHOW TABLES;"
```

PHP backend config snippet

If your backend (the `tajdid-api`) is a PHP project, use this PDO snippet (e.g. in `config.php`):

```php
<?php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'tajdid_db';
$DB_USER = 'root';
$DB_PASS = ''; // Laragon default: empty
$DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed: '.$e->getMessage()]);
    exit;
}
```

Notes
- If Laragon uses a custom MySQL port or custom credentials, update the connection values accordingly.
- The frontend in this repo expects an API at `http://localhost/tajdid-api/api/news.php`. Make sure your API
  project is accessible at that address and its DB config uses `tajdid_db`.

If you want, I can also add a sample `config.php` file to the backend code here or run the import for you (if you want interactive guidance).
