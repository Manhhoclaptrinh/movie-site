<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/CommentModel.php";

// Chỉ admin mới vào được
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$model = new CommentModel($conn);

// XÓA COMMENT
if (isset($_POST['delete_id'])) {
    $model->deleteComment((int)$_POST['delete_id']);
    header("Location: ../view/admin/manage_comments.php");
    exit;
}

// LẤY DANH SÁCH COMMENT
$comments = $model->getAllComments();
