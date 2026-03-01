<?php
session_start();

// Include controller
require_once __DIR__ . '/../../controllers/LogoutControllerUser.php';

// Khởi tạo và thực thi logout
$logoutController = new LogoutController();
$logoutController->logout();
?>
