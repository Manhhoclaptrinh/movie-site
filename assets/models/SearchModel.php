<?php
//Viet's Code
class SearchModel {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Tìm kiếm phim theo từ khóa
     */
    public function searchMovies($keyword) {
        if ($keyword === '') {
            return null;
        }
        
        $stmt = $this->conn->prepare("
            SELECT 
                id, title, slug, poster, views
            FROM movies
            WHERE 
                title LIKE ? 
                OR original_title LIKE ?
                OR cast LIKE ?
                OR director LIKE ?
            ORDER BY views DESC
        ");
        
        $like = "%$keyword%";
        $stmt->bind_param("ssss", $like, $like, $like, $like);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * Đếm số lượng kết quả tìm kiếm
     */
    public function countSearchResults($keyword) {
        if ($keyword === '') {
            return 0;
        }
        
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM movies
            WHERE 
                title LIKE ? 
                OR original_title LIKE ?
                OR cast LIKE ?
                OR director LIKE ?
        ");
        
        $like = "%$keyword%";
        $stmt->bind_param("ssss", $like, $like, $like, $like);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return (int)$row['total'];
    }
}
