<?php
require_once __DIR__ . "/../models/LoginHistoryModel.php";

class LoginHistoryController {
    private $model;
    
    public function __construct($dbConnection) {
        $this->model = new LoginHistoryModel($dbConnection);
    }
    
    /**
     * Xử lý xóa lịch sử
     */
    public function handleClearHistory() {
        if (isset($_POST['clear_history'])) {
            if ($this->model->clearAllHistory()) {
                echo "<script>alert('Đã xóa sạch lịch sử!'); window.location='login_history.php';</script>";
                exit;
            } else {
                echo "<script>alert('Có lỗi xảy ra khi xóa lịch sử!');</script>";
            }
        }
    }
    
    /**
     * Lấy dữ liệu lịch sử để hiển thị
     */
    public function getHistoryData($limit = 100) {
        return $this->model->getLoginHistory($limit);
    }
    
    /**
     * Kiểm tra xem có cột created_at không
     */
    public function hasTimestamp() {
        return $this->model->hasCreatedAtColumn();
    }
}
?>
