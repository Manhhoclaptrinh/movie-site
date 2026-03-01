<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controllers/MovieController.php';

// Khởi tạo controller
$movieController = new MovieController($conn);

// Kiểm tra quyền admin
$movieController->checkAdminAuth();

// Lấy dữ liệu
$data = $movieController->listMovies();

// Gán biến để sử dụng trong view
$movies = $data['movies'];
$mostViewed = $data['mostViewed'];
$newestMovies = $data['newestMovies'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách phim</title>
    <link rel="stylesheet" href="/movie-site/assets/css/list_movies.css">
</head>
<body>

<h1>🎬 Danh sách phim</h1>
<div class="movie-sections">

<!-- XEM NHIỀU NHẤT -->
<section class="movie-box">
    <h2>🔥 Phim xem nhiều nhất</h2>
    <div class="movie-grid">
        <?php while ($m = $mostViewed->fetch_assoc()): ?>
            <div class="movie-card">
                <img src="<?= htmlspecialchars($m['poster']) ?>">
                <h3><?= htmlspecialchars($m['title']) ?></h3>
                <p><?= $m['category_name'] ?></p>
                <span>👁 <?= number_format($m['views']) ?></span>
            </div>
        <?php endwhile ?>
    </div>
</section>

<!-- PHIM MỚI NHẤT -->
<section class="movie-box">
    <h2>🆕 Phim mới nhất</h2>
    <div class="movie-grid">
        <?php while ($m = $newestMovies->fetch_assoc()): ?>
            <div class="movie-card">
                <img src="<?= htmlspecialchars($m['poster']) ?>">
                <h3><?= htmlspecialchars($m['title']) ?></h3>
                <p><?= $m['category_name'] ?></p>
                <span>📅 <?= date('d/m/Y', strtotime($m['created_at'])) ?></span>
            </div>
        <?php endwhile ?>
    </div>
</section>

</div>
<!-- DANH SÁCH PHIM -->
<table>
<tr>
    <th>POSTER</th>
    <th>TÊN</th>
    <th>THỂ LOẠI</th>
    <th>LƯỢT XEM</th>
</tr>

<?php while ($m = $movies->fetch_assoc()): ?>
<tr>
    <td>
        <img src="<?= htmlspecialchars($m['poster']) ?>">
    </td>

    <td><?= htmlspecialchars($m['title']) ?></td>
    <td><?= $m['category_name'] ?></td>
    <td><?= number_format($m['views']) ?></td>
</tr>
<?php endwhile ?>
</table>

</body>
</html>
