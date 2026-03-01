<?php
session_start();
require_once "../config/db.php";
require_once "../models/MovieErrorReportModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = (int)$_POST['movie_id'];
    $content  = trim($_POST['content']);

    if ($movie_id <= 0 || $content === '') {
        die("❌ Dữ liệu không hợp lệ");
    }

    $model = new MovieErrorReportModel($conn);

   if ($model->create($movie_id, $content)) {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "&report=success");
    exit;
}
}
