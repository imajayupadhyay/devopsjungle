<?php
require_once('includes/db.php');
$pageTitle = "DevOpsJungle";
require_once('includes/header.php');

// Fetch courses
$sql = "SELECT * FROM courses ORDER BY position ASC LIMIT 12";
$result = $conn->query($sql);
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Level Up Your Learning</h1>
        <p>Curated courses designed to help you grow your skills and reach your goals.</p>
        <a href="#courses" class="btn btn-primary">Browse Courses</a>
    </div>
</section>
<!-- Tutorials Section -->
<section id="tutorials" class="container mt-5">
    <h2 class="section-heading">Latest Tutorials</h2>
    <div class="row g-4">
        <?php
        $tutorials_sql = "SELECT * FROM tutorials ORDER BY position ASC LIMIT 12";
        $tutorials_result = $conn->query($tutorials_sql);
        ?>

        <?php if ($tutorials_result && $tutorials_result->num_rows > 0): ?>
            <?php while($tut = $tutorials_result->fetch_assoc()): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 p-3">
                        <div class="card-body d-flex flex-column text-center">
                            <div class="mb-3">
                                <?php if (!empty($tut['file']) && file_exists($tut['file'])): ?>
                                    <img src="<?= htmlspecialchars($tut['file']) ?>" alt="Tutorial Image" class="img-fluid rounded" style="height:60px; object-fit:cover;">
                                <?php else: ?>
                                    <img src="assets/images/note-icon.png" alt="icon" width="48" height="48">
                                <?php endif; ?>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($tut['title']) ?></h5>
                            <p class="card-text small"><?= htmlspecialchars(substr($tut['description'], 0, 80)) ?>...</p>
                            <a href="<?= htmlspecialchars($tut['link'] ?: '#') ?>" class="btn btn-outline-success mt-auto btn-sm">
    Read Tutorial
</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">No tutorials available yet.</p>
        <?php endif; ?>
    </div>
</section>


<!-- Courses Section -->
<section id="courses" class="container mt-5">
    <h2 class="section-heading">Available Courses</h2>
    <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 p-3">
                        <div class="card-body d-flex flex-column text-center">
                            <div class="mb-3">
                                <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
                                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Course Image" class="img-fluid rounded" style="height:60px; object-fit:cover;">
                                <?php else: ?>
                                    <img src="assets/images/default-icon.png" alt="icon" width="48" height="48">
                                <?php endif; ?>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="card-text small"><?= htmlspecialchars(substr($row['description'], 0, 80)) ?>...</p>
                            <a href="<?= htmlspecialchars($row['link'] ?: '#') ?>" class="btn btn-outline-primary mt-auto btn-sm">
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">No courses found.</p>
        <?php endif; ?>
    </div>
</section>


<!-- Footer -->
<footer class="footer mt-5">
    <div class="container">
        &copy; <?= date('Y') ?> EduSite. All rights reserved.
    </div>
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
