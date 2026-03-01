<?php
//Tùng's Code
class ProfileModel {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Lấy thông tin user theo ID
     */
    public function getUserById($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Cập nhật tên hiển thị
     */
    public function updateFullName($user_id, $full_name) {
        $stmt = $this->conn->prepare("UPDATE users SET full_name = ? WHERE id = ?");
        $stmt->bind_param("si", $full_name, $user_id);
        return $stmt->execute();
    }
    
    /**
     * Lấy mật khẩu hiện tại của user
     */
    public function getUserPassword($user_id) {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['password'] : null;
    }
    
    /**
     * Cập nhật mật khẩu mới
     */
    public function updatePassword($user_id, $hashed_password) {
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        return $stmt->execute();
    }
}
