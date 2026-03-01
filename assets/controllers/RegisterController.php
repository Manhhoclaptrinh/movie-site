<?php
//Tùng's Code
require_once __DIR__ . "/../models/UserModel.php";

class RegisterController {
    private $userModel;
    private $error = '';
    private $success = '';
    
    public function __construct($connection) {
        $this->userModel = new UserModel($connection);
    }
    
    /**
     * Xử lý đăng ký
     */
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $full_name = trim($_POST['full_name']);
            $phone = trim($_POST['phone']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // 1. Kiểm tra mật khẩu nhập lại
            if ($password !== $confirm_password) {
                $this->error = "Mật khẩu xác nhận không khớp!";
            } else {
                // 2. Kiểm tra SĐT đã tồn tại chưa 
                if ($this->userModel->checkPhoneExists($phone)) {
                    $this->error = "Số điện thoại này đã được đăng ký rồi!";
                } else {
                    // 3. Thêm tài khoản mới
                    if ($this->userModel->createUser($full_name, $phone, $password)) {
                        $this->success = "Đăng ký thành công! Đang chuyển hướng đăng nhập...";
                        // Tự động chuyển qua trang login sau 1.5 giây
                        echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 1500);</script>";
                    } else {
                        $this->error = "Có lỗi xảy ra, vui lòng thử lại!";
                    }
                }
            }
        }
    }
    
    /**
     * Lấy thông báo lỗi
     */
    public function getError() {
        return $this->error;
    }
    
    /**
     * Lấy thông báo thành công
     */
    public function getSuccess() {
        return $this->success;
    }
}
