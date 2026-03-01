<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';

switch ($type) {
    case 'movies_by_month':
        // Thống kê số phim theo tháng (6 tháng gần nhất)
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total
                  FROM movies 
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month ASC";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'views_by_movie':
        // Top 10 phim có lượt xem cao nhất
        $query = "SELECT title, views 
                  FROM movies 
                  ORDER BY views DESC 
                  LIMIT 10";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'movies_by_category':
        // Số lượng phim theo thể loại
        $query = "SELECT c.name, COUNT(mc.movie_id) as total
                  FROM categories c
                  LEFT JOIN movie_categories mc ON c.id = mc.category_id
                  GROUP BY c.id, c.name
                  ORDER BY total DESC";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'episodes_by_movie':
        // Top 10 phim có nhiều tập nhất
        $query = "SELECT m.title, COUNT(e.id) as total_episodes
                  FROM movies m
                  LEFT JOIN episodes e ON m.id = e.movie_id
                  WHERE m.is_series = 1
                  GROUP BY m.id, m.title
                  HAVING total_episodes > 0
                  ORDER BY total_episodes DESC
                  LIMIT 10";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'views_trend':
        // Xu hướng lượt xem theo tháng
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    SUM(views) as total_views
                  FROM movies 
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month ASC";
        $result = $conn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid type']);
        break;
}
