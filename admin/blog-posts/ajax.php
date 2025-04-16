<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'fetch') {
    $sql = "SELECT p.*, c.title AS category_title 
            FROM blog_posts p 
            LEFT JOIN blog_categories c ON p.category_id = c.id 
            ORDER BY p.created_at DESC";

    $result = $conn->query($sql);
    echo '<table class="table table-bordered table-striped">';
    echo '<thead><tr>
            <th>Title</th>
            <th>Category</th>
            <th>Published</th>
            <th style="width:120px">Actions</th>
        </tr></thead><tbody>';

    while ($row = $result->fetch_assoc()) {
        $pub = $row['is_published'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
        echo "<tr>
            <td>" . htmlspecialchars($row['title']) . "</td>
            <td>" . htmlspecialchars($row['category_title'] ?? '—') . "</td>
            <td>$pub</td>
            <td>
                <button class='btn btn-sm btn-primary editBtn'
                    data-id='{$row['id']}'
                    data-title=\"" . htmlspecialchars($row['title'], ENT_QUOTES) . "\"
                    data-slug=\"" . htmlspecialchars($row['slug'], ENT_QUOTES) . "\"
                    data-category='{$row['category_id']}'
                    data-excerpt=\"" . htmlspecialchars($row['excerpt'], ENT_QUOTES) . "\"
                    data-meta_title=\"" . htmlspecialchars($row['meta_title'], ENT_QUOTES) . "\"
                    data-meta_description=\"" . htmlspecialchars($row['meta_description'], ENT_QUOTES) . "\"
                    data-content=\"" . htmlspecialchars($row['content'], ENT_QUOTES) . "\"
                    data-published='{$row['is_published']}'
                ><i class='bi bi-pencil'></i></button>
                <button class='btn btn-sm btn-danger deleteBtn' data-id='{$row['id']}'><i class='bi bi-trash'></i></button>
            </td>
        </tr>";
    }

    echo '</tbody></table>';
    exit;
}

if ($action === 'save') {
    $id             = $_POST['id'] ?? '';
    $title          = $conn->real_escape_string($_POST['title']);
    $slug           = $conn->real_escape_string($_POST['slug']);
    $category_id    = intval($_POST['category_id']);
    $excerpt        = $conn->real_escape_string($_POST['excerpt']);
    $content        = $_POST['content'];
    $meta_title     = $conn->real_escape_string($_POST['meta_title'] ?? '');
    $meta_desc      = $conn->real_escape_string($_POST['meta_description'] ?? '');
    $is_published   = isset($_POST['is_published']) ? 1 : 0;
    $author_id      = $_SESSION['admin_id'];
    $imagePath      = null;

    // Upload image if available
    if (!empty($_FILES['image']['name'])) {
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target = "../../uploads/" . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imagePath = "uploads/" . $filename;
        }
    }

    if ($id) {
        // Update
        $sql = "UPDATE blog_posts SET 
                title = ?, slug = ?, category_id = ?, excerpt = ?, content = ?, 
                meta_title = ?, meta_description = ?, is_published = ?, author_id = ?";

        if ($imagePath) {
            $sql .= ", image = ?";
        }

        $sql .= " WHERE id = ?";

        if ($imagePath) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissssisi", $title, $slug, $category_id, $excerpt, $content, $meta_title, $meta_desc, $is_published, $author_id, $imagePath, $id);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissssii", $title, $slug, $category_id, $excerpt, $content, $meta_title, $meta_desc, $is_published, $author_id, $id);
        }

    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO blog_posts 
            (title, slug, category_id, excerpt, content, meta_title, meta_description, is_published, author_id, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisssssis", $title, $slug, $category_id, $excerpt, $content, $meta_title, $meta_desc, $is_published, $author_id, $imagePath);
    }

    $stmt->execute();
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    exit;
}
