<?php
require_once __DIR__ . "/../models/UserModel.php";

class EditUserController {
    private $model;
    
    public function __construct($db_connection) {
        $this->model = new UserModel($db_connection);
    }
    
    /**
     * Validate và lấy ID
     */
    private function validateId($id) {
        if (!isset($id)) {
            header("Location: manage_users.php");
            exit;
        }
        return intval($id);
    }
    
    /**
     * Lấy thông tin user
     */
    public function getUser($id) {
        $id = $this->validateId($id);
        $user = $this->model->getUserById($id);
        
        if (!$user) {
            echo "Không tìm thấy người dùng!";
            exit;
        }
        
        return $user;
    }
    
    /**
     * Xử lý cập nhật user
     */
    public function handleUpdate($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'role' => $_POST['role']
            ];
            
            // Kiểm tra có đổi mật khẩu không
            $hasPassword = !empty($_POST['password']);
            
            if ($hasPassword) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $result = $this->model->updateUserWithPassword($id, $data);
            } else {
                $result = $this->model->updateUser($id, $data);
            }
            
            if ($result) {
                echo "<script>alert('Cập nhật thành công!'); window.location='manage_users.php';</script>";
                exit;
            } else {
                echo "<script>alert('Lỗi khi cập nhật!');</script>";
            }
        }
    }
    
    /**
     * Entry point
     */
    public function index() {
        $id = $_GET['id'] ?? null;
        $id = $this->validateId($id);
        
        // Lấy thông tin user
        $user = $this->getUser($id);
        
        // Xử lý update nếu có
        $this->handleUpdate($id);
        
        // Trả về dữ liệu cho view
        return [
            'user' => $user
        ];
    }
}
