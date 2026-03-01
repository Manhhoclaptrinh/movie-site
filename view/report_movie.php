<?php
session_start();
$movie_id = $_GET['movie_id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo lỗi phim</title>
</head>
<body>

<h3>🚨 Báo lỗi phim</h3>

<form id="reportForm">
    <input type="hidden" name="movie_id" value="<?= $movie_id ?>">

    <textarea name="content" id="content" rows="5" cols="50"
        placeholder="Nhập lỗi phim..." required></textarea>
    <br><br>

    <!-- ❗ PHẢI CÓ type="submit" -->
    <button type="submit">Gửi báo lỗi</button>
</form>

<div id="result"></div>

<!-- ❗❗ PHẢI LOAD JS -->
<script src="/public/js/report_movie.js"></script>

</body>
</html>
