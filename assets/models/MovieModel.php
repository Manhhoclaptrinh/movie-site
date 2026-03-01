<?php
class MovieModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    /**
     * Lấy tất cả categories
     */
    public function getAllCategories() {
        $result = $this->conn->query("SELECT * FROM categories ORDER BY name ASC");
        $categories = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        return $categories;
    }
    
    /**
     * Lấy thông tin phim theo ID
     */
    public function getMovieById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Xóa phim theo ID
     */
    public function deleteMovie($id) {
        $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
        
    /**
     * Lấy danh sách tất cả phim kèm thông tin thể loại
     */
    public function getAllMoviesWithCategory() {
        return $this->conn->query("
            SELECT m.*, c.name AS category_name
            FROM movies m
            LEFT JOIN categories c ON m.category_id = c.id
            ORDER BY m.created_at DESC
        ");
    }
    
    /**
     * Thêm phim mới với slug
     */
    public function addMovieWithSlug($title, $slug, $description, $posterPath, $releaseYear, $country, $categoryId, $isSeries) {
        $stmt = $this->conn->prepare("
            INSERT INTO movies 
            (title, slug, description, poster, release_year, country, category_id, is_series)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssissi",
            $title,
            $slug,
            $description,
            $posterPath,
            $releaseYear,
            $country,
            $categoryId,
            $isSeries
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Cập nhật phim có poster mới với slug
     */
    public function updateMovieWithPoster($id, $title, $slug, $description, $posterPath, $releaseYear, $country, $categoryId, $isSeries) {
        $stmt = $this->conn->prepare("
            UPDATE movies SET
            title=?, slug=?, description=?, poster=?, release_year=?, country=?, category_id=?, is_series=?
            WHERE id=?
        ");
        $stmt->bind_param(
            "ssssissii",
            $title,
            $slug,
            $description,
            $posterPath,
            $releaseYear,
            $country,
            $categoryId,
            $isSeries,
            $id
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Cập nhật phim không đổi poster với slug
     */
    public function updateMovieWithSlugNoPoster($id, $title, $slug, $description, $releaseYear, $country, $categoryId, $isSeries) {
        $stmt = $this->conn->prepare("
            UPDATE movies SET
            title=?, slug=?, description=?, release_year=?, country=?, category_id=?, is_series=?
            WHERE id=?
        ");
        $stmt->bind_param(
            "sssissii",
            $title,
            $slug,
            $description,
            $releaseYear,
            $country,
            $categoryId,
            $isSeries,
            $id
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Lấy tất cả phim
     */
    public function getAllMovies() {
        $result = $this->conn->query("
            SELECT m.*, c.name AS category_name
            FROM movies m
            LEFT JOIN categories c ON m.category_id = c.id
            ORDER BY m.created_at DESC
        ");
        
        return $result;
    }

    /**
     * Lấy phim xem nhiều nhất
     */
    public function getMostViewedMovies($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT m.*, c.name AS category_name
            FROM movies m
            LEFT JOIN categories c ON m.category_id = c.id
            ORDER BY m.views DESC
            LIMIT ?
        ");
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * Lấy phim mới nhất
     */
    public function getNewestMovies($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT m.*, c.name AS category_name
            FROM movies m
            LEFT JOIN categories c ON m.category_id = c.id
            ORDER BY m.created_at DESC
            LIMIT ?
        ");
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        
        return $stmt->get_result();
    }
}
?>
