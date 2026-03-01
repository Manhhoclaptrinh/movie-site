<?php
require_once __DIR__ . "/../models/EpisodeModel.php";

class EpisodeController {
    private $model;
    
    public function __construct($db_connection) {
        $this->model = new EpisodeModel($db_connection);
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
     * Validate movie_id
     */
    private function validateMovieId($movie_id) {
        if (!$movie_id) {
            die("Thiếu movie_id");
        }
        return (int)$movie_id;
    }
    
    /**
     * Lấy thông tin phim
     */
    public function getMovie($movie_id) {
        $movie = $this->model->getMovieById($movie_id);
        
        if (!$movie) {
            die("Không tìm thấy phim");
        }
        
        return $movie;
    }
    
    /**
     * Lấy danh sách tập phim
     */
    public function getEpisodes($movie_id) {
        return $this->model->getEpisodesByMovieId($movie_id);
    }
    
    /**
     * Xử lý thêm tập mới
     */
    public function handleAddEpisode($movie_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $episode_number = (int)$_POST['episode_number'];
            $video_url = trim($_POST['video_url']);
            
            // Validate
            if ($episode_number <= 0) {
                return ['success' => false, 'message' => 'Số tập phải lớn hơn 0'];
            }
            
            if (empty($video_url)) {
                return ['success' => false, 'message' => 'Link video không được để trống'];
            }
            
            // Kiểm tra tập đã tồn tại chưa
            if ($this->model->isEpisodeExists($movie_id, $episode_number)) {
                return ['success' => false, 'message' => 'Tập ' . $episode_number . ' đã tồn tại'];
            }
            
            // Thêm tập mới
            if ($this->model->addEpisode($movie_id, $episode_number, $video_url)) {
                header("Location: episodes.php?movie_id=" . $movie_id);
                exit;
            } else {
                return ['success' => false, 'message' => 'Lỗi khi thêm tập phim'];
            }
        }
        
        return null;
    }
    
    /**
     * Lấy đường dẫn poster
     */
    public function getPosterPath($poster) {
        if (empty($poster)) {
            return null;
        }
        
        // Xóa tiền tố nếu có
        $poster = str_replace('uploads/posters/', '', $poster);
        
        $posterFile = __DIR__ . '/../../uploads/posters/' . $poster;
        $posterSrc = '/movie-site/uploads/posters/' . $poster;
        
        if (file_exists($posterFile)) {
            return $posterSrc . '?v=' . time();
        }
        
        return null;
    }
    
    /**
     * Entry point
     */
    public function index() {
        // Kiểm tra đăng nhập
        $this->checkAdminLogin();
        
        // Validate và lấy movie_id
        $movie_id = $_GET['movie_id'] ?? 0;
        $movie_id = $this->validateMovieId($movie_id);
        
        // Lấy thông tin phim
        $movie = $this->getMovie($movie_id);
        
        // Xử lý thêm tập mới nếu có
        $addResult = $this->handleAddEpisode($movie_id);
        
        // Lấy danh sách tập
        $episodes = $this->getEpisodes($movie_id);
        
        // Đếm số tập
        $episodeCount = count($episodes);
        
        // Lấy đường dẫn poster
        $posterSrc = $this->getPosterPath($movie->poster ?? '');
        
        // Trả về dữ liệu cho view
        return [
            'movie' => $movie,
            'episodes' => $episodes,
            'episode_count' => $episodeCount,
            'poster_src' => $posterSrc,
            'movie_id' => $movie_id,
            'add_result' => $addResult
        ];
    }

    public function delete() {
        $this->checkAdminLogin();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $movie_id = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 0;

        if ($id > 0) {
            $this->model->deleteEpisode($id);
        }

        header("Location: /movie-site/view/admin/episodes.php?movie_id=" . $movie_id);
        exit();
    }
}
