<?php
class LoginHistoryModel {
    private $conn;
    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    
    /**
     * Kiểm tra xem cột created_at có tồn tại không
     */
    public function hasCreatedAtColumn() {
        $checkCol = $this->conn->query("SHOW COLUMNS FROM login_logs LIKE 'created_at'");
        return $checkCol && $checkCol->num_rows > 0;
    }
    
    /**
     * Lấy danh sách lịch sử đăng nhập (100 bản ghi gần nhất)
     */
    public function getLoginHistory($limit = 100) {
        if ($this->hasCreatedAtColumn()) {
            $sql = "SELECT * FROM login_logs ORDER BY created_at DESC LIMIT ?";
        } else {
            $sql = "SELECT * FROM login_logs ORDER BY log_id DESC LIMIT ?";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result;
    }
    
    /**
     * Xóa toàn bộ lịch sử đăng nhập
     */
    public function clearAllHistory() {
        return $this->conn->query("TRUNCATE TABLE login_logs");
    }
}
?>
