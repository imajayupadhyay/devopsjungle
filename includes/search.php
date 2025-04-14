<?php
require_once('includes/db.php');
$pageTitle = "Search Results";
require_once('includes/header.php');

$query = trim($_GET['query'] ?? '');

?>

<div class="container mt-5">
    <h3>Search Results for: <em><?= htmlspecialchars($query) ?></em></h3>

    <?php
    if ($query) {
        // Search in courses
        $stmt = $conn->prepare("SELECT * FROM courses WHERE title LIKE ? OR description LIKE ? ORDER BY position ASC");
        $search = "%$query%";
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h5 class='mt-4'>Courses</h5><div class='row g-4'>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-3">
                        <div class="card h-100 p-3">
                            <div class="card-body d-flex flex-column text-center">
                                <img src="' . ($row['image'] ? htmlspecialchars($row['image']) : 'assets/images/default-icon.png') . '" class="img-fluid mb-3" style="height:60px; object-fit:cover;">
                                <h5>' . htmlspecialchars($row['title']) . '</h5>
                                <p class="small">' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</p>
                                <a href="' . htmlspecialchars($row['link'] ?: '#') . '" class="btn btn-outline-primary btn-sm mt-auto">View</a>
                            </div>
                        </div>
                      </div>';
            }
        } else {
            echo "<p class='text-muted'>No courses found.</p>";
        }
        echo "</div>";

        // Search in tutorials
        $stmt = $conn->prepare("SELECT * FROM tutorials WHERE title LIKE ? OR description LIKE ? ORDER BY position ASC");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h5 class='mt-5'>Tutorials</h5><div class='row g-4'>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-3">
                        <div class="card h-100 p-3">
                            <div class="card-body d-flex flex-column text-center">
                                <img src="' . ($row['file'] ? htmlspecialchars($row['file']) : 'assets/images/note-icon.png') . '" class="img-fluid mb-3" style="height:60px; object-fit:cover;">
                                <h5>' . htmlspecialchars($row['title']) . '</h5>
                                <p class="small">' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</p>
                                <a href="' . htmlspecialchars($row['link'] ?: '#') . '" class="btn btn-outline-success btn-sm mt-auto">Read</a>
                            </div>
                        </div>
                      </div>';
            }
        } else {
            echo "<p class='text-muted'>No tutorials found.</p>";
        }
        echo "</div>";
    } else {
        echo "<p class='text-danger'>Please enter a search term.</p>";
    }

    $conn->close();
    ?>
</div>

<?php require_once('includes/footer.php'); ?>
