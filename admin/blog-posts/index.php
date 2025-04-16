<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch all categories for dropdown
$categories = $conn->query("SELECT * FROM blog_categories ORDER BY parent_id ASC, title ASC");
$categoryOptions = [];
while ($cat = $categories->fetch_assoc()) {
    $categoryOptions[] = $cat;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>
<body>
<div class="d-flex">
    <div style="width: 250px;"><?php include('../partials/sidebar.php'); ?></div>
    <div class="flex-grow-1">
        <?php include('../partials/header.php'); ?>
        <div class="container mt-4">
            <div class="d-flex justify-content-between mb-3">
                <h4>Blog Posts</h4>
                <button class="btn btn-success" onclick="openModal()">+ Add Post</button>
            </div>
            <div id="postTable"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="postModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="postForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Add/Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="postId">

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" id="postTitle" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Slug</label>
                    <input type="text" name="slug" id="postSlug" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Category</label>
                    <select name="category_id" id="postCategory" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categoryOptions as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Excerpt</label>
                    <textarea name="excerpt" id="postExcerpt" class="form-control" rows="2"></textarea>
                </div>

                <div class="mb-3">
                    <label>Featured Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label>Content</label>
                    <textarea name="content" id="postContent" class="form-control" rows="10"></textarea>
                </div>

                <div class="mb-3">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" id="postMetaTitle" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description" id="postMetaDescription" class="form-control"></textarea>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_published" id="postPublish" value="1" checked>
                    <label class="form-check-label" for="postPublish">Published</label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
let editor;

function loadPosts() {
    $.get("ajax.php", { action: "fetch" }, function (html) {
        $("#postTable").html(html);
    });
}

function openModal() {
    $('#postForm')[0].reset();
    $('#postId').val('');
    editor.setData('');
    new bootstrap.Modal(document.getElementById('postModal')).show();
}

$(document).on("input", "#postTitle", function () {
    const slug = $(this).val().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
    $("#postSlug").val(slug);
});

$(document).on("click", ".editBtn", function () {
    const post = $(this).data();
    $('#postId').val(post.id);
    $('#postTitle').val(post.title);
    $('#postSlug').val(post.slug);
    $('#postCategory').val(post.category);
    $('#postExcerpt').val(post.excerpt);
    $('#postMetaTitle').val(post.meta_title);
    $('#postMetaDescription').val(post.meta_description);
    $('#postPublish').prop('checked', post.published == 1);
    editor.setData(post.content);
    new bootstrap.Modal(document.getElementById('postModal')).show();
});

$(document).on("submit", "#postForm", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "save");
    formData.set("content", editor.getData());

    $.ajax({
        url: "ajax.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function () {
            bootstrap.Modal.getInstance(document.getElementById('postModal')).hide();
            loadPosts();
        }
    });
});

$(document).on("click", ".deleteBtn", function () {
    if (confirm("Delete this post?")) {
        $.post("ajax.php", { action: "delete", id: $(this).data("id") }, function () {
            loadPosts();
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    loadPosts();
    ClassicEditor.create(document.querySelector("#postContent"))
        .then(e => editor = e)
        .catch(err => console.error(err));
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
