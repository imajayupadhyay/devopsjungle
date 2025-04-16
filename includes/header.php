<!-- includes/header.php -->
<?php
if (!isset($pageTitle)) {
    $pageTitle = "DevOps Jungle";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <title><?= $pageTitle ?></title> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }
        body.dark-theme {
            background-color: #121212;
            color: #e4e4e4;
        }
        .navbar {
            transition: background 0.3s;
        }
        .dark-theme .navbar {
            background-color: #1f1f1f !important;
        }
        .theme-toggle {
            border: none;
            background: transparent;
            font-size: 1.2rem;
            color: inherit;
        }
        .dropdown-mega {
            position: static;
        }
        .dropdown-menu-mega {
            display: none;
            width: 100%;
            left: 0;
            right: 0;
            top: 100%;
            border-top: 1px solid #dee2e6;
            padding: 2rem;
            background: white;
        }
        .dropdown-mega:hover .dropdown-menu-mega {
            display: block;
        }
        .dark-theme .dropdown-menu-mega {
            background: #1f1f1f;
            color: white;
        }
        .dropdown-menu-mega a {
            color: inherit;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">DevOpsJungle</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown dropdown-mega">
                    <a class="nav-link dropdown-toggle" href="#" role="button">Courses</a>
                    <div class="dropdown-menu dropdown-menu-mega shadow">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-uppercase">DevOps Tools</h6>
                                <ul class="list-unstyled">
                                    <li><a class="dropdown-item" href="#">Docker</a></li>
                                    <li><a class="dropdown-item" href="#">Kubernetes</a></li>
                                    <li><a class="dropdown-item" href="#">Jenkins</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-uppercase">Cloud</h6>
                                <ul class="list-unstyled">
                                    <li><a class="dropdown-item" href="#">AWS</a></li>
                                    <li><a class="dropdown-item" href="#">Azure</a></li>
                                    <li><a class="dropdown-item" href="#">GCP</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-uppercase">CI/CD & Monitoring</h6>
                                <ul class="list-unstyled">
                                    <li><a class="dropdown-item" href="#">GitHub Actions</a></li>
                                    <li><a class="dropdown-item" href="#">Prometheus</a></li>
                                    <li><a class="dropdown-item" href="#">Grafana</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Tutorials</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
            </ul>
            <form class="d-flex me-3" action="search.php" method="GET">
                <input class="form-control form-control-sm me-2" type="search" placeholder="Search...">
                <button class="btn btn-outline-success btn-sm" type="submit">Search</button>
            </form>
            <!-- <button class="theme-toggle" onclick="toggleTheme()">
                <i class="bi bi-moon-stars-fill"></i>
            </button> -->
        </div>
    </div>
</nav>

<script>
function toggleTheme() {
    const body = document.body;
    const icon = document.querySelector('.theme-toggle i');
    body.classList.toggle('dark-theme');
    if (body.classList.contains('dark-theme')) {
        icon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
    } else {
        icon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
    }
}
</script>