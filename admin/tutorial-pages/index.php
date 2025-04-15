<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
$meta_title = $meta_title ?? 'Tutorial';

// Fetch tutorial groups for dropdown
$groups_result = $conn->query("SELECT id, title FROM tutorial_groups ORDER BY title ASC");
$groups = [];
while ($g = $groups_result->fetch_assoc()) {
    $groups[] = $g;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Tutorial Pages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>body { overflow-x: hidden; }</style>
</head>
<body>

<div class="d-flex">
    <div style="width: 250px; min-height: 100vh; background-color: #1e1e2f; color: white;">
        <?php include('../partials/sidebar.php'); ?>
    </div>
    <div class="flex-grow-1">
        <?php include('../partials/header.php'); ?>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">All Tutorial Pages</h4>
                <button class="btn btn-success" onclick="openAddModal()">+ Add Page</button>
            </div>
            <div id="pageTable"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="pageModal" tabindex="-1" enctype="multipart/form-data">
    <div class="modal-dialog modal-lg">
        <form id="pageForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add/Edit Tutorial Page</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="pageId">

                <div class="mb-3">
                    <label>Group</label>
                    <select name="group_id" id="pageGroup" class="form-select" required>
                        <option value="">Select Group</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?= $group['id'] ?>"><?= htmlspecialchars($group['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" id="pageTitle" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Slug</label>
                    <input type="text" name="slug" id="pageSlug" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" id="pageMetaTitle" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Meta Description</label>
                    <textarea name="meta_description" id="pageMetaDescription" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label>Content</label>
                    <textarea name="content" id="pageContent" class="form-control" rows="10"></textarea>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pagePublish" value="1" checked>
                    <label class="form-check-label" for="pagePublish">Published</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Page</button>
            </div>
        </form>
    </div>
</div>

<script>
let editor;

function loadPages() {
    $.get("ajax.php", { action: "fetch_all" }, function(data) {
        $("#pageTable").html(data);
    });
}

function openAddModal() {
    $('#pageForm')[0].reset();
    $('#pageId').val('');
    $('#pageGroup').val('');
    $('#pagePublish').prop('checked', true);
    $('#pageMetaTitle').val('');
    $('#pageMetaDescription').val('');
    if (editor) editor.setData('');
    new bootstrap.Modal(document.getElementById('pageModal')).show();
}

$(document).on("input", "#pageTitle", function () {
    const slug = $(this).val().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
    $("#pageSlug").val(slug);
});

$(document).on("click", ".editBtn", function () {
    const page = $(this).data();
    $('#pageId').val(page.id);
    $('#pageGroup').val(page.group);
    $('#pageTitle').val(page.title);
    $('#pageSlug').val(page.slug);
    $('#pageMetaTitle').val(page.meta_title || '');
    $('#pageMetaDescription').val(page.meta_description || '');
    $('#pagePublish').prop('checked', page.published == 1);
    if (editor) editor.setData(page.content);
    new bootstrap.Modal(document.getElementById('pageModal')).show();
});

$(document).on("submit", "#pageForm", function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "save");
    formData.set("content", editor.getData());
    formData.set("meta_title", $("#pageMetaTitle").val());
    formData.set("meta_description", $("#pageMetaDescription").val());

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function() {
            bootstrap.Modal.getInstance(document.getElementById('pageModal')).hide();
            loadPages();
        }
    });
});

$(document).on("click", ".deleteBtn", function() {
    if (confirm("Delete this page?")) {
        $.post("ajax.php", {
            action: "delete",
            id: $(this).data("id")
        }, function() {
            loadPages();
        });
    }
});

// ✅ CKEditor 5 + Upload Adapter setup
document.addEventListener("DOMContentLoaded", () => {
    loadPages();

    ClassicEditor.create(document.querySelector("#pageContent"), {
        extraPlugins: [CustomUploadAdapterPlugin]
    })
    .then(e => editor = e)
    .catch(err => console.error(err));

    function CustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }

    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                const data = new FormData();
                data.append('upload', file);

                fetch('upload-image.php', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(data => {
                    if (data.url) {
                        resolve({ default: data.url });
                    } else {
                        reject(data.error || 'Upload failed.');
                    }
                })
                .catch(err => reject('Upload error: ' + err));
            }));
        }

        abort() {
            // Optionally handle abort
        }
    }
});
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
