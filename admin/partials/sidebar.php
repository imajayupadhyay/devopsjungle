<!-- partials/sidebar.php -->
<?php
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark vh-100" style="width: 250px;">
    <a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">DevOpsJungle</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link text-white <?php echo ($current_page == 'dashboard.php') ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="../tutorials/index.php" class="nav-link text-white <?php echo ($current_page == 'index.php' && strpos($_SERVER['PHP_SELF'], 'tutorials') !== false) ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-journal-text me-2"></i> Tutorials
            </a>
        </li>
        <li>
            <a href="../courses/index.php" class="nav-link text-white <?php echo ($current_page == 'index.php' && strpos($_SERVER['PHP_SELF'], 'courses') !== false) ? 'active bg-primary' : ''; ?>">
                <i class="bi bi-play-circle me-2"></i> Courses
            </a>
        </li>
        <li>
            <a href="logout.php" class="nav-link text-white">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>