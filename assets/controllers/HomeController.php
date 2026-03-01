<?php
require_once __DIR__ . "/../models/HomeModel.php";

class HomeController {
    private $homeModel;
    
    public function __construct($connection) {
        $this->homeModel = new HomeModel($connection);
    }
    
    /**
     * Lấy tất cả dữ liệu cho trang chủ
     */
    public function index() {
        $data = [];
        
        // Lấy banner
        $data['banner'] = $this->homeModel->getBanner();
        
        // Lấy danh sách phim theo quốc gia
        $data['koreanMovies'] = $this->homeModel->getKoreanMovies(10);
        $data['chinaMovies'] = $this->homeModel->getChinaMovies(6);
        $data['usMovies'] = $this->homeModel->getUSMovies(6);
        
        return $data;
    }
}
