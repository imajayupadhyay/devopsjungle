<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'fetch') {
    $result = $conn->query("SELECT * FROM blog_categories ORDER BY parent_id ASC, title ASC");
    echo '<table class="table table-bordered table-striped">';
    echo '<thead><tr><th>Title</th><th>Slug</th><th>Parent</th><th>Actions</th></tr></thead><tbody>';
    while ($row = $result->fetch_assoc()) {
        $parent = 'None';
        if ($row['parent_id']) {
            $p = $conn->query("SELECT title FROM blog_categories WHERE id = {$row['parent_id']}")->fetch_assoc();
            $parent = $p['title'] ?? 'Unknown';
        }
        echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['slug']}</td>
            <td>{$parent}</td>
            <td>
                <button class='btn btn-sm btn-primary editBtn'
                    data-id='{$row['id']}'
                    data-title='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'
                    data-slug='" . htmlspecialchars($row['slug'], ENT_QUOTES) . "'
                    data-description='" . htmlspecialchars($row['description'], ENT_QUOTES) . "'
                    data-parent='{$row['parent_id']}'>
                    <i class='bi bi-pencil'></i>
                </button>
                <button class='btn btn-sm btn-danger deleteBtn' data-id='{$row['id']}'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
    }
    echo '</tbody></table>';
    exit;
}

if ($action === 'save') {
    $id = $_POST['id'] ?? null;
    $title = $conn->real_escape_string($_POST['title']);
    $slug = $conn->real_escape_string($_POST['slug']);
    $desc = $conn->real_escape_string($_POST['description']);
    $parent = $_POST['parent_id'] !== '' ? intval($_POST['parent_id']) : null;

    if ($id) {
        $stmt = $conn->prepare("UPDATE blog_categories SET title=?, slug=?, description=?, parent_id=? WHERE id=?");
        $stmt->bind_param("sssii", $title, $slug, $desc, $parent, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO blog_categories (title, slug, description, parent_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $slug, $desc, $parent);
    }
    $stmt->execute();
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM blog_categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    exit;
}
