<?php
require_once('includes/db.php');
$pageTitle = "$meta_title | DevOpsJungle";


$group_slug = $_GET['group'] ?? '';
$topic_slug = $_GET['topic'] ?? '';

// Get group
$stmt = $conn->prepare("SELECT * FROM tutorial_groups WHERE slug = ?");
$stmt->bind_param("s", $group_slug);
$stmt->execute();
$group_result = $stmt->get_result();
$group = $group_result->fetch_assoc();

if (!$group) {
    die("Tutorial group not found.");
}

// Get all pages in group
$pages_stmt = $conn->prepare("SELECT * FROM tutorial_pages WHERE group_id = ? AND is_published = 1 ORDER BY position ASC");
$pages_stmt->bind_param("i", $group['id']);
$pages_stmt->execute();
$pages_result = $pages_stmt->get_result();

$pages = [];
while ($row = $pages_result->fetch_assoc()) {
    $pages[] = $row;
}

// Find current page
$current_page = null;
if ($topic_slug) {
    foreach ($pages as $p) {
        if ($p['slug'] === $topic_slug) {
            $current_page = $p;
            break;
        }
    }
}
if (!$current_page && count($pages)) {
    $current_page = $pages[0];
}

$meta_title = $current_page['meta_title'] ?? $current_page['title'] ?? 'Tutorial';
$meta_description = $current_page['meta_description'] ?? substr(strip_tags($current_page['content']), 0, 160);
require_once('includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($meta_title) ?> | DevOpsJungle</title>
    <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/devopsjungle/assets/css/tutorial.css" rel="stylesheet">
</head>
<body>

<!-- Mobile Navbar -->
<div class="d-md-none d-flex justify-content-between align-items-center px-3 py-2 bg-success text-white">
    <span class="fw-bold"><?= htmlspecialchars($group['title']) ?></span>
    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
        ☰ Topics
    </button>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Desktop Sidebar -->
        <div class="col-md-3 d-none d-md-block bg-white border-end vh-100 overflow-auto">
            <div class="p-3">
                <h5 class="mb-3"><?= htmlspecialchars($group['title']) ?></h5>
                <?php foreach ($pages as $i => $page): ?>
                    <a href="/devopsjungle/tutorial/<?= $group['slug'] ?>/<?= $page['slug'] ?>"
                       class="topic-link <?= ($current_page['id'] == $page['id']) ? 'active' : '' ?>">
                        <span class="topic-badge"><?= $i + 1 ?></span>
                        <?= htmlspecialchars($page['title']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMobile">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title"><?= htmlspecialchars($group['title']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <?php foreach ($pages as $i => $page): ?>
                    <a href="/devopsjungle/tutorial/<?= $group['slug'] ?>/<?= $page['slug'] ?>"
                       class="topic-link <?= ($current_page['id'] == $page['id']) ? 'active' : '' ?>">
                        <span class="topic-badge"><?= $i + 1 ?></span>
                        <?= htmlspecialchars($page['title']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-md-9 content-area">
            <div class="p-4">
                <?php if ($current_page): ?>
                    <h2 class="mb-2"><?= htmlspecialchars($current_page['title']) ?></h2>
                    <p class="text-muted mb-3">Last updated: <?= date('d M, Y', strtotime($current_page['updated_at'])) ?></p>
                    <hr>
                    <div><?= $current_page['content'] ?></div>

                    <!-- Next/Previous Buttons -->
                    <?php
                    $currentIndex = array_search($current_page['id'], array_column($pages, 'id'));
                    $prevPage = $pages[$currentIndex - 1] ?? null;
                    $nextPage = $pages[$currentIndex + 1] ?? null;
                    ?>
                    <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                        <?php if ($prevPage): ?>
                            <a href="/devopsjungle/tutorial/<?= $group['slug'] ?>/<?= $prevPage['slug'] ?>" class="btn btn-outline-secondary">
                                ← <?= htmlspecialchars($prevPage['title']) ?>
                            </a>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>

                        <?php if ($nextPage): ?>
                            <a href="/devopsjungle/tutorial/<?= $group['slug'] ?>/<?= $nextPage['slug'] ?>" class="btn btn-success">
                                <?= htmlspecialchars($nextPage['title']) ?> →
                            </a>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <p>No tutorial content available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/prismjs/prism.js"></script>

</body>
</html>

<?php $conn->close(); ?>
