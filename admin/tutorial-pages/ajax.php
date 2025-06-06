<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'fetch_all') {
    $sql = "SELECT p.*, g.title AS group_title 
            FROM tutorial_pages p 
            INNER JOIN tutorial_groups g ON p.group_id = g.id 
            ORDER BY g.title ASC, p.position ASC";
    $result = $conn->query($sql);

    echo '<table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Group</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($row['title']) . '</td>
                <td>' . htmlspecialchars($row['group_title']) . '</td>
                <td>' . htmlspecialchars($row['slug']) . '</td>
                <td>' . ($row['is_published'] ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-secondary">Unpublished</span>') . '</td>
                <td>
                    <button class="btn btn-sm btn-primary editBtn"
                        data-id="' . $row['id'] . '"
                        data-group="' . $row['group_id'] . '"
                        data-title="' . htmlspecialchars($row['title'], ENT_QUOTES) . '"
                        data-slug="' . htmlspecialchars($row['slug'], ENT_QUOTES) . '"
                        data-content="' . htmlentities($row['content'], ENT_QUOTES | ENT_HTML5) . '"
                        data-meta_title="' . htmlspecialchars($row['meta_title'], ENT_QUOTES) . '"
                        data-meta_description="' . htmlspecialchars($row['meta_description'], ENT_QUOTES) . '"
                        data-published="' . $row['is_published'] . '"
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
    $group_id = intval($_POST['group_id']);
    $title = $conn->real_escape_string(trim($_POST['title']));
    $slug = $conn->real_escape_string(trim($_POST['slug']));
    $content = trim($_POST['content']);
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $meta_title = $conn->real_escape_string(trim($_POST['meta_title'] ?? ''));
    $meta_description = $conn->real_escape_string(trim($_POST['meta_description'] ?? ''));

    if ($id) {
        $stmt = $conn->prepare("UPDATE tutorial_pages SET title = ?, slug = ?, content = ?, meta_title = ?, meta_description = ?, is_published = ?, group_id = ? WHERE id = ?");
        $stmt->bind_param("ssssssii", $title, $slug, $content, $meta_title, $meta_description, $is_published, $group_id, $id);
    } else {
        $stmt = $conn->prepare("SELECT IFNULL(MAX(position), 0) + 1 AS pos FROM tutorial_pages WHERE group_id = ?");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $pos_result = $stmt->get_result()->fetch_assoc();
        $position = $pos_result['pos'];

        $stmt = $conn->prepare("INSERT INTO tutorial_pages (group_id, title, slug, content, position, is_published, meta_title, meta_description)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssiiss", $group_id, $title, $slug, $content, $position, $is_published, $meta_title, $meta_description);
    }

    $stmt->execute();
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM tutorial_pages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    exit;
}
