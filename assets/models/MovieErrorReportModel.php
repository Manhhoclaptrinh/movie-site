<?php
class MovieErrorReportModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($movie_id, $content) {
        $sql = "INSERT INTO movie_error_reports (movie_id, content, status)
                VALUES (?, ?, 0)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("is", $movie_id, $content);
        return $stmt->execute();
    }

    public function getAll() {
        $sql = "SELECT r.*, m.title
                FROM movie_error_reports r
                JOIN movies m ON r.movie_id = m.id
                ORDER BY r.created_at DESC";

        return $this->conn->query($sql);
    }
}
