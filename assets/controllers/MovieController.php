<?php
require_once __DIR__ . "/../models/MovieModel.php";

class MovieController {
    private $model;
    
    public function __construct($dbConnection) {
        $this->model = new MovieModel($dbConnection);
    }
    
    /**
     * Tạo slug từ tiêu đề
     */
    private function slugify($text) {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        return trim($text, '-');
    }
    
    /**
     * Upload poster
     */
    private function uploadPoster($file) {
        if (empty($file['name'])) {
            return null;
        }
        
        $folder = $_SERVER['DOCUMENT_ROOT'] . "/movie-site/uploads/posters/";
        
        error_log("Upload folder: " . $folder);
        error_log("File tmp: " . $file['tmp_name']);
        
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        
        $filename = time() . "_" . basename($file['name']);
        $posterPath = "uploads/posters/" . $filename;
        $fullPath = $folder . $filename;
        
        error_log("Moving to: " . $fullPath);
        
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            error_log("Upload SUCCESS: " . $posterPath);
            return $posterPath;
        }
        
        error_log("Upload FAILED");
        return null;
    }
    
    /**
     * Xử lý thêm/cập nhật phim
     */
    public function handleSaveMovie($postData, $fileData) {

        $title = $postData['title'];
        $slug = $this->slugify($title);
        $description = $postData['description'];
        $releaseYear = $postData['release_year'];
        $country = $postData['country'];
        $categoryId = $postData['category_id'];
        $isSeries = isset($postData['is_series']) ? 1 : 0;

        // Upload poster
        $posterPath = $this->uploadPoster($fileData['poster']);

        // =========================
        // THÊM MỚI
        // =========================
        if (empty($postData['id'])) {

            return $this->model->addMovieWithSlug(
                $title,
                $slug,
                $description,
                $posterPath,
                $releaseYear,
                $country,
                $categoryId,
                $isSeries
            );
        }

        // =========================
        // CẬP NHẬT
        // =========================
        $id = $postData['id'];

        if ($posterPath) {

            return $this->model->updateMovieWithPoster(
                $id,
                $title,
                $slug,
                $description,
                $posterPath,
                $releaseYear,
                $country,
                $categoryId,
                $isSeries
            );

        } else {

            return $this->model->updateMovieWithSlugNoPoster(
                $id,
                $title,
                $slug,
                $description,
                $releaseYear,
                $country,
                $categoryId,
                $isSeries
            );
        }
    }
    
    /**
     * Xử lý xóa phim
     */
    public function handleDeleteMovie($id) {
        return $this->model->deleteMovie($id);
    }
    
    /**
     * Lấy danh sách phim
     */
    public function getAllMovies() {
        return $this->model->getAllMoviesWithCategory();
    }
    
    /**
     * Lấy danh sách thể loại
     */
    public function getAllCategories() {
        return $this->model->getAllCategories();
    }
    
    /**
     * Lấy thông tin phim để sửa
     */
    public function getMovieForEdit($id) {
        return $this->model->getMovieById($id);
    }

    /**
     * Lấy dữ liệu cho trang danh sách phim
     */
    public function listMovies() {
        $data = [];
        
        // Lấy tất cả phim
        $data['movies'] = $this->model->getAllMovies();
        
        // Lấy phim xem nhiều nhất
        $data['mostViewed'] = $this->model->getMostViewedMovies(5);
        
        // Lấy phim mới nhất
        $data['newestMovies'] = $this->model->getNewestMovies(5);
        
        return $data;
    }

    /**
     * Kiểm tra quyền admin
     */
    public function checkAdminAuth() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header("Location: login.php");
            exit;
        }
    }
}
?>
