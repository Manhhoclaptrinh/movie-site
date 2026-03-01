<?php
//Tùng's Code
class UserAuthModel {
    private $conn;
    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    
    /**
     * Tìm user theo số điện thoại
     */
    public function findUserByPhone($phone) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        return $user;
    }
    
    /**
     * Ghi log đăng nhập
     */
    public function writeLoginLog($phone, $statusText) {
        try {
            $ip = $_SERVER['REMOTE_ADDR'];
            $action = "Khách: " . $statusText . " - IP: " . $ip;
            
            $stmt = $this->conn->prepare("INSERT INTO login_logs (username, action) VALUES (?, ?)");
            $stmt->bind_param("ss", $phone, $action);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
