<?php
//Viet's Code
require_once __DIR__ . "/../models/SearchModel.php";

class SearchController {
    private $searchModel;
    
    public function __construct($connection) {
        $this->searchModel = new SearchModel($connection);
    }
    
    /**
     * Xử lý tìm kiếm
     */
    public function search() {
        // Lấy từ khóa tìm kiếm
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        // Tìm kiếm phim
        $movies = $this->searchModel->searchMovies($keyword);
        
        // Đếm số lượng kết quả
        $count = $this->searchModel->countSearchResults($keyword);
        
        return [
            'keyword' => $keyword,
            'movies' => $movies,
            'count' => $count
        ];
    }
}
