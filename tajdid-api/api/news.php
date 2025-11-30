<?php
// Simple REST-ish API for news resources
// Improved error reporting & logging for local development

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Ensure logs directory exists
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}

// Exception handler to log uncaught exceptions
set_exception_handler(function ($e) use ($logDir) {
    $msg = '[' . date('c') . '] Uncaught exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
    @file_put_contents($logDir . '/error.log', $msg, FILE_APPEND);
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Internal Server Error', 'detail' => $e->getMessage()]);
    exit;
});

// Shutdown handler for fatal errors
register_shutdown_function(function () use ($logDir) {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $msg = '[' . date('c') . '] Fatal error: ' . $err['message'] . " in " . $err['file'] . ":" . $err['line'] . "\n";
        @file_put_contents($logDir . '/error.log', $msg, FILE_APPEND);
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Fatal error', 'detail' => $err]);
        }
    }
});
// Supports GET (list or single), POST (create), PUT (update), DELETE (delete)

// Centralized CORS and headers
require_once __DIR__ . '/_cors.php';
require_once __DIR__ . '/../config.php';

// Helper: normalize incoming image input into JSON array of URLs
function normalizeImagesInput($img) {
    if ($img === null) return null;

    // If it's a JSON string, attempt to decode
    if (is_string($img)) {
        $decoded = json_decode($img, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $img = $decoded;
        } else {
            // treat as single URL string
            return json_encode([$img], JSON_UNESCAPED_UNICODE);
        }
    }

    if (!is_array($img)) return null;

    $urls = [];
    $primaryIndex = null;
    foreach ($img as $i => $item) {
        if (is_string($item)) {
            $urls[] = $item;
        } elseif (is_array($item)) {
            if (isset($item['url'])) {
                $urls[] = $item['url'];
                if (isset($item['is_primary']) && ($item['is_primary'] === true || $item['is_primary'] == 1)) {
                    $primaryIndex = count($urls) - 1;
                }
            }
        } elseif (is_object($item)) {
            $obj = (array)$item;
            if (isset($obj['url'])) {
                $urls[] = $obj['url'];
                if (isset($obj['is_primary']) && ($obj['is_primary'] === true || $obj['is_primary'] == 1)) {
                    $primaryIndex = count($urls) - 1;
                }
            }
        }
    }

    // If a primary was set, move it to the front
    if ($primaryIndex !== null && $primaryIndex > 0 && isset($urls[$primaryIndex])) {
        $primaryUrl = $urls[$primaryIndex];
        array_splice($urls, $primaryIndex, 1);
        array_unshift($urls, $primaryUrl);
    }

    return json_encode(array_values($urls), JSON_UNESCAPED_UNICODE);
}

