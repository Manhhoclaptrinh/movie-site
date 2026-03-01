<?php
require_once __DIR__ . "/../models/TagModel.php";

class TagController {
    private $model;
    
    public function __construct($dbConnection) {
        $this->model = new TagModel($dbConnection);
    }
    
    /**
     * Kiểm tra bảng tags có tồn tại không
     */
    public function checkTableExists() {
        return $this->model->checkTagsTableExists();
    }
    
    /**
     * Xử lý thêm tag
     */
    public function handleAddTag($name, $slug) {
        $name = trim($name);
        $slug = strtolower(trim($slug));
        
        if (empty($name) || empty($slug)) {
            return [
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin!'
            ];
        }
        
        if (!$this->checkTableExists()) {
            return [
                'success' => false,
                'message' => 'Vui lòng chạy file update_database.sql trước!'
            ];
        }
        
        if ($this->model->addTag($name, $slug)) {
            return [
                'success' => true,
                'message' => 'Thêm tag thành công!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Lỗi khi thêm tag!'
            ];
        }
    }
    
    /**
     * Xử lý cập nhật tag
     */
    public function handleUpdateTag($id, $name, $slug) {
        $id = intval($id);
        $name = trim($name);
        $slug = strtolower(trim($slug));
        
        if (empty($name) || empty($slug)) {
            return [
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin!'
            ];
        }
        
        if ($this->model->updateTag($id, $name, $slug)) {
            return [
                'success' => true,
                'message' => 'Cập nhật tag thành công!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật tag!'
            ];
        }
    }
    
    /**
     * Xử lý xóa tag
     */
    public function handleDeleteTag($id) {
        $id = intval($id);
        
        // Kiểm tra xem có phim nào đang sử dụng tag này không
        $movieCount = $this->model->countMoviesByTag($id);
        
        if ($movieCount > 0) {
            return [
                'success' => false,
                'message' => "Không thể xóa tag này vì đang có {$movieCount} phim sử dụng!"
            ];
        }
        
        if ($this->model->deleteTag($id)) {
            return [
                'success' => true,
                'message' => 'Xóa tag thành công!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa tag!'
            ];
        }
    }
    
    /**
     * Lấy danh sách tất cả tags
     */
    public function getAllTags() {
        if (!$this->checkTableExists()) {
            return null;
        }
        return $this->model->getAllTagsWithMovieCount();
    }
    
    /**
     * Lấy thông tin tag để sửa
     */
    public function getTagForEdit($id) {
        if (!$this->checkTableExists()) {
            return null;
        }
        return $this->model->getTagById(intval($id));
    }
}
?>
