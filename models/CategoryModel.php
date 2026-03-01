<?php
class CategoryModel {
    private $conn;
    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    
    /**
     * Thêm thể loại mới
     */
    public function addCategory($name, $slug, $description) {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $slug, $description);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Cập nhật thể loại
     */
    public function updateCategory($id, $name, $slug, $description) {
        $stmt = $this->conn->prepare("UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $slug, $description, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Xóa thể loại
     */
    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Lấy danh sách tất cả thể loại với số lượng phim
     */
    public function getAllCategoriesWithMovieCount() {
        $result = $this->conn->query("
            SELECT c.*, COUNT(m.id) as movie_count 
            FROM categories c 
            LEFT JOIN movies m ON c.id = m.category_id 
            GROUP BY c.id 
            ORDER BY c.name ASC
        ");
        return $result;
    }
    
    /**
     * Lấy thông tin một thể loại theo ID
     */
    public function getCategoryById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();
        return $category;
    }
    
    /**
     * Đếm số lượng phim sử dụng thể loại
     */
    public function countMoviesByCategory($categoryId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM movies WHERE category_id = ?");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    }
}
?>
