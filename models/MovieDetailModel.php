<?php

class MovieDetailModel {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Lấy thông tin phim theo slug
     */
    public function getMovieBySlug($slug) {
        $sql = "SELECT m.*, c.name AS category_name
                FROM movies m
                LEFT JOIN categories c ON m.category_id = c.id
                WHERE m.slug = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Tăng lượt xem phim
     */
    public function incrementViews($movie_id) {
        $updateView = $this->conn->prepare(
            "UPDATE movies SET views = views + 1 WHERE id = ?"
        );
        $updateView->bind_param("i", $movie_id);
        return $updateView->execute();
    }
    
    /**
     * Ghi log hệ thống
     */
    public function addLog($action) {
        $log = $this->conn->prepare("INSERT INTO logs(action) VALUES (?)");
        $log->bind_param("s", $action);
        return $log->execute();
    }
    
    /**
     * Lấy tags của phim
     */
    public function getMovieTags($movie_id) {
        $tags = [];
        
        $tagStmt = $this->conn->prepare("
            SELECT t.name
            FROM movie_tags mt
            JOIN tags t ON mt.tag_id = t.id
            WHERE mt.movie_id = ?
        ");
        $tagStmt->bind_param("i", $movie_id);
        $tagStmt->execute();
        $tagResult = $tagStmt->get_result();
        
        while ($row = $tagResult->fetch_assoc()) {
            $tags[] = $row['name'];
        }
        
        return $tags;
    }
    
    /**
     * Lấy bình luận của phim
     */
    public function getComments($movie_id) {
        $commentStmt = $this->conn->prepare("
            SELECT c.*, a.username, a.avatar
            FROM comments c
            LEFT JOIN admin a ON c.admin_id = a.id
            WHERE c.movie_id = ? AND c.status = 1
            ORDER BY c.created_at DESC
        ");
        $commentStmt->bind_param("i", $movie_id);
        $commentStmt->execute();
        return $commentStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Thêm bình luận
     */
    public function addComment($movie_id, $admin_id, $content) {
        $insert = $this->conn->prepare("
            INSERT INTO comments (movie_id, admin_id, content)
            VALUES (?, ?, ?)
        ");
        $insert->bind_param("iis", $movie_id, $admin_id, $content);
        return $insert->execute();
    }
    
    /**
     * Lấy danh sách tập phim (nếu là phim bộ)
     */
    public function getEpisodes($movie_id) {
        $epStmt = $this->conn->prepare(
            "SELECT episode_number, video_url
             FROM episodes
             WHERE movie_id = ?
             ORDER BY episode_number ASC"
        );
        $epStmt->bind_param("i", $movie_id);
        $epStmt->execute();
        return $epStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Thêm hoặc cập nhật đánh giá
     */
    public function addOrUpdateRating($movie_id, $admin_id, $rating) {
        $stmt = $this->conn->prepare("
            INSERT INTO ratings (movie_id, admin_id, rating)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE rating = VALUES(rating)
        ");
        
        $stmt->bind_param("iii", $movie_id, $admin_id, $rating);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Lấy thống kê đánh giá phim
     */
    public function getRatingStats($movie_id) {
        $avg_rating = 0;
        $total_rating = 0;
        
        $stmt = $this->conn->prepare("
            SELECT 
                ROUND(AVG(rating),1) AS avg_rating,
                COUNT(*) AS total_rating
            FROM ratings
            WHERE movie_id = ?
        ");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $stmt->bind_result($avg_rating, $total_rating);
        $stmt->fetch();
        $stmt->close();
        
        return [
            'avg_rating' => $avg_rating,
            'total_rating' => $total_rating
        ];
    }
}
