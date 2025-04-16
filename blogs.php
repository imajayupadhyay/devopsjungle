<?php
require_once('includes/db.php');
$pageTitle = "Blogs - DevOps Jungle";
require_once('includes/header.php');

$cat_slug = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Fetch categories
$cat_q = $conn->query("SELECT * FROM blog_categories ORDER BY title ASC");
$categories = [];
while ($cat = $cat_q->fetch_assoc()) {
    $categories[] = $cat;
}

// Build query
$sql = "SELECT p.*, c.title AS category_title, c.slug AS category_slug 
        FROM blog_posts p 
        JOIN blog_categories c ON p.category_id = c.id 
        WHERE p.is_published = 1";

if ($cat_slug) {
    $sql .= " AND c.slug = '" . $conn->real_escape_string($cat_slug) . "'";
}
if ($search) {
    $q = $conn->real_escape_string($search);
    $sql .= " AND (p.title LIKE '%$q%' OR p.excerpt LIKE '%$q%' OR p.content LIKE '%$q%')";
}

$sql .= " ORDER BY p.created_at DESC";
$posts = $conn->query($sql);
?>

<link rel="stylesheet" href="assets/css/blog.css">

<section class="blog-hero text-white text-center py-5">
    <div class="container">
        <h1 class="display-5 fw-bold">📚 Explore Latest Blogs</h1>
        <p class="lead">Learn DevOps, Cloud, CI/CD & more from curated blogs</p>

        <!-- Search + Categories -->
        <form class="row justify-content-center g-3 mt-4" method="get">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search blogs..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['slug'] ?>" <?= ($cat['slug'] === $cat_slug) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-light w-100">Filter</button>
            </div>
        </form>
    </div>
</section>

<div class="container my-5">
    <?php if ($posts && $posts->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <?php if ($post['image'] && file_exists($post['image'])): ?>
                            <img src="<?= $post['image'] ?>" class="card-img-top" alt="Post Image">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-success mb-2"><?= htmlspecialchars($post['category_title']) ?></span>
                            <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                            <p class="text-muted small"><?= date('M d, Y', strtotime($post['created_at'])) ?></p>
                            <p class="card-text"><?= htmlspecialchars(substr($post['excerpt'], 0, 100)) ?>...</p>
                            <a href="/blogs/<?= $post['slug'] ?>" class="btn btn-outline-success mt-auto btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">No blog posts found.</p>
    <?php endif; ?>
</div>

<?php require_once('includes/footer.php'); ?>
