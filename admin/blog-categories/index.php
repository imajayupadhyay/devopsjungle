<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch all categories
$categories = $conn->query("SELECT * FROM blog_categories ORDER BY parent_id ASC, title ASC");
$categoryList = [];
while ($row = $categories->fetch_assoc()) {
    $categoryList[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<div class="d-flex">
    <div style="width: 250px;"><?php include('../partials/sidebar.php'); ?></div>
    <div class="flex-grow-1">
        <?php include('../partials/header.php'); ?>

        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Blog Categories</h4>
                <button class="btn btn-success" onclick="openModal()">+ Add Category</button>
            </div>
            <div id="categoryTable"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="categoryForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add/Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="categoryId">
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" id="categoryTitle" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Slug</label>
                    <input type="text" name="slug" id="categorySlug" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Parent Category</label>
                    <select name="parent_id" id="categoryParent" class="form-select">
                        <option value="">None</option>
                        <?php foreach ($categoryList as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" id="categoryDesc" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function loadCategories() {
    $.get("ajax.php", { action: "fetch" }, function (html) {
        $("#categoryTable").html(html);
    });
}

function openModal() {
    $('#categoryForm')[0].reset();
    $('#categoryId').val('');
    new bootstrap.Modal(document.getElementById('categoryModal')).show();
}

$(document).on("input", "#categoryTitle", function () {
    const slug = $(this).val().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]/g, '');
    $("#categorySlug").val(slug);
});

$(document).on("click", ".editBtn", function () {
    const cat = $(this).data();
    $("#categoryId").val(cat.id);
    $("#categoryTitle").val(cat.title);
    $("#categorySlug").val(cat.slug);
    $("#categoryDesc").val(cat.description);
    $("#categoryParent").val(cat.parent);
    new bootstrap.Modal(document.getElementById('categoryModal')).show();
});

$(document).on("submit", "#categoryForm", function (e) {
    e.preventDefault();
    $.post("ajax.php", $(this).serialize() + "&action=save", function () {
        bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
        loadCategories();
    });
});

$(document).on("click", ".deleteBtn", function () {
    if (confirm("Delete this category?")) {
        $.post("ajax.php", { action: "delete", id: $(this).data("id") }, function () {
            loadCategories();
        });
    }
});

$(document).ready(loadCategories);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
