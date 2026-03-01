<?php
//Tùng's Code
require_once __DIR__ . "/../models/ProfileModel.php";

class ProfileController {
    private $profileModel;
    private $message = "";
    private $msg_type = "";
    
    public function __construct($connection) {
        $this->profileModel = new ProfileModel($connection);
    }
    
    /**
     * Kiểm tra đăng nhập
     */
    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: view/quanly_user/login.php');
            exit;
        }
    }
    
    /**
     * Xử lý cập nhật thông tin
     */
    public function updateInfo($user_id) {
        if (isset($_POST['btn_update_info'])) {
            $full_name = trim($_POST['full_name']);
            
            if ($this->profileModel->updateFullName($user_id, $full_name)) {
                $_SESSION['full_name'] = $full_name;
                $this->message = "Đã cập nhật tên hiển thị!";
                $this->msg_type = "success";
            }
        }
    }
    
    /**
     * Xử lý đổi mật khẩu
     */
    public function changePassword($user_id) {
        if (isset($_POST['btn_change_pass'])) {
            $old_pass = $_POST['old_password'];
            $new_pass = $_POST['new_password'];
            $confirm_pass = $_POST['confirm_password'];
            
            // Lấy mật khẩu hiện tại
            $current_password = $this->profileModel->getUserPassword($user_id);
            
            // Kiểm tra mật khẩu cũ
            if (!password_verify($old_pass, $current_password)) {
                $this->message = "Mật khẩu cũ không đúng!";
                $this->msg_type = "error";
            } 
            // Kiểm tra mật khẩu xác nhận
            elseif ($new_pass !== $confirm_pass) {
                $this->message = "Mật khẩu xác nhận không khớp!";
                $this->msg_type = "error";
            } 
            // Kiểm tra độ dài mật khẩu
            elseif (strlen($new_pass) < 6) {
                $this->message = "Mật khẩu quá ngắn (tối thiểu 6 ký tự)!";
                $this->msg_type = "error";
            } 
            // Cập nhật mật khẩu mới
            else {
                $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                if ($this->profileModel->updatePassword($user_id, $hashed_pass)) {
                    $this->message = "Đổi mật khẩu thành công!";
                    $this->msg_type = "success";
                }
            }
        }
    }
    
    /**
     * Lấy thông tin user
     */
    public function getUserInfo($user_id) {
        return $this->profileModel->getUserById($user_id);
    }
    
    /**
     * Lấy thông báo
     */
    public function getMessage() {
        return $this->message;
    }
    
    /**
     * Lấy loại thông báo
     */
    public function getMessageType() {
        return $this->msg_type;
    }
}
