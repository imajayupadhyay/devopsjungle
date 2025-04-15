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
    <title>Manage Tutorial Groups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body { overflow-x: hidden; }
        .main-content { margin-left: 250px; padding: 20px; }
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Tutorial Groups</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#groupModal" onclick="openAddModal()">+ Add Group</button>
            </div>
            <div id="groupTable"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="groupForm" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Add/Edit Group</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="id" id="groupId">
          <div class="mb-3">
              <label>Title</label>
              <input type="text" name="title" id="groupTitle" class="form-control" required>
          </div>
          <div class="mb-3">
              <label>Slug</label>
              <input type="text" name="slug" id="groupSlug" class="form-control" required>
          </div>
          <div class="mb-3">
              <label>Description</label>
              <textarea name="description" id="groupDesc" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
              <label>Icon/Image</label>
              <input type="file" name="icon" class="form-control">
              <img id="previewIcon" class="mt-2 d-none" src="" style="height:60px;">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Group</button>
      </div>
    </form>
  </div>
</div>

<script>
function loadGroups() {
    $.get("ajax.php", { action: "fetch" }, function(data) {
        $("#groupTable").html(data);
    });
}

function openAddModal() {
    $('#groupForm')[0].reset();
    $('#groupId').val('');
    $('#previewIcon').addClass('d-none');
}

$(document).on("input", "#groupTitle", function () {
    const slug = $(this).val().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
    $("#groupSlug").val(slug);
});

$(document).on("click", ".editBtn", function () {
    const group = $(this).data();
    $('#groupId').val(group.id);
    $('#groupTitle').val(group.title);
    $('#groupSlug').val(group.slug);
    $('#groupDesc').val(group.description);
    if (group.icon) {
        $('#previewIcon').attr('src', "../../" + group.icon).removeClass('d-none');
    } else {
        $('#previewIcon').addClass('d-none');
    }
    new bootstrap.Modal(document.getElementById('groupModal')).show();
});

$(document).on("submit", "#groupForm", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("action", "save");
    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function () {
            bootstrap.Modal.getInstance(document.getElementById('groupModal')).hide();
            loadGroups();
        }
    });
});

$(document).on("click", ".deleteBtn", function () {
    if (confirm("Delete this group?")) {
        $.post("ajax.php", {
            action: "delete",
            id: $(this).data("id")
        }, function () {
            loadGroups();
        });
    }
});

$(document).ready(loadGroups);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
