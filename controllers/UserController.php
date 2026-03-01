<?php
require_once __DIR__ . "/../models/UserModel.php";

class UserController {
    private $model;
    
    public function __construct($dbConnection) {
        $this->model = new UserModel($dbConnection);
    }
    
    /**
     * Lấy danh sách tất cả users
     */
    public function getAllUsers() {
        return $this->model->getAllUsers();
    }
    
    /**
     * Lấy thông tin user để sửa
     */
    public function getUserForEdit($id) {
        return $this->model->getUserById(intval($id));
    }
    
    /**
     * Xử lý xóa user
     */
    public function handleDeleteUser($id) {
        $id = intval($id);
        
        if ($this->model->deleteUser($id)) {
            return [
                'success' => true,
                'message' => 'Xóa tài khoản thành công!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa tài khoản!'
            ];
        }
    }
    
    /**
     * Xử lý cập nhật user
     */
    public function handleUpdateUser($id, $fullName, $phone, $role) {
        $id = intval($id);
        $fullName = trim($fullName);
        $phone = trim($phone);
        
        if (empty($phone)) {
            return [
                'success' => false,
                'message' => 'Số điện thoại không được để trống!'
            ];
        }
        
        if ($this->model->updateUser($id, $fullName, $phone, $role)) {
            return [
                'success' => true,
                'message' => 'Cập nhật tài khoản thành công!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật tài khoản!'
            ];
        }
    }
    
    /**
     * Đếm tổng số users
     */
    public function getTotalUsers() {
        return $this->model->countAllUsers();
    }
}
?>
