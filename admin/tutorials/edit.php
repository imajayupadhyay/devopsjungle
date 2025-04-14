<?php
include_once '../../includes/session.php';
include_once '../../includes/db.php';
include_once '../partials/header.php';
include_once '../partials/sidebar.php';

// Get tutorial ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid tutorial ID");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM tutorials WHERE id = $id");

if ($result->num_rows === 0) {
    die("Tutorial not found");
}

$tutorial = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    
    $conn->query("UPDATE tutorials SET title = '$title', description = '$description' WHERE id = $id");
    
    header("Location: index.php?msg=updated");
    exit;
}
?>

<div class="main-content">
    <h2>Edit Tutorial</h2>

    <form action="" method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($tutorial['title']) ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="6" required><?= htmlspecialchars($tutorial['description']) ?></textarea><br><br>

        <button type="submit">Update Tutorial</button>
        <a href="index.php">Cancel</a>
    </form>
</div>
