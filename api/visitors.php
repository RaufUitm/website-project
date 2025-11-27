<?php
// Simple visitors counter API - stores count in a flat file.
// Routes:
// GET  -> returns current count { success: true, count: N }
// POST -> increments count and returns new count

header('Content-Type: application/json');

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
