<?php
// api/upload.php
// Image upload handler with multiple file support

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Maximum file size (5MB)
$maxFileSize = 5 * 1024 * 1024;

// Allowed file types
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

// Upload directory
$uploadDir = '../uploads/news/';

// Create upload directory if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        uploadImages();
        break;
        
    case 'DELETE':
        deleteImage();
        break;
        
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}

function uploadImages() {
    global $uploadDir, $maxFileSize, $allowedTypes;
    
    if (!isset($_FILES['images'])) {
        http_response_code(400);
        echo json_encode(["message" => "No files uploaded"]);
        return;
    }
    
    $uploadedFiles = [];
    $errors = [];
    
    // Handle multiple files
    $files = $_FILES['images'];
    $fileCount = is_array($files['name']) ? count($files['name']) : 1;
    
    for ($i = 0; $i < $fileCount; $i++) {
        // Get file info
        if (is_array($files['name'])) {
            $fileName = $files['name'][$i];
            $fileTmpName = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $fileType = $files['type'][$i];
            $fileError = $files['error'][$i];
        } else {
            $fileName = $files['name'];
            $fileTmpName = $files['tmp_name'];
            $fileSize = $files['size'];
            $fileType = $files['type'];
            $fileError = $files['error'];
        }
        
        // Check for upload errors
        if ($fileError !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading $fileName";
            continue;
        }
        
        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "$fileName: Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed";
            continue;
        }
        
        // Validate file size
        if ($fileSize > $maxFileSize) {
            $errors[] = "$fileName: File size exceeds 5MB limit";
            continue;
        }
        
        // Generate unique filename
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('news_', true) . '.' . $fileExtension;
        $destination = $uploadDir . $newFileName;
        
        // Move uploaded file
        if (move_uploaded_file($fileTmpName, $destination)) {
            // Generate URL path
            $fileUrl = str_replace('../', '', $destination);
            $uploadedFiles[] = [
                'filename' => $newFileName,
                'original_name' => $fileName,
                'url' => 'http://' . $_SERVER['HTTP_HOST'] . '/tajdid-api/' . $fileUrl,
                'size' => $fileSize,
                'type' => $fileType
            ];
        } else {
            $errors[] = "Failed to move $fileName to destination";
        }
    }
    
    // Return response
    if (count($uploadedFiles) > 0) {
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => count($uploadedFiles) . " file(s) uploaded successfully",
            "files" => $uploadedFiles,
            "errors" => $errors
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "No files were uploaded",
            "errors" => $errors
        ]);
    }
}

function deleteImage() {
    global $uploadDir;
    
    $data = json_decode(file_get_contents("php://input"));
    
    if (!isset($data->filename)) {
        http_response_code(400);
        echo json_encode(["message" => "Filename not provided"]);
        return;
    }
    
    $filename = basename($data->filename); // Security: prevent directory traversal
    $filePath = $uploadDir . $filename;
    
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            http_response_code(200);
            echo json_encode(["message" => "File deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to delete file"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["message" => "File not found"]);
    }
}
?>