<?php
require_once('../includes/db.php');

$search = $_GET['q'] ?? '';
$search = $conn->real_escape_string($search);

$sql = "SELECT * FROM tutorials WHERE title LIKE '%$search%' OR description LIKE '%$search%' ORDER BY position ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($tut = $result->fetch_assoc()) {
        echo '<div class="col-lg-3 col-md-4 col-sm-6 tutorial-item">
                <a href="' . htmlspecialchars($tut['link'] ?: '#') . '" class="card h-100 text-decoration-none text-dark tutorial-card">
                    <div class="card-body text-center">
                        <img src="' . ((!empty($tut['file']) && file_exists('../' . $tut['file'])) ? $tut['file'] : 'assets/images/note-icon.png') . '" 
                             class="mb-3" alt="Tutorial Image" style="height:60px; object-fit:contain;">
                        <h5 class="card-title">' . htmlspecialchars($tut['title']) . '</h5>
                        <p class="card-text small">' . htmlspecialchars(substr($tut['description'], 0, 80)) . '...</p>
                    </div>
                </a>
            </div>';
    }
} else {
    echo '<p class="text-center text-muted">No tutorials found.</p>';
}
?>
