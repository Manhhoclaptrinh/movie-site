<?php
require_once "config/db.php";
require_once "controllers/MovieFilterController.php";

// Khởi tạo controller
$movieFilterController = new MovieFilterController($conn);

// Lấy dữ liệu
$data = $movieFilterController->index();

// Gán biến để sử dụng trong view
$movies = $data['movies'];
$count = $data['count'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách phim</title>
    <link rel="stylesheet" href="assets/css/list_movies.css">
</head>
<body>

<h1>🎬 Danh sách phim</h1>
<p>Tìm thấy <b><?= $count ?></b> phim</p>

<div class="movie-grid">
<?php while ($m = $movies->fetch_assoc()): ?>
    <a href="movie.php?slug=<?= urlencode($m['slug']) ?>" class="movie-card">
        <img src="<?= htmlspecialchars($m['poster']) ?>">
        <h3><?= htmlspecialchars($m['title']) ?></h3>
        <p><?= htmlspecialchars($m['category_name']) ?></p>
        <span>👁 <?= number_format($m['views']) ?></span>
    </a>
<?php endwhile; ?>

<?php if ($count === 0): ?>
    <p>❌ Không có phim phù hợp</p>
<?php endif; ?>
</div>

</body>
</html>
