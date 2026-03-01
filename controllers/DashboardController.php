<?php
require_once __DIR__ . "/../models/DashboardModel.php";

class DashboardController {
    private $model;
    
    public function __construct($db_connection) {
        $this->model = new DashboardModel($db_connection);
    }
    
    //Kiểm tra đăng nhập admin
    public function checkAdminLogin() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: login.php');
            exit;
        }
    }
    
    //Lấy dữ liệu dashboard
    public function getDashboardData() {
        $data = [];
        
        // Thống kê
        $data['statistics'] = $this->model->getAllStatistics();
        
        // Phim mới nhất
        $data['recent_movies'] = $this->model->getRecentMovies(5);
        
        // Phim xem nhiều nhất
        $data['top_movies'] = $this->model->getTopMovies(5);
        
        // Thông tin admin
        $data['admin_username'] = $_SESSION['admin_username'] ?? 'Admin';
        
        return $data;
    }
    
    //Hiển thị trang dashboard
    public function index() {
        // Kiểm tra đăng nhập
        $this->checkAdminLogin();
        
        // Lấy dữ liệu
        $data = $this->getDashboardData();
        
        // Trả về dữ liệu cho view
        return $data;
    }
}
