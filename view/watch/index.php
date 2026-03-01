<?php

session_start();
require_once __DIR__ . "/../../config/db.php";

// =====================
// 1. NHẬN THAM SỐ
// =====================
$slug = $_GET['slug'] ?? '';
$ep   = (int)($_GET['ep'] ?? 1);

if ($slug === '') {
    die("❌ Không tìm thấy phim");
}

// =====================
// 2. LẤY PHIM THEO SLUG
// =====================
$stmt = $conn->prepare("SELECT * FROM movies WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();

if (!$movie) {
    die("❌ Phim không tồn tại");
}

$movie_id = (int)$movie['id'];

// =====================
// 3. LẤY TẬP HIỆN TẠI
// =====================
$stmt = $conn->prepare("
    SELECT * FROM episodes 
    WHERE movie_id = ? AND episode_number = ?
");
$stmt->bind_param("ii", $movie_id, $ep);
$stmt->execute();
$currentEpisode = $stmt->get_result()->fetch_assoc();

// Nếu chưa có tập → về tập 1
if (!$currentEpisode) {
    $ep = 1;
    $stmt = $conn->prepare("
        SELECT * FROM episodes 
        WHERE movie_id = ? AND episode_number = 1
    ");
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $currentEpisode = $stmt->get_result()->fetch_assoc();
}

if (!$currentEpisode) {
    die("❌ Phim chưa có tập");
}

// =====================
// 4. TĂNG VIEW
// =====================
require_once "actions/increase_view.php";

// =====================
// 5. LƯU LỊCH SỬ XEM
// =====================
require_once "actions/save_history.php";

// =====================
// 6. LẤY DANH SÁCH TẬP
// =====================
$episodes = $conn->query("
    SELECT * FROM episodes 
    WHERE movie_id = $movie_id 
    ORDER BY episode_number ASC
");

// =====================
// 7. PHIM LIÊN QUAN
// =====================
$related = $conn->query("
    SELECT * FROM movies 
    WHERE category = '{$movie['category']}'
    AND id != $movie_id
    LIMIT 6
");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($movie['title']) ?> - Tập <?= $ep ?></title>

<link rel="stylesheet" href="css/watch.css">
<script src="js/watch.js" defer></script>
</head>

<body>

<?php include "partials/header.php"; ?>

<div class="watch-container">

    <?php include "partials/player.php"; ?>

    <div class="watch-body">

        <?php include "partials/movie_info.php"; ?>

        <?php include "partials/episode_list.php"; ?>

        <?php include "partials/related.php"; ?>

    </div>

</div>

<?php include "partials/footer.php"; ?>

</body>
</html>
