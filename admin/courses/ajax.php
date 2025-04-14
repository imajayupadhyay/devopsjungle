<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function uploadImage($inputName) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
        $targetDir = "../../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $filename = time() . "_" . basename($_FILES[$inputName]['name']);
        $targetFile = $targetDir . $filename;
        move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile);
        return "uploads/" . $filename;
    }
    return null;
}

if ($action === 'fetch') {
    $result = $conn->query("SELECT * FROM courses ORDER BY position ASC");
    while ($row = $result->fetch_assoc()) {
        $img = $row['image'] ? '<img src="../../' . $row['image'] . '" class="image-preview me-2" />' : '';
        echo '<li class="list-group-item d-flex justify-content-between align-items-center" data-id="' . $row['id'] . '">
                <span class="handle me-2"><i class="bi bi-grip-vertical"></i></span>
                <div class="d-flex align-items-center flex-grow-1">
                    ' . $img . '
                    <div>
                        <strong>' . htmlspecialchars($row['title']) . '</strong><br>
                        <small>' . htmlspecialchars($row['description']) . '</small>
                    </div>
                </div>
                <div>
                    <button class="btn btn-sm btn-primary editBtn" 
                        data-id="' . $row['id'] . '" 
                        data-title="' . htmlspecialchars($row['title'], ENT_QUOTES) . '" 
                        data-desc="' . htmlspecialchars($row['description'], ENT_QUOTES) . '" 
                        data-link="' . htmlspecialchars($row['link'], ENT_QUOTES) . '" 
                        data-image="../../' . $row['image'] . '">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>
            </li>';
    }
    exit;
}

if ($action === 'add') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $link = trim($_POST['link']);
    $image = uploadImage('image');

    $position = $conn->query("SELECT IFNULL(MAX(position), 0)+1 AS pos FROM courses")->fetch_assoc()['pos'];

    $stmt = $conn->prepare("INSERT INTO courses (title, description, image, position, link) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $description, $image, $position, $link);
    $stmt->execute();
    exit;
}

if ($action === 'update') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $link = trim($_POST['link']);
    $image = uploadImage('image');

    if ($image) {
        $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ?, image = ?, link = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $description, $image, $link, $id);
    } else {
        $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ?, link = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $link, $id);
    }
    $stmt->execute();
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM courses WHERE id = $id");
    exit;
}

if ($action === 'reorder') {
    $order = json_decode($_POST['order'], true);
    foreach ($order as $item) {
        $id = intval($item['id']);
        $pos = intval($item['position']);
        $conn->query("UPDATE courses SET position = $pos WHERE id = $id");
    }
    exit;
}