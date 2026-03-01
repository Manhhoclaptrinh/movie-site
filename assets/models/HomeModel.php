<?php

class HomeModel {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Lấy thông tin banner
     */
    public function getBanner() {
        $result = $this->conn->query("
            SELECT 
                m.title,
                m.description,
                m.slug AS movie_slug,
                b.image
            FROM banners b
            JOIN movies m ON b.movie_id = m.id
            WHERE b.status = 1
            LIMIT 1
        ");
        
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Lấy danh sách phim theo quốc gia
     */
    public function getMoviesByCountry($countryName, $limit = 10) {
        $stmt = $this->conn->prepare("
            SELECT 
                m.title,
                m.slug AS movie_slug,
                m.poster,
                m.views
            FROM movies m
            JOIN categories c ON m.category_id = c.id
            WHERE c.name = ?
            ORDER BY m.created_at DESC
            LIMIT ?
        ");
        
        $stmt->bind_param("si", $countryName, $limit);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * Lấy phim Hàn Quốc
     */
    public function getKoreanMovies($limit = 10) {
        return $this->getMoviesByCountry('Hàn Quốc', $limit);
    }
    
    /**
     * Lấy phim Trung Quốc
     */
    public function getChinaMovies($limit = 6) {
        return $this->getMoviesByCountry('Trung Quốc', $limit);
    }
    
    /**
     * Lấy phim US-UK
     */
    public function getUSMovies($limit = 6) {
        return $this->getMoviesByCountry('US-UK', $limit);
    }
}
