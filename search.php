<?php
require_once('includes/db.php');
$pageTitle = "Search Results";
require_once('includes/header.php');

$query = trim($_GET['q'] ?? '');
?>
<div class="container my-5">
    <h2 class="mb-4">Search Results for: <strong><?= htmlspecialchars($query) ?></strong></h2>

    <?php if (!empty($query)): ?>
        <?php
        // Search in tutorials
        $stmt = $conn->prepare("SELECT * FROM tutorials WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC");
        $searchTerm = "%" . $query . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $tutorials = $stmt->get_result();
        ?>

        <h4 class="mt-4">Tutorials</h4>
        <div class="row g-4 mb-5">
            <?php if ($tutorials->num_rows > 0): ?>
                <?php while ($tut = $tutorials->fetch_assoc()): ?>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?= htmlspecialchars($tut['link']) ?>" class="card h-100 text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <img src="<?= !empty($tut['file']) ? htmlspecialchars($tut['file']) : 'assets/images/note-icon.png' ?>" style="height:50px;">
                                <h6 class="mt-2"><?= htmlspecialchars($tut['title']) ?></h6>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No tutorials found.</p>
            <?php endif; ?>
        </div>

        <?php
        // Search in blogs
        $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE title LIKE ? OR excerpt LIKE ? OR content LIKE ? ORDER BY created_at DESC");
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $blogs = $stmt->get_result();
        ?>

        <h4 class="mt-4">Blogs</h4>
        <div class="row g-4">
            <?php if ($blogs->num_rows > 0): ?>
                <?php while ($blog = $blogs->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <a href="blog/<?= htmlspecialchars($blog['slug']) ?>" class="card blog-card h-100 text-decoration-none text-dark">
                            <?php if (!empty($blog['image']) && file_exists($blog['image'])): ?>
                                <img src="<?= htmlspecialchars($blog['image']) ?>" class="card-img-top" style="height:180px; object-fit:cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="card-title"><?= htmlspecialchars($blog['title']) ?></h6>
                                <p class="small"><?= htmlspecialchars(substr(strip_tags($blog['excerpt']), 0, 100)) ?>...</p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No blogs found.</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Please enter a search term.</div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
<?php $conn->close(); ?>
