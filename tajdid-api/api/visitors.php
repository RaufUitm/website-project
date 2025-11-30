<?php
// Minimal CORS headers for visitors endpoint
if (!headers_sent()) {
    header_remove('Access-Control-Allow-Origin');
    header_remove('Access-Control-Allow-Methods');
    header_remove('Access-Control-Allow-Headers');
}
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // CORS preflight
    http_response_code(204);
    exit;
}

$storageFile = __DIR__ . '/visitors_count.txt';

// Ensure file exists
if (!file_exists($storageFile)) {
    file_put_contents($storageFile, "0");
}

// Acquire exclusive lock while reading/writing
$fp = fopen($storageFile, 'c+');
if (!$fp) {
    echo json_encode(['success' => false, 'message' => 'Unable to open storage file']);
    exit;
}

flock($fp, LOCK_EX);
$content = stream_get_contents($fp);
$count = intval($content);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $count++;
    // Rewind and truncate then write
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, (string)$count);
    fflush($fp);
}

// Release lock and close
flock($fp, LOCK_UN);
fclose($fp);

echo json_encode(['success' => true, 'count' => $count]);
exit;
