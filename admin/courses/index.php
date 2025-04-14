<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        body { overflow-x: hidden; }
        .main-content { margin-left: 250px; padding: 20px; }
        .handle { cursor: move; }
        .image-preview { height: 60px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>
<div class="d-flex">
    <div style="width: 250px; min-height: 100vh; background-color: #1e1e2f; color: white;">
        <?php include('../partials/sidebar.php'); ?>
    </div>
    <div class="flex-grow-1">
        <?php include('../partials/header.php'); ?>
        <div class="container mt-4">
            <h4 class="mb-4">Manage Courses</h4>

            <!-- Add Course Form -->
            <form id="addForm" class="row g-3 mb-4" enctype="multipart/form-data">
                <div class="col-md-3">
                    <input type="text" name="title" class="form-control" placeholder="Course Title" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="description" class="form-control" placeholder="Short Description" required>
                </div>
                <div class="col-md-3">
                    <input type="url" name="link" class="form-control" placeholder="Course Link (optional)">
                </div>
                <div class="col-md-2">
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-success w-100" type="submit">Add</button>
                </div>
            </form>

            <ul class="list-group" id="containerList"></ul>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Edit Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="id" id="editId">
          <div class="mb-3">
              <input type="text" name="title" id="editTitle" class="form-control" required placeholder="Title">
          </div>
          <div class="mb-3">
              <textarea name="description" id="editDesc" class="form-control" rows="3" placeholder="Description" required></textarea>
          </div>
          <div class="mb-3">
              <input type="url" name="link" id="editLink" class="form-control" placeholder="Course Link (optional)">
          </div>
          <div class="mb-3">
              <input type="file" name="image" class="form-control">
              <img id="currentImage" class="mt-2 image-preview d-none" src="" alt="Current image">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
function loadContainers() {
    $.get("ajax.php", { action: "fetch" }, function(data) {
        $("#containerList").html(data);

        Sortable.create(document.getElementById('containerList'), {
            handle: '.handle',
            animation: 150,
            onEnd: function () {
                const order = [];
                $('#containerList > li').each(function(index) {
                    order.push({ id: $(this).data('id'), position: index + 1 });
                });
                $.post("ajax.php", {
                    action: "reorder",
                    order: JSON.stringify(order)
                });
            }
        });
    });
}

$(document).on("submit", "#addForm", function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "add");

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function() {
            $("#addForm")[0].reset();
            loadContainers();
        }
    });
});

$(document).on("click", ".editBtn", function() {
    $("#editId").val($(this).data("id"));
    $("#editTitle").val($(this).data("title"));
    $("#editDesc").val($(this).data("desc"));
    $("#editLink").val($(this).data("link"));
    const img = $(this).data("image");
    if (img) {
        $("#currentImage").removeClass('d-none').attr("src", img);
    } else {
        $("#currentImage").addClass('d-none');
    }
    new bootstrap.Modal(document.getElementById('editModal')).show();
});

$(document).on("submit", "#editForm", function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "update");

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function() {
            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
            loadContainers();
        }
    });
});

$(document).on("click", ".deleteBtn", function() {
    if (confirm("Are you sure to delete this course?")) {
        $.post("ajax.php", {
            action: "delete",
            id: $(this).data("id")
        }, function() {
            loadContainers();
        });
    }
});

$(document).ready(loadContainers);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>