// Helpers
function jsonResponse($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $stmt = $pdo->prepare('SELECT * FROM news WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            if (!$row) jsonResponse(['success' => false, 'message' => 'Not found'], 404);
            // Add a convenient `date` field (YYYY-MM-DD) for frontend date inputs
            $row['date'] = $row['published_at'] ? substr($row['published_at'], 0, 10) : null;
            jsonResponse($row);
        }

        // optional filters: category, limit, action=latest
        if (isset($_GET['action']) && $_GET['action'] === 'latest') {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $stmt = $pdo->prepare('SELECT * FROM news ORDER BY published_at DESC, created_at DESC LIMIT :limit');
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            // Add `date` alias for each row
            foreach ($rows as &$r) {
                $r['date'] = $r['published_at'] ? substr($r['published_at'], 0, 10) : null;
            }
            jsonResponse($rows);
        }

        $sql = 'SELECT * FROM news ORDER BY published_at DESC, created_at DESC';
        $params = [];
        if (isset($_GET['category'])) {
            $sql = 'SELECT * FROM news WHERE category = :category ORDER BY published_at DESC, created_at DESC';
            $params[':category'] = $_GET['category'];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$r) {
            $r['date'] = $r['published_at'] ? substr($r['published_at'], 0, 10) : null;
        }
        jsonResponse($rows);

    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) jsonResponse(['success' => false, 'message' => 'Invalid JSON'], 400);

        if (empty($input['title']) || empty($input['content'])) {
            jsonResponse(['success' => false, 'message' => 'Title and content are required'], 400);
        }

        $title = $input['title'];
        $content = $input['content'];
        $image = isset($input['image']) ? $input['image'] : null; // expected JSON string or null
        // normalize image input (accept string, JSON array of urls, or array of objects {url,is_primary})
        $image = normalizeImagesInput($image);
        $category = isset($input['category']) ? $input['category'] : 'general';
        $is_featured = isset($input['is_featured']) && ($input['is_featured'] === true || $input['is_featured'] == 1) ? 1 : 0;
        // Accept either `published_at` (datetime) or `date` (YYYY-MM-DD) from frontend
        if (isset($input['published_at']) && $input['published_at'] !== '') {
            $published_at = $input['published_at'];
        } elseif (isset($input['date']) && $input['date'] !== '') {
            // normalize date-only to datetime at midnight
            $published_at = preg_match('/^\d{4}-\d{2}-\d{2}$/', $input['date']) ? ($input['date'] . ' 00:00:00') : $input['date'];
        } else {
            $published_at = null;
        }
        $excerpt = isset($input['description']) ? $input['description'] : (mb_substr(strip_tags($content), 0, 200));
        $slug = isset($input['slug']) ? $input['slug'] : preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($title)));

        $stmt = $pdo->prepare('INSERT INTO news (title, slug, excerpt, content, image, category, is_featured, published_at) VALUES (:title, :slug, :excerpt, :content, :image, :category, :is_featured, :published_at)');
        $stmt->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':excerpt' => $excerpt,
            ':content' => $content,
            ':image' => $image,
            ':category' => $category,
            ':is_featured' => $is_featured,
            ':published_at' => $published_at,
        ]);
        $id = $pdo->lastInsertId();
        jsonResponse(['success' => true, 'id' => (int)$id], 201);

    } elseif ($method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['id'])) jsonResponse(['success' => false, 'message' => 'Missing id or invalid JSON'], 400);
        $id = (int)$input['id'];

        $fields = [];
        $params = [':id' => $id];
        $allowed = ['title','slug','excerpt','content','image','category','is_featured','published_at'];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $input)) {
                $fields[] = "`$k` = :$k";
                // If frontend sent `date`, map it to `published_at` when updating
                if ($k === 'published_at' && isset($input['date']) && $input['date'] !== '' && !isset($input['published_at'])) {
                    $params[":$k"] = (preg_match('/^\d{4}-\d{2}-\d{2}$/', $input['date']) ? ($input['date'] . ' 00:00:00') : $input['date']);
                } else {
                        // If image field present, normalize it
                        if ($k === 'image') {
                            $params[":$k"] = normalizeImagesInput($input[$k]);
                        } elseif ($k === 'is_featured') {
                            // Normalize various truthy/falsy representations to integer 1/0
                            $val = $input[$k];
                            $params[":$k"] = ($val === true || $val === 1 || $val === '1' || $val === 'true' || $val === 'on') ? 1 : 0;
                        } else {
                            $params[":$k"] = $input[$k];
                        }
                }
            }
        }
        if (empty($fields)) jsonResponse(['success' => false, 'message' => 'No fields to update'], 400);

        $sql = 'UPDATE news SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        jsonResponse(['success' => true]);

    } elseif ($method === 'DELETE') {
        if (!isset($_GET['id'])) jsonResponse(['success' => false, 'message' => 'Missing id'], 400);
        $id = (int) $_GET['id'];
        $stmt = $pdo->prepare('DELETE FROM news WHERE id = :id');
        $stmt->execute([':id' => $id]);
        jsonResponse(['success' => true]);
    } else {
        jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
    }

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
}
