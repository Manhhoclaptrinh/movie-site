<?php
class EpisodeModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    /**
     * Lấy thông tin phim theo ID
     */
    public function getMovieById($movie_id) {
        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object();
    }
    
    /**
     * Lấy danh sách tập phim theo movie_id
     */
    public function getEpisodesByMovieId($movie_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM episodes 
            WHERE movie_id = ?
            ORDER BY episode_number ASC
        ");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $episodes = [];
        while ($row = $result->fetch_object()) {
            $episodes[] = $row;
        }
        return $episodes;
    }
    
    /**
     * Đếm số tập phim
     */
    public function countEpisodes($movie_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM episodes WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }
    
    /**
     * Thêm tập phim mới
     */
    public function addEpisode($movie_id, $episode_number, $video_url) {
        $stmt = $this->conn->prepare("
            INSERT INTO episodes (movie_id, episode_number, video_url)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iis", $movie_id, $episode_number, $video_url);
        return $stmt->execute();
    }
    
    /**
     * Kiểm tra tập phim đã tồn tại chưa
     */
    public function isEpisodeExists($movie_id, $episode_number) {
        $stmt = $this->conn->prepare("
            SELECT id FROM episodes 
            WHERE movie_id = ? AND episode_number = ?
        ");
        $stmt->bind_param("ii", $movie_id, $episode_number);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    /**
     * Xóa tập phim
     */
    public function deleteEpisode($id) {
        $stmt = $this->conn->prepare("DELETE FROM episodes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
}
