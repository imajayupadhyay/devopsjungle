<?php
require_once('../../includes/db.php');
require_once('../../includes/session.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM contact_messages WHERE id = $id");
    $_SESSION['msg'] = "Message deleted successfully!";
    header("Location: index.php");
    exit;
}

// Fetch messages
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <div style="width: 250px; min-height: 100vh; background-color: #1e1e2f;">
        <?php include('../partials/sidebar.php'); ?>
    </div>
    <div class="flex-grow-1">
        <?php include('../partials/header.php'); ?>
        <div class="container mt-4">
            <h4 class="mb-3">Contact Messages</h4>

            <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
            <?php endif; ?>

            <?php if (count($messages) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th style="width: 80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $i => $msg): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($msg['name']) ?></td>
                                    <td><?= htmlspecialchars($msg['email']) ?></td>
                                    <td><?= htmlspecialchars($msg['subject']) ?></td>
                                    <td><?= htmlspecialchars(substr($msg['message'], 0, 100)) ?>...</td>
                                    <td><?= date('d M Y, H:i', strtotime($msg['created_at'])) ?></td>
                                    <td>
                                        <a href="?delete=<?= $msg['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No messages yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
