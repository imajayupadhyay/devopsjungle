<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

$meta_title = $meta_title ?? 'Tutorial';

// Fetch tutorial groups
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
  <link href="https://fonts.googleapis.com/css2?family=Fira+Code&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism.css">

  <style>
    body { overflow-x: hidden; font-family: 'Nunito', sans-serif; }

    pre code {
      display: block;
      background: #1e1e2f;
      color: #f8f8f2;
      padding: 1rem;
      margin: 1.5rem 0;
      font-size: 14px;
      line-height: 1.6;
      font-family: 'Fira Code', monospace;
      border-radius: 8px;
      overflow-x: auto;
    }

    .code-toolbar {
      position: relative;
    }

    .copy-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      background: #198754;
      color: #fff;
      border: none;
      font-size: 12px;
      padding: 4px 8px;
      border-radius: 4px;
      cursor: pointer;
      opacity: 0.8;
    }

    .copy-btn:hover {
      opacity: 1;
    }
  </style>

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.tiny.cloud/1/mrwvrvsk4a9x9n68ecjzxtrvr3nkhcuqrxfuju1ad32ya65v/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
<div class="modal fade" id="pageModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="pageForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add/Edit Tutorial Page</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

<!-- Fix focus trap in Bootstrap modal -->
<script>
document.addEventListener('focusin', function(e) {
  if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root")) {
    e.stopImmediatePropagation();
  }
});
</script>

<!-- TinyMCE Config -->
<script>
tinymce.init({
  selector: '#pageContent',
  plugins: 'code codesample image link lists',
  toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | codesample code',
  height: 450,
  menubar: false,
  branding: false,
  relative_urls: false,
  remove_script_host: false,
  convert_urls: true,
  codesample_languages: [
    { text: 'HTML/XML', value: 'markup' },
    { text: 'JavaScript', value: 'javascript' },
    { text: 'PHP', value: 'php' },
    { text: 'Bash', value: 'bash' },
    { text: 'CSS', value: 'css' }
  ],
});
</script>

<!-- CRUD JS -->
<script>
function loadPages() {
  $.get("ajax.php", { action: "fetch_all" }, function(data) {
    $("#pageTable").html(data);
    setTimeout(() => Prism.highlightAll(), 100);
  });
}

function openAddModal() {
  $('#pageForm')[0].reset();
  $('#pageId').val('');
  $('#pageGroup').val('');
  $('#pagePublish').prop('checked', true);
  $('#pageMetaTitle').val('');
  $('#pageMetaDescription').val('');
  tinymce.get("pageContent").setContent('');
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
  tinymce.get("pageContent").setContent(page.content);

  new bootstrap.Modal(document.getElementById('pageModal')).show();
  setTimeout(() => Prism.highlightAll(), 200);
});

$(document).on("submit", "#pageForm", function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append("action", "save");
  formData.set("content", tinymce.get("pageContent").getContent());

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

document.addEventListener("DOMContentLoaded", function () {
  loadPages();
});
</script>

<!-- Copy Button for Code -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  setTimeout(() => {
    document.querySelectorAll('pre').forEach(pre => {
      const wrapper = document.createElement('div');
      wrapper.className = 'code-toolbar';
      pre.parentNode.insertBefore(wrapper, pre);
      wrapper.appendChild(pre);

      const btn = document.createElement('button');
      btn.className = 'copy-btn';
      btn.innerText = 'Copy';

      btn.addEventListener('click', () => {
        const code = pre.querySelector('code');
        const text = code.innerText;
        navigator.clipboard.writeText(text).then(() => {
          btn.innerText = 'Copied!';
          setTimeout(() => btn.innerText = 'Copy', 1500);
        });
      });

      wrapper.appendChild(btn);
    });
  }, 300);
});
</script>

<!-- Prism.js -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/prism.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-bash.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-javascript.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
