<?php
require_once __DIR__ . "/../models/MovieModel.php";

class DeleteMovieController {
    private $model;
    private $uploadDir;
    
    public function __construct($db_connection) {
        $this->model = new MovieModel($db_connection);
        $this->uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/movie-site/uploads/posters/";
    }
    
    //Xóa file poster
    private function deletePosterFile($poster) {
        if ($poster && $poster !== 'default.png' && $poster !== 'default2.png') {
            $path = $this->uploadDir . $poster;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
    
    //Xử lý xóa phim
    public function deleteMovie($id) {
        // Lấy thông tin poster
        $movie = $this->model->getMovieById($id);
        
        if ($movie) {
            // Xóa file poster
            $this->deletePosterFile($movie['poster']);
            
            // Xóa phim khỏi database
            $this->model->deleteMovie($id);
        }
        
        // Redirect về trang danh sách
        header("Location: ../movies.php");
        exit;
    }
    
    //Entry point
    public function index() {
        $id = $_GET['id'] ?? 0;
        $this->deleteMovie($id);
    }
}
