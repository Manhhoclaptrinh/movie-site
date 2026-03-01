<?php
require_once __DIR__ . "/../models/MovieModel.php";

class AddMovieController {
    private $model;
    private $uploadDir;
    
    public function __construct($db_connection) {
        $this->model = new MovieModel($db_connection);
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/movie-site/uploads/posters/";
    }
    
    /**
     * Kiểm tra đăng nhập admin
     */
    public function checkAdminLogin() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header("Location: login.php");
            exit;
        }
    }
    
    /**
     * Lấy danh sách categories
     */
    public function getCategories() {
        return $this->model->getAllCategories();
    }
    
    /**
     * Xử lý upload poster
     */
    private function handlePosterUpload() {
        $poster = 'default2.png';
        
        if (isset($_FILES['poster']) && 
            !empty($_FILES['poster']['name']) && 
            $_FILES['poster']['error'] === UPLOAD_ERR_OK && 
            $_FILES['poster']['size'] > 0) {
            
            $originalName = $_FILES['poster']['name'];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allow = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($ext, $allow)) {
                // Lấy tên file không có extension
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                
                // XÓA TẤT CẢ SỐ Ở ĐẦU TÊN FILE
                $fileName = preg_replace('/^[0-9]+[_\-]*/', '', $fileName);
                
                // Nếu tên file rỗng sau khi xóa số, dùng tên mặc định
                if (empty($fileName) || trim($fileName) == '') {
                    $fileName = 'poster_' . time();
                }
                
                // Tạo tên file cuối cùng
                $poster = $fileName . '.' . $ext;
                
                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists($this->uploadDir)) {
                    mkdir($this->uploadDir, 0755, true);
                }
                
                // Upload file
                if (!move_uploaded_file($_FILES['poster']['tmp_name'], $this->uploadDir . $poster)) {
                    // Upload thất bại, dùng default
                    $poster = 'default2.png';
                }
            }
        }
        
        return $poster;
    }
    
    /**
     * Xử lý form submit
     */
    public function handleSubmit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý upload poster
            $poster = $this->handlePosterUpload();
            
            // Chuẩn bị dữ liệu
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'category_id' => $_POST['category_id'],
                'release_year' => $_POST['release_year'],
                'country' => $_POST['country'],
                'poster' => $poster,
                'is_series' => $_POST['is_series']
            ];
            
            // Lưu vào database
            if ($this->model->addMovie($data)) {
                header("Location: ../movies.php?success=added");
                exit;
            }
        }
    }
    
    /**
     * Hiển thị trang add movie
     */
    public function index() {
        $this->checkAdminLogin();
        
        // Xử lý submit nếu có
        $this->handleSubmit();
        
        // Lấy categories cho form
        return [
            'categories' => $this->getCategories()
        ];
    }
}
