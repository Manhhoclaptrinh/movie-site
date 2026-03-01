<?php
class TagModel {
    private $conn;
    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    
    /**
     * Kiểm tra xem bảng tags đã tồn tại chưa
     */
    public function checkTagsTableExists() {
        $result = $this->conn->query("SHOW TABLES LIKE 'tags'");
        return $result->num_rows > 0;
    }
    
    /**
     * Thêm tag mới
     */
    public function addTag($name, $slug) {
        $stmt = $this->conn->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $slug);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Cập nhật tag
     */
    public function updateTag($id, $name, $slug) {
        $stmt = $this->conn->prepare("UPDATE tags SET name = ?, slug = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $slug, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Xóa tag
     */
    public function deleteTag($id) {
        $stmt = $this->conn->prepare("DELETE FROM tags WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Lấy danh sách tất cả tags với số lượng phim
     */
    public function getAllTagsWithMovieCount() {
        return $this->conn->query("
            SELECT t.*, COUNT(mt.movie_id) as movie_count 
            FROM tags t 
            LEFT JOIN movie_tags mt ON t.id = mt.tag_id 
            GROUP BY t.id 
            ORDER BY t.name ASC
        ");
    }
    
    /**
     * Lấy thông tin một tag theo ID
     */
    public function getTagById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tags WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tag = $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        return $tag;
    }
    
    /**
     * Đếm số lượng phim sử dụng tag
     */
    public function countMoviesByTag($tagId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM movie_tags WHERE tag_id = ?");
        $stmt->bind_param("i", $tagId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    }
}
?>
