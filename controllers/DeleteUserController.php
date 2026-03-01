<?php
require_once __DIR__ . "/../models/UserModel.php";

class DeleteUserController {
    private $model;
    
    public function __construct($db_connection) {
        $this->model = new UserModel($db_connection);
    }
    
    /**
     * Xử lý xóa user
     */
    public function deleteUser($id) {
        try {
            // Kiểm tra user có tồn tại không
            $user = $this->model->getUserById($id);
            
            if (!$user) {
                $this->redirectWithMessage('User không tồn tại!', 'error');
                return;
            }
            
            // Xóa user
            if ($this->model->deleteUser($id)) {
                $this->redirectWithMessage('Đã xóa thành công!', 'success');
            } else {
                $this->redirectWithMessage('Lỗi khi xóa user!', 'error');
            }
        } catch (Exception $e) {
            $this->redirectWithMessage('Lỗi: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Redirect với thông báo
     */
    private function redirectWithMessage($message, $type = 'success') {
        echo "<script>alert('$message'); window.location='manage_users.php';</script>";
        exit;
    }
    
    /**
     * Entry point
     */
    public function index() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $this->deleteUser($id);
        } else {
            header("Location: manage_users.php");
            exit;
        }
    }
}
