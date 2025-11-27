<?php
// Simple News API for local Laragon development
// Supports: GET (list or single by id/slug), POST (create), PUT (update), DELETE (delete)

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$host = '127.0.0.1';
$db   = 'tajdid_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opts = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $opts);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// Helper: read JSON body
function getBody() {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}

try {
    if ($method === 'GET') {
        // list or single
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ? LIMIT 1');
            $stmt->execute([ (int)$_GET['id'] ]);
            $row = $stmt->fetch();
            echo json_encode($row ?: new stdClass());
            exit;
        }

        if (isset($_GET['slug'])) {
            $stmt = $pdo->prepare('SELECT * FROM news WHERE slug = ? LIMIT 1');
            $stmt->execute([ $_GET['slug'] ]);
            $row = $stmt->fetch();
            echo json_encode($row ?: new stdClass());
            exit;
        }

        // default: return all (ordered by published_at desc then id)
        $stmt = $pdo->query('SELECT id, title, slug, excerpt, image, category, is_featured, views, published_at AS date FROM news ORDER BY COALESCE(published_at, created_at) DESC, id DESC');
        $rows = $stmt->fetchAll();
        echo json_encode($rows);
        exit;
    }

    if ($method === 'POST') {
        $data = getBody();
        if (empty($data['title']) || empty($data['content'])) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Missing title or content']);
            exit;
        }

        $slug = $data['slug'] ?? strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($data['title'])));
        $excerpt = $data['excerpt'] ?? null;
        $image = $data['image'] ?? null;
        $category = $data['category'] ?? 'general';
        $is_featured = empty($data['is_featured']) ? 0 : 1;
        $published_at = $data['published_at'] ?? null;

        $stmt = $pdo->prepare('INSERT INTO news (title, slug, excerpt, content, image, category, is_featured, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['title'],
            $slug,
            $excerpt,
            $data['content'],
            $image,
            $category,
            $is_featured,
            $published_at,
        ]);

        echo json_encode(['ok' => true, 'id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($method === 'PUT') {
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $query);
        $data = getBody();
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Missing id']);
            exit;
        }

        $stmt = $pdo->prepare('UPDATE news SET title = ?, slug = ?, excerpt = ?, content = ?, image = ?, category = ?, is_featured = ?, published_at = ? WHERE id = ?');
        $stmt->execute([
            $data['title'] ?? '',
            $data['slug'] ?? '',
            $data['excerpt'] ?? null,
            $data['content'] ?? '',
            $data['image'] ?? null,
            $data['category'] ?? 'general',
            empty($data['is_featured']) ? 0 : 1,
            $data['published_at'] ?? null,
            (int)$data['id'],
        ]);

        echo json_encode(['ok' => true]);
        exit;
    }

    if ($method === 'DELETE') {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Missing id']);
            exit;
        }

        $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
        $stmt->execute([ (int)$_GET['id'] ]);
        echo json_encode(['ok' => true]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}

?>
