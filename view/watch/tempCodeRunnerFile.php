<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../../config/db.php";

$user_id = $_SESSION['user_id'] ?? 1;

$slug = $_GET['slug'] ?? '';
$ep = intval($_GET['ep'] ?? 1);

// LẤY PHIM
$stmt = $conn->prepare("SELECT * FROM movies WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
if (!$movie) die("Không tìm thấy phim");

// LẤY TẬP
$stmt = $conn->prepare("
    SELECT * FROM episodes 
    WHERE movie_id = ? AND episode_number = ?
");
$stmt->bind_param("ii", $movie['id'], $ep);
$stmt->execute();
$episode = $stmt->get_result()->fetch_assoc();

// PHIM LIÊN QUAN (cùng category)
$related = null;

if (!empty($movie['category_id'])) {
    $stmt = $conn->prepare("
        SELECT id, title, slug, poster
        FROM movies
        WHERE category_id = ?
          AND id != ?
        LIMIT 6
    ");
    $stmt->bind_param("ii", $movie['category_id'], $movie['id']);
    $stmt->execute();
    $related = $stmt->get_result();
}

// RESUME
$stmt = $conn->prepare("
    SELECT last_time FROM watch_history
    WHERE user_id=? AND movie_id=? AND episode_number=?
");
$stmt->bind_param("iii", $user_id, $movie['id'], $ep);
$stmt->execute();
$resume = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?= $movie['title'] ?> - Tập <?= $ep ?></title>
<link rel="stylesheet" href="assets/css/watch.css">
</head>
<body>

<?php include "partials/header.php"; ?>

<div class="watch-container">
    <?php include "partials/player.php"; ?>
    <?php include "partials/movie_info.php"; ?>
    <?php include "partials/episode_list.php"; ?>
    <?php include "partials/related.php"; ?>
</div>

<?php include "partials/footer.php"; ?>

<script>
const RESUME_TIME = <?= (int)($resume['last_time'] ?? 0) ?>;
const MOVIE_ID = <?= $movie['id'] ?>;
const EP = <?= $ep ?>;
</script>
<script src="assets/js/watch.js"></script>
</body>
</html>
