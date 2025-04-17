<?php
require_once('includes/db.php');
$pageTitle = "All Tutorials";
require_once('includes/header.php');

// Fetch all tutorials initially
$tutorials_sql = "SELECT * FROM tutorials ORDER BY position ASC";
$tutorials_result = $conn->query($tutorials_sql);
?>

<link rel="stylesheet" href="assets/css/tutorials.css">

<section class="container py-5">
    <h2 class="section-heading text-center mb-4">All Tutorials</h2>

    <!-- Filter Bar -->
    <div class="row mb-4 justify-content-center">
        <div class="col-md-6">
            <input type="text" id="searchTutorials" class="form-control form-control-lg" placeholder="Search tutorials...">
        </div>
    </div>

    <!-- Tutorials Grid -->
    <div class="row g-4" id="tutorialResults">
        <?php if ($tutorials_result && $tutorials_result->num_rows > 0): ?>
            <?php while($tut = $tutorials_result->fetch_assoc()): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 tutorial-item">
                    <a href="<?= htmlspecialchars($tut['link'] ?: '#') ?>" class="card h-100 text-decoration-none text-dark tutorial-card">
                        <div class="card-body text-center">
                            <img src="<?= (!empty($tut['file']) && file_exists($tut['file'])) ? $tut['file'] : 'assets/images/note-icon.png' ?>" 
                                 alt="Tutorial Image" class="mb-3" style="height:60px; object-fit:contain;">
                            <h5 class="card-title"><?= htmlspecialchars($tut['title']) ?></h5>
                            <p class="card-text small"><?= htmlspecialchars(substr($tut['description'], 0, 80)) ?>...</p>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">No tutorials found.</p>
        <?php endif; ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function () {
    $('#searchTutorials').on('input', function () {
        const query = $(this).val().trim();

        $.get('ajax/tutorials-search.php', { q: query }, function (data) {
            $('#tutorialResults').html(data);
        });
    });
});
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>
