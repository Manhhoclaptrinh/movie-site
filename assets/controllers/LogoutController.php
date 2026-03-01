<?php
require_once __DIR__ . "/../models/SessionModel.php";

class LogoutController {
    private $model;
    
    public function __construct() {
        $this->model = new SessionModel();
    }
    
    /**
     * Xử lý đăng xuất
     */
    public function handleLogout() {
        // Xóa tất cả session variables
        $this->model->clearSessionData();
        
        // Xóa session cookie
        $this->model->clearSessionCookie();
        
        // Hủy session
        $this->model->destroySession();
        
        return true;
    }
    
    /**
     * Redirect về trang login
     */
    public function redirectToLogin() {
        header("Location: login.php?logged_out=1");
        exit;
    }
}
?>
