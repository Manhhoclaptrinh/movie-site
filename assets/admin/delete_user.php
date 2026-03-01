<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/DeleteUserController.php";

// Khởi tạo controller
$controller = new DeleteUserController($conn);

// Xử lý xóa user
$controller->index();
