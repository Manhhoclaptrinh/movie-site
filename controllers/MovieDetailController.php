<?php
// controllers/MovieDetailController.php

require_once __DIR__ . "/../models/MovieDetailModel.php";

class MovieDetailController {
    private $movieDetailModel;
    private $movie = null;
    private $tags = [];
    private $comments = [];
    private $episodes = [];
    private $ratingStats = [];
    
    public function __construct($connection) {
        $this->movieDetailModel = new MovieDetailModel($connection);
    }
    
    /**
     * Xử lý hiển thị chi tiết phim
     */
    public function show($slug) {
        // 1. Kiểm tra slug
        if (!isset($slug) || empty($slug)) {
            die("❌ Không tìm thấy phim");
        }
        
        // 2. Lấy thông tin phim
        $this->movie = $this->movieDetailModel->getMovieBySlug($slug);
        
        // 3. Nếu không có phim
        if (!$this->movie) {
            die("❌ Phim không tồn tại");
        }
        
        $movie_id = (int)$this->movie['id'];
        
        // 4. Tăng lượt xem
        $this->movieDetailModel->incrementViews($this->movie['id']);
        
        // 5. Ghi log hệ thống
        $action = "View movie: " . $this->movie['title'];
        $this->movieDetailModel->addLog($action);
        
        // 6. Lấy tags phim
        $this->tags = $this->movieDetailModel->getMovieTags($this->movie['id']);
        
        // 7. Lấy bình luận
        $this->comments = $this->movieDetailModel->getComments($this->movie['id']);
        
        // 8. Lấy thống kê đánh giá
        $this->ratingStats = $this->movieDetailModel->getRatingStats($movie_id);
        
        // 9. Nếu là phim bộ → lấy tập phim
        if ((int)$this->movie['is_series'] === 1) {
            $this->episodes = $this->movieDetailModel->getEpisodes($this->movie['id']);
        }
    }
    
    /**
     * Xử lý submit đánh giá
     */
    public function handleRating($slug) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
            
            if (!isset($_SESSION['admin_id'])) {
                die("❌ Chưa đăng nhập");
            }
            
            $rating = (int)$_POST['rating'];
            $admin_id = (int)$_SESSION['admin_id'];
            
            if ($rating < 1 || $rating > 5) {
                die("❌ Rating không hợp lệ");
            }
            
            // Lấy movie_id từ slug
            $movie = $this->movieDetailModel->getMovieBySlug($slug);
            if (!$movie) {
                die("❌ Phim không tồn tại");
            }
            
            $movie_id = (int)$movie['id'];
            
            // Lưu đánh giá
            $this->movieDetailModel->addOrUpdateRating($movie_id, $admin_id, $rating);
            
            header("Location: movie.php?slug=" . urlencode($slug));
            exit;
        }
    }
    
    /**
     * Xử lý thêm bình luận
     */
    public function handleComment($slug) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
            if (!isset($_SESSION['admin_id'])) {
                die("❌ Bạn chưa đăng nhập");
            }
            
            $content = trim($_POST['comment_content']);
            if ($content !== '') {
                // Lấy movie_id từ slug
                $movie = $this->movieDetailModel->getMovieBySlug($slug);
                if (!$movie) {
                    die("❌ Phim không tồn tại");
                }
                
                $this->movieDetailModel->addComment(
                    $movie['id'],
                    $_SESSION['admin_id'],
                    $content
                );
            }
            
            header("Location: movie.php?slug=" . urlencode($slug));
            exit;
        }
    }
    
    /**
     * Lấy dữ liệu phim
     */
    public function getMovie() {
        return $this->movie;
    }
    
    /**
     * Lấy tags
     */
    public function getTags() {
        return $this->tags;
    }
    
    /**
     * Lấy comments
     */
    public function getComments() {
        return $this->comments;
    }
    
    /**
     * Lấy episodes
     */
    public function getEpisodes() {
        return $this->episodes;
    }
    
    /**
     * Lấy thống kê rating
     */
    public function getRatingStats() {
        return $this->ratingStats;
    }
}
