<?php
require_once('../includes/db.php');
require_once('../includes/session.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        @media(max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include('partials/sidebar.php'); ?>

<!-- Main content -->
<div class="main-content">
    <?php include('partials/header.php'); ?>

    <div class="container-fluid mt-4">
        <h2>Welcome to Admin Dashboard</h2>
        <p>Use the sidebar to manage tutorials and courses.</p>
    </div>
</div>

</body>
</html>
