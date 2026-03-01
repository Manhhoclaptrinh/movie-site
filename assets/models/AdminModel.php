<?php
class AdminModel {
    private $conn;
    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    
    /**
     * Tìm admin theo username và status = 1
     */
    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM admin WHERE username = ? AND status = 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        return $admin;
    }
    
    /**
     * Cập nhật thời gian đăng nhập cuối
     */
    public function updateLastLogin($adminId) {
        $stmt = $this->conn->prepare("UPDATE admin SET last_login = NOW() WHERE id = ?");
        $stmt->bind_param("i", $adminId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>
