<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/MovieController.php";

// Khởi tạo controller
$controller = new MovieController($conn);

// Xử lý thêm/cập nhật phim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleSaveMovie($_POST, $_FILES);
    header("Location: /movie-site/view/admin/manage_movies.php");
    exit;
}

// Xử lý xóa phim
if (isset($_GET['delete'])) {
    $controller->handleDeleteMovie($_GET['delete']);
    header("Location: /movie-site/view/admin/manage_movies.php");
    exit;
}

// Lấy danh sách phim
$movies = $controller->getAllMovies();

// Lấy danh sách thể loại
$categories = $controller->getAllCategories();

// Lấy phim cần sửa
$editMovie = null;
if (isset($_GET['edit'])) {
    $editMovie = $controller->getMovieForEdit($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Quản lý phim</title>
        <link rel="stylesheet" href="/movie-site/assets/css/manage-movies.css">
    </head>
<body>

<h1>🎬 Quản lý phim</h1>

<form method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $editMovie['id'] ?? '' ?>">

<input name="title" placeholder="Tên phim" required value="<?= $editMovie['title'] ?? '' ?>"><br><br>

<textarea name="description" placeholder="Mô tả"><?= $editMovie['description'] ?? '' ?></textarea><br><br>

<input name="release_year" placeholder="Năm phát hành" value="<?= $editMovie['release_year'] ?? '' ?>"><br><br>

<input name="country" placeholder="Quốc gia" value="<?= $editMovie['country'] ?? '' ?>"><br><br>

<select name="category_id">
<?php foreach ($categories as $c): ?>
<option value="<?= $c['id'] ?>" <?= isset($editMovie) && $editMovie['category_id']==$c['id']?'selected':'' ?>>
<?= $c['name'] ?>
</option>
<?php endforeach ?>
</select>

<label>
<input type="checkbox" name="is_series" <?= isset($editMovie) && $editMovie['is_series'] ? 'checked':'' ?>>
 Phim bộ
</label><br><br>

<input type="file" name="poster"><br><br>

<button type="submit">💾 Lưu phim</button>
</form>

<hr>

<table>
<tr>
<th>Poster</th>
<th>Tên</th>
<th>Thể loại</th>
<th>Lượt xem</th>
<th>Quản lý</th>
</tr>

<?php while ($m = $movies->fetch_assoc()): ?>
<tr>
<td>
    <?php 
    $posterUrl = '/movie-site/' . $m['poster'];
    ?>
    <img 
        src="<?= $posterUrl ?>" 
        width="60"
        onerror="this.onerror=null; this.src='/movie-site/assets/no-image.png';"
    >
</td>
    <td><?= htmlspecialchars($m['title']) ?></td>
    <td><?= $m['category_name'] ?></td>
    <td><?= number_format($m['views']) ?></td>
<td>
    <a href="?edit=<?= $m['id'] ?>" title="Sửa phim">✏️</a>

    <?php if ($m['is_series']): ?>
        <a href="episodes.php?movie_id=<?= $m['id'] ?>" title="Quản lý tập phim">
            <a class="btn btn-small btn-info"href="episodes.php?movie_id=<?= $m['id'] ?>">Tập phim</a>
    <?php endif; ?>

    <a href="?delete=<?= $m['id'] ?>" 
       onclick="return confirm('Xoá phim?')" 
       title="Xoá phim">🗑️</a>
</td>

</tr>
<?php endwhile ?>
</table>

</body>
</html>
