<?php
// Path: /admin/tutorial-pages/upload-image.php

// Absolute path to /uploads (2 levels up from /admin/tutorial-pages)
$targetDir = realpath(__DIR__ . '/../../uploads') ?: (__DIR__ . '/../../uploads');

// Create the uploads folder if it doesn't exist
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Validate and process the uploaded image
if (!empty($_FILES['upload']['name'])) {
    $file       = $_FILES['upload'];
    $fileName   = basename($file['name']);
    $fileTmp    = $file['tmp_name'];
    $fileSize   = $file['size'];
    $fileExt    = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $fileMime   = mime_content_type($fileTmp);

    // Allowed file types (extensions + MIME)
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

    if (!in_array($fileExt, $allowedExtensions) || !in_array($fileMime, $allowedMimeTypes)) {
        echo json_encode(["error" => "Unsupported image type."]);
        exit;
    }

    // Optional: Limit size (e.g. 5MB)
    if ($fileSize > 5 * 1024 * 1024) {
        echo json_encode(["error" => "Image too large. Max 5MB allowed."]);
        exit;
    }

    // Safe filename generation
    $safeName   = time() . '_' . preg_replace("/[^a-zA-Z0-9_\-.]/", "", $fileName);
    $targetPath = rtrim($targetDir, '/') . '/' . $safeName;

    if (move_uploaded_file($fileTmp, $targetPath)) {
        // Dynamically build base URL
        $protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host      = $_SERVER['HTTP_HOST'];
        $basePath = rtrim(str_replace('/admin/tutorial-pages', '', dirname($_SERVER['SCRIPT_NAME'])), '/');
        $imageUrl  = "$protocol://$host$basePath/uploads/" . $safeName;

        echo json_encode(["url" => $imageUrl]);
    } else {
        echo json_encode(["error" => "Failed to save uploaded image."]);
    }
} else {
    echo json_encode(["error" => "No file uploaded."]);
}
