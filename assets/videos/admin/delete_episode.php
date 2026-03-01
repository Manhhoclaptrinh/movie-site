<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/EpisodeController.php";

// Khởi tạo controller
$controller = new EpisodeController($conn);

// Xử lý xóa và redirect
$controller->delete();
