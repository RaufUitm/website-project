<?php
// Image upload endpoint for Tajdid API
require_once __DIR__ . '/_cors.php';

$uploadDir = __DIR__ . '/../uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filesResponse = [];
$errors = [];

// Support both 'images' and 'images[]' naming
$fieldNames = ['images','images[]'];
$found = null;
foreach ($fieldNames as $f) {
    if (isset($_FILES[$f])) { $found = $f; break; }
}

if ($found === null && empty($_FILES)) {
    echo json_encode(['success' => false, 'message' => 'No files uploaded']);
    exit;
}

// Normalize files array
$uploaded = $found ? $_FILES[$found] : $_FILES;

// If single file sent, convert to array
if (!is_array($uploaded['name'])) {
    $uploaded = [ 'name' => [$uploaded['name']], 'type' => [$uploaded['type']], 'tmp_name' => [$uploaded['tmp_name']], 'error' => [$uploaded['error']], 'size' => [$uploaded['size']] ];
}

for ($i = 0; $i < count($uploaded['name']); $i++) {
    $origName = $uploaded['name'][$i];
    $tmp = $uploaded['tmp_name'][$i];
    $err = $uploaded['error'][$i];
    if ($err !== UPLOAD_ERR_OK) {
        $errors[] = "$origName: upload error code $err";
        continue;
    }

    // Validate image mime
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp);
    finfo_close($finfo);
    if (strpos($mime, 'image/') !== 0) {
        $errors[] = "$origName: not an image (mime: $mime)";
        continue;
    }

    $ext = pathinfo($origName, PATHINFO_EXTENSION);
    $safe = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($origName, PATHINFO_FILENAME));
    $filename = $safe . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . ($ext ?: 'jpg');
    $dest = $uploadDir . '/' . $filename;
        if (move_uploaded_file($tmp, $dest)) {
        // Build accessible URL relative to api root. This assumes the API is served at http://localhost/tajdid-api/
        $baseUrl = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '\\/');
        $url = $baseUrl . '/uploads/' . $filename;
        $filesResponse[] = ['url' => $url, 'filename' => $filename, 'original_name' => $origName, 'is_primary' => false];
    } else {
        $errors[] = "$origName: failed to move file";
    }
}

echo json_encode(['success' => count($filesResponse) > 0, 'files' => $filesResponse, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
exit;
