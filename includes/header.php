<?php
if (!isset($pageTitle)) {
  $pageTitle = "DevOps Jungle";
}
$currentPath = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($pageTitle) ?></title>
<meta name="description" content="<?= htmlspecialchars($meta_description ?? '') ?>">
<meta name="keywords" content="<?= htmlspecialchars($meta_keywords ?? '') ?>">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/devopsjungle/assets/css/header.css">
  <link rel="stylesheet" href="/devopsjungle/assets/css/style.css">
</head>
<body>

<!-- ✅ NAVBAR START -->
<nav class="custom-navbar navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <!-- Logo -->
    <a class="navbar-brand fw-bold" href="/devopsjungle/index.php">DevOpsJungle</a>

    <!-- Center Nav Menu -->
    <div class="d-none d-lg-flex mx-auto">
      <ul class="navbar-nav gap-4">
        <?php
        $navItems = [
          'Home' => '/devopsjungle/index.php',
          'Tutorials' => '/devopsjungle/tutorials.php',
          'Courses' => '/devopsjungle/courses',
          'Blogs' => '/devopsjungle/blogs',
          'Contact' => '/devopsjungle/contact.php'
        ];
        foreach ($navItems as $name => $link):
          $basename = basename($link);
          if ($basename === 'index.php' && ($currentPath === '/devopsjungle/' || $currentPath === '/devopsjungle/index.php')) {
            $active = 'active';
          } elseif (strpos($currentPath, $basename) !== false) {
            $active = 'active';
          } else {
            $active = '';
          }          
        ?>
          <li class="nav-item">
            <a class="nav-link <?= $active ?>" href="<?= $link ?>"><?= $name ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Right Side (Desktop) -->
    <div class="d-none d-lg-flex align-items-center gap-3">
     
      <!-- <a href="#" class="fs-5 text-dark"><i class="bi bi-search"></i></a> -->
      <form action="/devopsjungle/search.php" method="get">
      <div class="input-group">
        <input type="search" name="q" class="form-control" placeholder="Search...">
        <button class="btn btn-outline-success">Search</button>
      </div>
    </form>
    <a href="/devopsjungle/login.php" class="btn btn-outline-dark btn-sm">Login / Signup</a>
    </div>

    <!-- Hamburger for Mobile -->
    <button class="btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav">
      <i class="bi bi-list fs-3"></i>
    </button>
  </div>
</nav>

<!-- ✅ Mobile Slide-In Menu -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileNav">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="nav flex-column mb-4">
      <?php foreach ($navItems as $name => $link): ?>
        <li class="nav-item mb-2">
          <a class="nav-link text-dark <?= (strpos($currentPath, basename($link)) !== false) ? 'fw-bold text-success' : '' ?>" href="<?= $link ?>">
            <?= $name ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <a href="/devopsjungle/login.php" class="btn btn-success w-100 mb-3">Login / Signup</a>

    <form action="/devopsjungle/search.php" method="get">
      <div class="input-group">
        <input type="search" name="q" class="form-control" placeholder="Search...">
        <button class="btn btn-outline-success">Search</button>
      </div>
    </form>
  </div>
</div>
