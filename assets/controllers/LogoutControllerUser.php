<?php
//Tùng's Code
class LogoutController {
    
    /**
     * Xử lý đăng xuất
     */
    public function logout() {
        // Xóa tất cả các biến session
        $_SESSION = array();
        
        // Hủy session
        session_destroy();
        
        // Xóa cookie ghi nhớ đăng nhập (nếu có)
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, '/');
        }
        
        // Chuyển hướng về trang đăng nhập
        header("Location: http://localhost/movie-site/view/quanly_user/login.php");
        exit;
    }
}

// Khởi tạo session
session_start();

// Thực thi logout
$logoutController = new LogoutController();
$logoutController->logout();
?>
