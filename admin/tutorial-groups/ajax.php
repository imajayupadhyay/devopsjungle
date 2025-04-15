<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function uploadIcon($fieldName) {
    if (!empty($_FILES[$fieldName]['name']) && $_FILES[$fieldName]['error'] === 0) {
        $uploadDir = '../../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $filename = time() . '_' . basename($_FILES[$fieldName]['name']);
        $target = $uploadDir . $filename;
        move_uploaded_file($_FILES[$fieldName]['tmp_name'], $target);
        return 'uploads/' . $filename;
    }
    return null;
}

if ($action === 'fetch') {
    $result = $conn->query("SELECT * FROM tutorial_groups ORDER BY created_at DESC");
    
    echo '<table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Icon</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>';
    
    while ($row = $result->fetch_assoc()) {
        $icon = $row['icon'] ? '<img src="../../' . $row['icon'] . '" style="height:40px;">' : '-';
        echo '<tr>
                <td>' . htmlspecialchars($row['title']) . '</td>
                <td>' . htmlspecialchars($row['slug']) . '</td>
                <td>' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</td>
                <td>' . $icon . '</td>
                <td>
                    <button class="btn btn-sm btn-primary editBtn"
                        data-id="' . $row['id'] . '"
                        data-title="' . htmlspecialchars($row['title'], ENT_QUOTES) . '"
                        data-slug="' . htmlspecialchars($row['slug'], ENT_QUOTES) . '"
                        data-description="' . htmlspecialchars($row['description'], ENT_QUOTES) . '"
                        data-icon="' . $row['icon'] . '"
                    ><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </td>
              </tr>';
    }

    echo '</tbody></table>';
    exit;
}

if ($action === 'save') {
    $id = $_POST['id'] ?? '';
    $title = $conn->real_escape_string(trim($_POST['title']));
    $slug = $conn->real_escape_string(trim($_POST['slug']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $icon = uploadIcon('icon');

    if ($id) {
        // Update
        if ($icon) {
            $stmt = $conn->prepare("UPDATE tutorial_groups SET title = ?, slug = ?, description = ?, icon = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $title, $slug, $description, $icon, $id);
        } else {
            $stmt = $conn->prepare("UPDATE tutorial_groups SET title = ?, slug = ?, description = ? WHERE id = ?");
            $stmt->bind_param("sssi", $title, $slug, $description, $id);
        }
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO tutorial_groups (title, slug, description, icon) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $slug, $description, $icon);
    }

    $stmt->execute();
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM tutorial_groups WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    exit;
}
