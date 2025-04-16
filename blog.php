<?php
require_once('includes/db.php');

$slug = $_GET['slug'] ?? '';
$stmt = $conn->prepare("SELECT p.*, c.title AS category_title, c.slug AS category_slug 
                        FROM blog_posts p 
                        JOIN blog_categories c ON p.category_id = c.id 
                        WHERE p.slug = ? AND p.is_published = 1 LIMIT 1");
$stmt->bind_param("s", $slug);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    http_response_code(404);
    echo "<h1>404 - Blog not found</h1>";
    exit;
}

$pageTitle = $post['meta_title'] ?: $post['title'];
$metaDescription = $post['meta_description'] ?: substr(strip_tags($post['excerpt']), 0, 160);
require_once('includes/header.php');
?>

<link rel="stylesheet" href="/devopsjungle/assets/css/blog-post.css">
<meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">

<!-- 🔥 Hero Banner -->
<section class="post-hero">
    <div class="container text-center text-white py-5">
        <span class="badge bg-white text-success fw-semibold px-3 py-2 mb-3"><?= htmlspecialchars($post['category_title']) ?></span>
        <h1 class="display-5 fw-bold"><?= htmlspecialchars($post['title']) ?></h1>
        <p class="text-light mt-2 small">🗓️ <?= date('F d, Y', strtotime($post['created_at'])) ?></p>
    </div>
</section>

<!-- 🧠 Blog Content -->
<div class="container post-body mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <?php if ($post['image'] && file_exists($post['image'])): ?>
                <img src="<?= $post['image'] ?>" alt="Post Image" class="featured-img mb-4">
            <?php endif; ?>

            <div class="content">
                <?= $post['content'] ?>
            </div>

            <!-- 🔗 Share Buttons -->
            <div class="share mt-5 pt-3 border-top">
                <h6 class="mb-3 text-muted">Share this post:</h6>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" class="btn btn-sm btn-outline-info me-2" target="_blank">
                    <i class="bi bi-twitter"></i> Twitter
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                    <i class="bi bi-facebook"></i> Facebook
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>
