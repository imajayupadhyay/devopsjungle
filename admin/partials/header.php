<!-- partials/header.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h5">Admin Panel</span>
        <div class="ms-auto">
            <span class="me-3 text-muted">👋 Hello, <?= $_SESSION['admin_name'] ?? 'Admin' ?></span>
        </div>
    </div>
</nav>
