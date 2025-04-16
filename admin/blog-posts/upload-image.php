<?php
// Get real upload path (uploads is 2 levels up from blog-posts folder)
$targetDir = realpath(__DIR__ . '/../../uploads') ?: (__DIR__ . '/../../uploads');

// Ensure the uploads directory exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Validate the upload
if (!empty($_FILES['upload']['name'])) {
    $file       = $_FILES['upload'];
    $fileName   = basename($file['name']);
    $fileTmp    = $file['tmp_name'];
    $fileSize   = $file['size'];
    $fileExt    = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $fileMime   = mime_content_type($fileTmp);

    // Allowed image formats
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

    if (!in_array($fileExt, $allowedExtensions) || !in_array($fileMime, $allowedMimeTypes)) {
        echo json_encode(["error" => "Invalid image type."]);
        exit;
    }

    // Limit size: 5MB
    if ($fileSize > 5 * 1024 * 1024) {
        echo json_encode(["error" => "Image too large (max 5MB)."]);
        exit;
    }

    // Safe file name
    $safeName = time() . '_' . preg_replace("/[^a-zA-Z0-9_\-.]/", "", $fileName);
    $targetPath = $targetDir . '/' . $safeName;

    if (move_uploaded_file($fileTmp, $targetPath)) {
        $url = "/uploads/" . $safeName;
        echo json_encode(["url" => $url]);
    } else {
        echo json_encode(["error" => "Failed to save image."]);
    }
} else {
    echo json_encode(["error" => "No file uploaded."]);
}
