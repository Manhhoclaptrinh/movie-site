<?php
class CommentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả comment
    public function getAllComments() {
        $sql = "
            SELECT c.*, m.title AS movie_title, a.username
            FROM comments c
            JOIN movies m ON c.movie_id = m.id
            LEFT JOIN admin a ON c.admin_id = a.id
            ORDER BY c.created_at DESC
        ";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Xóa comment
    public function deleteComment($id) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
