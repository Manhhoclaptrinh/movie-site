<?php
// admin/pages/logout.php
session_start();

require_once __DIR__ . "/../../controllers/LogoutController.php";

// Khởi tạo controller
$controller = new LogoutController();

// Xử lý đăng xuất
$controller->handleLogout();

// Redirect về trang login
$controller->redirectToLogin();
?>
