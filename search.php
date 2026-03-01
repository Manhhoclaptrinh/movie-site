<?php
require_once "config/db.php";
require_once "controllers/SearchController.php";
//viet's Code
// Khởi tạo controller
$searchController = new SearchController($conn);

// Xử lý tìm kiếm
$data = $searchController->search();

// Gán biến để sử dụng trong view
$keyword = $data['keyword'];
$movies = $data['movies'];
$count = $data['count'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm kiếm: <?= htmlspecialchars($keyword) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2>Kết quả tìm kiếm</h2>
<p>
    Tìm thấy <b><?= $count ?></b> phim với từ khóa
    "<b><?= htmlspecialchars($keyword) ?></b>"
</p>

<div class="slider" style="padding:20px">
<?php if ($movies && $movies->num_rows > 0): ?>
    <?php while ($m = $movies->fetch_assoc()): ?>
        <a href="movie.php?slug=<?= urlencode($m['slug']) ?>"
           class="movie-card"
           style="text-decoration:none;color:inherit">
            <img src="<?= htmlspecialchars($m['poster']) ?>">
            <span class="badge">👁 <?= (int)$m['views'] ?></span>
            <h3><?= htmlspecialchars($m['title']) ?></h3>
        </a>
    <?php endwhile; ?>
<?php else: ?>
    <p>❌ Không tìm thấy phim phù hợp</p>
<?php endif; ?>
</div>
<div class="search-header">

    <a href="index.php" class="btn-back">
        ← Quay lại trang chủ
    </a>
</div>
</body>
</html>
