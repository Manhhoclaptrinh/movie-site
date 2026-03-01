<?php
session_start();
require_once "../config/db.php";

/*
|--------------------------------------------------------------------------
| 1. KIỂM TRA THAM SỐ
|--------------------------------------------------------------------------
*/
if (!isset($_GET['slug'])) {
    die("❌ Không tìm thấy phim");
}

$slug = trim($_GET['slug']);
$episode_number = isset($_GET['ep']) ? (int)$_GET['ep'] : null;

/*
|--------------------------------------------------------------------------
| 2. LẤY THÔNG TIN PHIM
|--------------------------------------------------------------------------
*/
$stmt = $conn->prepare("
    SELECT *
    FROM movies
    WHERE slug = ?
    LIMIT 1
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();

if (!$movie) {
    die("❌ Phim không tồn tại");
}

/*
|--------------------------------------------------------------------------
| 3. RESUME – XEM TIẾP (NẾU ĐÃ ĐĂNG NHẬP)
|--------------------------------------------------------------------------
*/
$last_time = 0;

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("
        SELECT last_time
        FROM watch_history
        WHERE user_id = ? AND movie_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $_SESSION['user_id'], $movie['id']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $last_time = (int)($row['last_time'] ?? 0);
    $stmt->close();
}

/*
|--------------------------------------------------------------------------
| 4. XÁC ĐỊNH VIDEO CẦN PHÁT
|--------------------------------------------------------------------------
*/
$video_url = null;

/* ===== PHIM BỘ ===== */
if ((int)$movie['is_series'] === 1) {

    if (!$episode_number) {
        $episode_number = 1;
    }

    $ep = $conn->prepare("
        SELECT episode_number, video_url
        FROM episodes
        WHERE movie_id = ? AND episode_number = ?
        LIMIT 1
    ");
    $ep->bind_param("ii", $movie['id'], $episode_number);
    $ep->execute();
    $episode = $ep->get_result()->fetch_assoc();

    if (!empty($episode) && !empty($episode['video_url'])) {
        $video_url = $episode['video_url'];
    }

}
/* ===== PHIM LẺ ===== */
elseif (!empty($movie['video_url'])) {
    $video_url = $movie['video_url'];
}

/* ===== ÉP PATH LOCAL ===== */
if ($video_url && !preg_match('#^https?://#', $video_url)) {
    $video_url = '/' . ltrim($video_url, '/');
}


/*
|--------------------------------------------------------------------------
| 5. DANH SÁCH TẬP (NẾU LÀ PHIM BỘ)
|--------------------------------------------------------------------------
*/
$episodes = [];

if ((int)$movie['is_series'] === 1) {
    $list = $conn->prepare("
        SELECT episode_number
        FROM episodes
        WHERE movie_id = ?
        ORDER BY episode_number ASC
    ");
    $list->bind_param("i", $movie['id']);
    $list->execute();
    $episodes = $list->get_result()->fetch_all(MYSQLI_ASSOC);
}

/*
|--------------------------------------------------------------------------
| 6. TĂNG LƯỢT XEM
|--------------------------------------------------------------------------
*/
$conn->query("
    UPDATE movies
    SET views = views + 1
    WHERE id = {$movie['id']}
");

/*
|--------------------------------------------------------------------------
| 7. PHIM LIÊN QUAN
|--------------------------------------------------------------------------
*/
$related = $conn->query("
    SELECT *
    FROM movies
    WHERE category_id = {$movie['category_id']}
      AND id != {$movie['id']}
    LIMIT 6
");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
<meta charset="UTF-8">
<title><?= $movie['title'] ?> - Tập <?= $ep ?></title>

<link rel="stylesheet" href="/movie-site/view/watch/assets/css/watch.css?v=2">

</head>

<body>

<?php include "partials/header.php"; ?>

<div class="watch-container">

    <!-- ================= PLAYER ================= -->
   <div class="player-wrapper">
    <div class="player-frame">

<?php if ($video_url): ?>

<video id="videoPlayer"
       controls
       width="100%"
       preload="metadata"
       data-movie="<?= (int)$movie['id'] ?>"
       data-episode="<?= (int)($episode_number ?? 1) ?>">

    <source src="/movie-site/<?= htmlspecialchars($video_url) ?>" type="video/mp4">

    Trình duyệt không hỗ trợ video
</video>

<?php else: ?>

<div class="no-video">
    ❌ Chưa có video cho tập này
</div>

<?php endif; ?>

    </div>
</div>

    <!-- ================= INFO ================= -->
    <?php include "partials/movie_info.php"; ?>

    <!-- ================= EPISODES ================= -->
    <?php if ((int)$movie['is_series'] === 1): ?>
        <section class="episodes-section">
            <h3>📺 Danh sách tập</h3>

            <div class="episode-list">
    <?php foreach ($episodes as $ep): ?>
        <a
            class="episode-btn <?= $ep['episode_number'] == $episode_number ? 'active' : '' ?>"
            href="watch.php?slug=<?= urlencode($slug) ?>&ep=<?= $ep['episode_number'] ?>"
        >
            <span class="ep-number">Tập <?= $ep['episode_number'] ?></span>
        </a>
    <?php endforeach; ?>
</div>

        </section>
    <?php endif; ?>

    <!-- ================= RELATED ================= -->
    <?php include "partials/related.php"; ?>

</div>

<?php include "partials/footer.php"; ?>

</body>
</html>
