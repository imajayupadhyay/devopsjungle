<?php
$current_uri = $_SERVER['REQUEST_URI'];
?>

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark vh-100" style="width: 250px;">
    <a href="/admin/dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">DevOpsJungle</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/admin/dashboard.php" class="nav-link text-white <?= str_contains($current_uri, '/admin/dashboard.php') ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="/admin/tutorials/index.php" class="nav-link text-white <?= str_contains($current_uri, '/admin/tutorials/') ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-journal-text me-2"></i> Tutorials
            </a>
        </li>
        <li>
            <a href="/admin/courses/index.php" class="nav-link text-white <?= str_contains($current_uri, '/admin/courses/') ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-play-circle me-2"></i> Courses
            </a>
        </li>
        <li>
            <a href="/admin/tutorial-groups/index.php" class="nav-link text-white <?= str_contains($current_uri, '/admin/tutorial-groups/') ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-folder2-open me-2"></i> Tutorial Groups
            </a>
        </li>
        <li>
            <a href="/admin/tutorial-pages/index.php" class="nav-link text-white <?= str_contains($current_uri, '/admin/tutorial-pages/') ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-file-earmark-text me-2"></i> Tutorial Pages
            </a>
        </li>
        <li>
            <a href="/admin/blog-categories/index.php" class="nav-link text-white <?= str_contains($current_uri, '/admin/blog-categories/') ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-tags me-2"></i> Blog Categories
            </a>
        </li>
        <li>
            <a href="/admin/blog-posts/index.php" class="nav-link text-white <?= str_contains($current_uri, '/admin/blog-posts/') ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-file-post me-2"></i> Blog Posts
            </a>
        </li>
        <li>
            <a href="/admin/logout.php" class="nav-link text-white">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>
