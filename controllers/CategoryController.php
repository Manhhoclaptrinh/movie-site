<?php
require_once __DIR__ . "/../models/CategoryModel.php";

class CategoryController {
    private $model;
    
    public function __construct($dbConnection) {
        $this->model = new CategoryModel($dbConnection);
    }
    
    /**
     * Xử lý thêm thể loại
     */
    public function handleAddCategory($name, $slug, $description) {
        $name = trim($name);
        $slug = strtolower(trim($slug));
        $description = trim($description);
        
        if (empty($name) || empty($slug)) {
            return [
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin!'
            ];
        }
        
        if ($this->model->addCategory($name, $slug, $description)) {
            return [
                'success' => true,
                'message' => 'Thêm thể loại thành công!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Lỗi khi thêm thể loại!'
            ];
        }
    }
    
    /**
     * Xử lý cập nhật thể loại
     */
    public function handleUpdateCategory($id, $name, $slug, $description) {
        $id = intval($id);
        $name = trim($name);
        $slug = strtolower(trim($slug));
        $description = trim($description);
        
        if (empty($name) || empty($slug)) {
            return [
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin!'
            ];
        }
        
        if ($this->model->updateCategory($id, $name, $slug, $description)) {
            return [
                'success' => true,
                'message' => 'Cập nhật thể loại thành công!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật thể loại!'
            ];
        }
    }
    
    /**
     * Xử lý xóa thể loại
     */
    public function handleDeleteCategory($id) {
        $id = intval($id);
        
        // Kiểm tra xem có phim nào đang sử dụng category này không
        $movieCount = $this->model->countMoviesByCategory($id);
        
        if ($movieCount > 0) {
            return [
                'success' => false,
                'message' => "Không thể xóa thể loại này vì đang có {$movieCount} phim sử dụng!"
            ];
        }
        
        if ($this->model->deleteCategory($id)) {
            return [
                'success' => true,
                'message' => 'Xóa thể loại thành công!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa thể loại!'
            ];
        }
    }
    
    /**
     * Lấy danh sách tất cả thể loại
     */
    public function getAllCategories() {
        return $this->model->getAllCategoriesWithMovieCount();
    }
    
    /**
     * Lấy thông tin thể loại để sửa
     */
    public function getCategoryForEdit($id) {
        return $this->model->getCategoryById(intval($id));
    }
}
?>
