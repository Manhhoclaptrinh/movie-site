<?php

require_once __DIR__ . "/../models/MovieFilterModel.php";

class MovieFilterController {
    private $movieFilterModel;
    
    public function __construct($connection) {
        $this->movieFilterModel = new MovieFilterModel($connection);
    }
    
    /**
     * Lấy danh sách phim với bộ lọc
     */
    public function index() {
        // Lấy các tham số lọc từ URL
        $filters = [];
        
        if (isset($_GET['type'])) {
            $filters['type'] = $_GET['type'];
        }
        
        if (!empty($_GET['category'])) {
            $filters['category'] = $_GET['category'];
        }
        
        if (!empty($_GET['country'])) {
            $filters['country'] = $_GET['country'];
        }
        
        // Lấy danh sách phim
        $movies = $this->movieFilterModel->getFilteredMovies($filters);
        $count = $movies->num_rows;
        
        return [
            'movies' => $movies,
            'count' => $count
        ];
    }
}
