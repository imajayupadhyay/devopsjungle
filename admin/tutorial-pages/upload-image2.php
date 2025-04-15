<?php
// Get real path to upload dir
$targetDir = realpath(__DIR__ . '/../../uploads') . '/';
$logFile = __DIR__ . '/debug-log.txt';

// Make sure uploads dir exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
    file_put_contents($logFile, "📁 Created uploads folder: $targetDir\n", FILE_APPEND);
}

// Start logging
file_put_contents($logFile, "\n===========================\n", FILE_APPEND);
file_put_contents($logFile, print_r($_FILES, true), FILE_APPEND);

if (!empty($_FILES['upload']['name'])) {
    $fileName = time() . '_' . basename($_FILES['upload']['name']);
    $targetFile = $targetDir . $fileName;
    $tmpName = $_FILES['upload']['tmp_name'];

    file_put_contents($logFile, "Trying to move from: $tmpName to $targetFile\n", FILE_APPEND);

    if (move_uploaded_file($tmpName, $targetFile)) {
        file_put_contents($logFile, "✅ Upload success: $fileName\n", FILE_APPEND);
        echo json_encode(["url" => "/devopsjungle/uploads/" . $fileName]);
    } else {
        file_put_contents($logFile, "❌ move_uploaded_file failed\n", FILE_APPEND);
        echo json_encode(["error" => "move_uploaded_file failed"]);
    }
} else {
    file_put_contents($logFile, "❌ No file uploaded\n", FILE_APPEND);
    echo json_encode(["error" => "No file uploaded"]);
}
