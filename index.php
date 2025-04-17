<?php
require_once('includes/db.php');
$pageTitle = "DevOps Jungle";
require_once('includes/header.php');

// Fetch tutorials
$tutorials_sql = "SELECT * FROM tutorials ORDER BY position ASC LIMIT 8";
$tutorials_result = $conn->query($tutorials_sql);

// Fetch recent blogs + categories
$blogs_sql = "SELECT b.*, c.title as category FROM blog_posts b 
              LEFT JOIN blog_categories c ON b.category_id = c.id 
              ORDER BY b.created_at DESC LIMIT 6";
$blogs_result = $conn->query($blogs_sql);

$categories_sql = "SELECT * FROM blog_categories ORDER BY title ASC";
$categories_result = $conn->query($categories_sql);
?>

<link rel="stylesheet" href="assets/css/home.css">


<!-- Hero Banner -->
<section class="hero-banner d-flex align-items-center justify-content-center text-white text-center">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Welcome to DevOps Jungle 🌿</h1>
        <p class="lead mb-4">Master DevOps Tools, Cloud, Scripting, and More!</p>
        
        <form action="search.php" method="GET" class="search-form mx-auto">
            <div class="input-group">
                <input type="text" name="q" class="form-control form-control-lg shadow-sm" placeholder="Search tutorials, blogs..." required>
                <button class="btn btn-success btn-lg px-4" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</section>


<!-- Tutorials -->
<section class="container-fluid my-5">
    <h2 class="section-heading mb-4">Latest Tutorials</h2>
    <div class="row g-4 p-lg-5 p-md-3 p-sm-1">
        <?php if ($tutorials_result && $tutorials_result->num_rows > 0): ?>
            <?php while($tut = $tutorials_result->fetch_assoc()): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="<?= htmlspecialchars($tut['link'] ?: '#') ?>" class="card tutorial-card h-100 text-decoration-none text-dark">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                            <img src="<?= !empty($tut['file']) && file_exists($tut['file']) ? $tut['file'] : 'assets/images/note-icon.png' ?>" class="mb-3" alt="Icon" style="height:60px; object-fit:contain;">
                            <h5 class="card-title"><?= htmlspecialchars($tut['title']) ?></h5>
                            <p class="card-text small"><?= htmlspecialchars(substr($tut['description'], 0, 80)) ?>...</p>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No tutorials available yet.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Blogs Section -->
<section class="container my-5">
    <h2 class="section-heading mb-4">Recent Blogs</h2>

    <!-- Categories -->
    <div class="mb-4 d-flex flex-wrap gap-2">
        <?php if ($categories_result && $categories_result->num_rows > 0): ?>
            <?php while($cat = $categories_result->fetch_assoc()): ?>
                <span class="badge bg-dark"><?= htmlspecialchars($cat['title']) ?></span>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if ($blogs_result && $blogs_result->num_rows > 0): ?>
            <?php while($blog = $blogs_result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <a href="blog/<?= htmlspecialchars($blog['slug']) ?>" class="card blog-card h-100 text-decoration-none text-dark">
                        <?php if (!empty($blog['image']) && file_exists($blog['image'])): ?>
                            <img src="<?= htmlspecialchars($blog['image']) ?>" class="card-img-top" alt="Blog Image" style="height:180px; object-fit:cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <small class="text-muted"><?= htmlspecialchars($blog['category'] ?? 'General') ?></small>
                            <h5 class="card-title mt-1"><?= htmlspecialchars($blog['title']) ?></h5>
                            <p class="card-text small"><?= htmlspecialchars(substr(strip_tags($blog['excerpt']), 0, 100)) ?>...</p>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No blogs found.</p>
        <?php endif; ?>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include('includes/footer.php'); ?>
</body>
</html>
<?php $conn->close(); ?>
