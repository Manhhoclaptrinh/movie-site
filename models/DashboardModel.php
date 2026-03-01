<?php
class DashboardModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    //Lấy tổng số phim
    public function getTotalMovies() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM movies");
        return $result->fetch_assoc()['total'];
    }
    
    //Lấy tổng số thể loại
    public function getTotalCategories() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM categories");
        return $result->fetch_assoc()['total'];
    }
    
    //Lấy tổng lượt xem
    public function getTotalViews() {
        $result = $this->conn->query("SELECT SUM(views) as total FROM movies");
        $data = $result->fetch_assoc();
        return $data['total'] ?? 0;
    }
    
    //Lấy tổng số tập phim
    public function getTotalEpisodes() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM episodes");
        return $result->fetch_assoc()['total'];
    }
    
    //Lấy danh sách phim mới nhất
    public function getRecentMovies($limit = 5) {
        $limit = intval($limit);
        $result = $this->conn->query("SELECT * FROM movies ORDER BY created_at DESC LIMIT $limit");
        
        $movies = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $movies[] = $row;
            }
        }
        return $movies;
    }
    
    //Lấy danh sách phim xem nhiều nhất
    public function getTopMovies($limit = 5) {
        $limit = intval($limit);
        $result = $this->conn->query("SELECT * FROM movies ORDER BY views DESC LIMIT $limit");
        
        $movies = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $movies[] = $row;
            }
        }
        return $movies;
    }
    
    //Lấy tất cả thống kê
    public function getAllStatistics() {
        return [
            'total_movies' => $this->getTotalMovies(),
            'total_categories' => $this->getTotalCategories(),
            'total_views' => $this->getTotalViews(),
            'total_episodes' => $this->getTotalEpisodes()
        ];
    }
}
