<?php
require_once('includes/db.php');
$pageTitle = "Latest Blogs - DevOps Jungle";
require_once('includes/header.php');

// Get selected category if any
$cat_slug = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;

// Fetch all categories for sidebar
$cat_q = $conn->query("SELECT * FROM blog_categories ORDER BY title ASC");
$categories = [];
while ($cat = $cat_q->fetch_assoc()) {
    $categories[] = $cat;
}

// Build base SQL for blog posts
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

<div class="container mt-5">
    <h1 class="mb-4">📰 Latest Blog Posts</h1>

    <div class="row">
        <!-- Sidebar Categories -->
        <div class="col-md-3">
            <form method="get" class="mb-4">
                <input type="text" name="search" class="form-control" placeholder="Search blogs..." value="<?= htmlspecialchars($search) ?>">
            </form>
            <h5 class="mb-3">📂 Categories</h5>
            <ul class="list-group">
                <li class="list-group-item <?= !$cat_slug ? 'active' : '' ?>">
                    <a href="/blogs" class="text-decoration-none d-block <?= !$cat_slug ? 'text-white' : '' ?>">All Categories</a>
                </li>
                <?php foreach ($categories as $cat): ?>
                    <li class="list-group-item <?= ($cat['slug'] === $cat_slug) ? 'active' : '' ?>">
                        <a href="/blogs?category=<?= urlencode($cat['slug']) ?>" class="text-decoration-none d-block <?= ($cat['slug'] === $cat_slug) ? 'text-white' : '' ?>">
                            <?= htmlspecialchars($cat['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Blog Post Cards -->
        <div class="col-md-9">
            <?php if ($posts && $posts->num_rows > 0): ?>
                <div class="row g-4">
                    <?php while ($post = $posts->fetch_assoc()): ?>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <?php if ($post['image'] && file_exists($post['image'])): ?>
                                    <img src="<?= $post['image'] ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                                    <p class="card-text text-muted small mb-1"><?= htmlspecialchars($post['category_title']) ?> | <?= date('M d, Y', strtotime($post['created_at'])) ?></p>
                                    <p class="card-text"><?= htmlspecialchars(substr($post['excerpt'], 0, 100)) ?>...</p>
                                    <a href="/blogs/<?= $post['slug'] ?>" class="btn btn-outline-success mt-auto btn-sm">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted text-center">No blog posts found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>
