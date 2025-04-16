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
    echo "<h1>404 - Post not found</h1>";
    exit;
}

// Set SEO meta dynamically
$pageTitle = $post['meta_title'] ?: $post['title'];
$metaDescription = $post['meta_description'] ?: substr(strip_tags($post['excerpt']), 0, 160);

require_once('includes/header.php');
?>

<!-- Meta Tag -->
<meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <p class="text-muted small">
                📁 <a href="/blogs?category=<?= $post['category_slug'] ?>"><?= htmlspecialchars($post['category_title']) ?></a>
                &nbsp;|&nbsp;
                🗓️ <?= date('M d, Y', strtotime($post['created_at'])) ?>
            </p>

            <h1 class="mb-3"><?= htmlspecialchars($post['title']) ?></h1>

            <?php if ($post['image'] && file_exists($post['image'])): ?>
                <img src="<?= $post['image'] ?>" alt="Blog Image" class="img-fluid rounded mb-4">
            <?php endif; ?>

            <div class="blog-content">
                <?= $post['content'] ?>
            </div>

            <!-- Share Buttons -->
            <div class="mt-4">
                <h6>🔗 Share this post</h6>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($post['title']) ?>" target="_blank" class="btn btn-sm btn-outline-info me-2">
                    <i class="bi bi-twitter"></i> Twitter
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-facebook"></i> Facebook
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>
