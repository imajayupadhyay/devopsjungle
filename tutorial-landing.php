<?php
require_once('includes/db.php');
require_once('includes/header.php');


$group_slug = $_GET['group'] ?? '';

$stmt = $conn->prepare("SELECT * FROM tutorial_groups WHERE slug = ?");
$stmt->bind_param("s", $group_slug);
$stmt->execute();
$group_result = $stmt->get_result();
$group = $group_result->fetch_assoc();

if (!$group) {
    die("Tutorial group not found.");
}

$page_stmt = $conn->prepare("SELECT * FROM tutorial_pages WHERE group_id = ? AND is_published = 1 ORDER BY position ASC");
$page_stmt->bind_param("i", $group['id']);
$page_stmt->execute();
$page_result = $page_stmt->get_result();

$pages = [];
while ($row = $page_result->fetch_assoc()) {
    $pages[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($group['title']) ?> | DevOpsJungle</title>
    <meta name="description" content="<?= htmlspecialchars(substr($group['description'], 0, 150)) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f5f5f5;
        }

        .landing-hero {
            background: linear-gradient(to right, #14532d, #198754);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .landing-hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
        }

        .landing-hero p {
            font-size: 1.1rem;
            max-width: 800px;
            margin: 15px auto 0;
            line-height: 1.6;
        }

        .topics {
            padding: 40px 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .topic-card {
            background-color: #fff;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.06);
            transition: all 0.25s ease-in-out;
            border-left: 5px solid #198754;
            cursor: pointer;
            position: relative;
            padding-left: 60px;
        }

        .topic-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
            border-left: 5px solid #14532d;
        }

        .topic-card a {
            text-decoration: none;
            color: #212529;
        }

        .topic-card a:hover {
            color: #198754;
        }

        .topic-card .card-body {
            padding: 20px 24px;
        }

        .topic-card h5 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .topic-number {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #198754;
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        @media (max-width: 767px) {
            .landing-hero h1 {
                font-size: 2rem;
            }
            .landing-hero p {
                font-size: 1rem;
            }
            .topic-card {
                padding-left: 50px;
            }
            .topic-number {
                width: 28px;
                height: 28px;
                font-size: 13px;
                top: 16px;
                left: 16px;
            }
        }
    </style>
</head>
<body>

<!-- Hero -->
<section class="landing-hero">
    <div class="container">
        <h1><?= htmlspecialchars($group['title']) ?></h1>
        <p><?= nl2br(htmlspecialchars($group['description'])) ?></p>
    </div>
</section>

<!-- Topics List -->
<section class="topics">
    <div class="row g-4">
        <?php if (count($pages)): ?>
            <?php foreach ($pages as $index => $page): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card topic-card h-100">
                        <a href="/devopsjungle/tutorial/<?= urlencode($group['slug']) ?>/<?= urlencode($page['slug']) ?>">
                            <div class="topic-number"><?= $index + 1 ?></div>
                            <div class="card-body">
                                <h5><?= htmlspecialchars($page['title']) ?></h5>
                                <p class="text-muted mb-0"><?= htmlspecialchars(substr(strip_tags($page['content']), 0, 90)) ?>...</p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No tutorials in this group yet.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer class="text-center text-muted py-4">
    &copy; <?= date('Y') ?> DevOpsJungle. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
