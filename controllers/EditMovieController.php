<?php
require_once __DIR__ . "/../models/MovieModel.php";

class EditMovieController {
    private $model;
    private $uploadDir;
    
    public function __construct($db_connection) {
        $this->model = new MovieModel($db_connection);
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/movie-site/uploads/posters/";
    }
    
    /**
     * Validate ID
     */
    private function validateId($id) {
        if (!isset($id) || !is_numeric($id)) {
            die("❌ ID phim không hợp lệ");
        }
        return (int)$id;
    }
    
    /**
     * Lấy thông tin phim
     */
    public function getMovie($id) {
        $id = $this->validateId($id);
        $movie = $this->model->getMovieById($id);
        
        if (!$movie) {
            die("❌ Không tìm thấy phim");
        }
        
        return $movie;
    }
    
    /**
     * Lấy danh sách categories
     */
    public function getCategories() {
        return $this->model->getAllCategories();
    }
    
    /**
     * Xử lý upload poster mới
     */
    private function handlePosterUpload($oldPoster) {
        if (!isset($_FILES['poster']) || 
            empty($_FILES['poster']['name']) || 
            $_FILES['poster']['error'] !== UPLOAD_ERR_OK) {
            return $oldPoster; // Giữ nguyên poster cũ
        }
        
        $originalName = $_FILES['poster']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allow = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (!in_array($ext, $allow)) {
            return $oldPoster;
        }
        
        // Xóa số ở đầu tên file
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $fileName = preg_replace('/^[0-9]+[_\-]*/', '', $fileName);
        
        if (empty($fileName) || trim($fileName) == '') {
            $fileName = 'poster_' . time();
        }
        
        $newPoster = $fileName . '.' . $ext;
        
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        
        // Upload file mới
        if (move_uploaded_file($_FILES['poster']['tmp_name'], $this->uploadDir . $newPoster)) {
            // Xóa poster cũ nếu không phải default
            if ($oldPoster && $oldPoster !== 'default.png' && $oldPoster !== 'default2.png') {
                $oldPath = $this->uploadDir . $oldPoster;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            return $newPoster;
        }
        
        return $oldPoster;
    }
    
    /**
     * Xử lý cập nhật phim
     */
    public function handleUpdate($id, $movie) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            // Xử lý upload poster
            $poster = $this->handlePosterUpload($movie['poster']);
            
            // Chuẩn bị dữ liệu
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'category_id' => $_POST['category_id'],
                'release_year' => $_POST['release_year'] ?? $movie['release_year'],
                'country' => $_POST['country'] ?? $movie['country'],
                'poster' => $poster,
                'is_series' => $_POST['is_series'] ?? $movie['is_series']
            ];
            
            // Cập nhật database
            if ($this->model->updateMovie($id, $data)) {
                header("Location: manage_movies.php?success=updated");
                exit;
            }
        }
    }
    
    /**
     * Entry point
     */
    public function index() {
        $id = $_GET['id'] ?? null;
        $id = $this->validateId($id);
        
        // Lấy thông tin phim
        $movie = $this->getMovie($id);
        
        // Xử lý update nếu có
        $this->handleUpdate($id, $movie);
        
        // Trả về dữ liệu cho view
        return [
            'movie' => $movie,
            'categories' => $this->getCategories()
        ];
    }
}
