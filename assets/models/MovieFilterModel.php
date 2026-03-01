<?php

class MovieFilterModel {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Lọc phim theo điều kiện
     */
    public function getFilteredMovies($filters = []) {
        $where = [];
        $params = [];
        $types = "";
        
        // Lọc phim lẻ / phim bộ
        if (isset($filters['type'])) {
            if ($filters['type'] === 'le') {
                $where[] = "m.is_series = 0";
            }
            if ($filters['type'] === 'bo') {
                $where[] = "m.is_series = 1";
            }
        }
        
        // Lọc thể loại
        if (!empty($filters['category'])) {
            $where[] = "c.slug = ?";
            $params[] = $filters['category'];
            $types .= "s";
        }
        
        // Lọc quốc gia
        if (!empty($filters['country'])) {
            $where[] = "m.country = ?";
            $params[] = $filters['country'];
            $types .= "s";
        }
        
        // Xây dựng câu truy vấn
        $sql = "
            SELECT m.*, c.name AS category_name
            FROM movies m
            LEFT JOIN categories c ON m.category_id = c.id
        ";
        
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY m.created_at DESC";
        
        // Thực thi truy vấn
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        
        return $stmt->get_result();
    }
}
