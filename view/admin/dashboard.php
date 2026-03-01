<?php
session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/DashboardController.php";

// Khởi tạo controller
$controller = new DashboardController($conn);

// Lấy dữ liệu từ controller
$data = $controller->index();

// Extract dữ liệu để sử dụng trong view
$statistics = $data['statistics'];
$recent_movies = $data['recent_movies'];
$top_movies = $data['top_movies'];
$admin_username = $data['admin_username'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="/movie-site/assets/css/admin-style.css">
    <link rel="stylesheet" href="/movie-site/assets/css/dashboard.css">
    <link rel="stylesheet" href="/movie-site/assets/css/dashboard-charts.css">
</head>

<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                        <path d="M19 4H5C3.9 4 3 4.9 3 6V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V6C21 4.9 20.1 4 19 4ZM19 18H5V8H19V18Z" fill="currentColor" />
                        <path d="M10 9L15 12L10 15V9Z" fill="currentColor" />
                    </svg>
                    <span>Movie Admin</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M3 13H11V3H3V13ZM3 21H11V15H3V21ZM13 21H21V11H13V21ZM13 3V9H21V3H13Z" fill="currentColor" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="manage_movies.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M18 4L20 8H17L15 4H13L15 8H12L10 4H8L10 8H7L5 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V4H18Z" fill="currentColor" />
                    </svg>
                    <span>Quản lý phim</span>
                </a>

                <a href="manage_categories.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2L2 7V17L12 22L22 17V7L12 2Z" fill="currentColor" />
                    </svg>
                    <span>Thể loại</span>
                </a>

                <a href="manage_tags.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M21.41 11.58L12.41 2.58C12.05 2.22 11.55 2 11 2H4C2.9 2 2 2.9 2 4V11C2 11.55 2.22 12.05 2.59 12.42L11.59 21.42C11.95 21.78 12.45 22 13 22C13.55 22 14.05 21.78 14.41 21.41L21.41 14.41C21.78 14.05 22 13.55 22 13C22 12.45 21.77 11.94 21.41 11.58ZM5.5 7C4.67 7 4 6.33 4 5.5C4 4.67 4.67 4 5.5 4C6.33 4 7 4.67 7 5.5C7 6.33 6.33 7 5.5 7Z" fill="currentColor" />
                    </svg>
                    <span>Tags</span>
                </a>

                <a href="manage_users.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="currentColor" />
                    </svg>
                    <span>Quản lý tài khoản</span>
                </a>

                <a href="login_history.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12C2 17.52 6.47 22 11.99 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 11.99 2ZM12 20C7.58 20 4 16.42 4 12C4 7.58 7.58 4 12 4C16.42 4 20 7.58 20 12C20 16.42 16.42 20 12 20ZM12.5 7H11V13L16.25 16.15L17 14.92L12.5 12.25V7Z" fill="currentColor" />
                    </svg>
                    <span>Quản lý đăng nhập</span>
                </a>
                <a href="manage_movie_reports.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M20 8H17.19C16.74 7.22 16.12 6.55 15.37 6.02L17 4.39L15.59 3L14 4.59C13.35 4.22 12.59 4 11.8 4C11.01 4 10.25 4.22 9.6 4.59L8 3L6.59 4.41L8.23 6.05C7.48 6.58 6.86 7.25 6.41 8H4V10H5.58C5.53 10.33 5.5 10.66 5.5 11V13H4V15H5.5V16C5.5 17.1 6.4 18 7.5 18H16.5C17.6 18 18.5 17.1 18.5 16V15H20V13H18.5V11C18.5 10.66 18.47 10.33 18.42 10H20V8Z" fill="currentColor" />
                    </svg>
                    <span>Quản lý báo lỗi phim</span>
                </a>

                <a href="manage_comments.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M21 11.5C21 6.81 16.97 3 12 3C7.03 3 3 6.81 3 11.5C3 16.19 7.03 20 12 20C13.38 20 14.7 19.71 15.9 19.18L21 21L19.47 16.28C20.46 15.05 21 13.32 21 11.5Z"
                            fill="currentColor" />
                    </svg>
                    <span>Quản lý bình luận</span>
                </a>

                <div class="nav-divider"></div>

                <a href="http://localhost/movie-site/index.php" class="nav-item" target="_blank">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M19 19H5V5H12V3H5C3.89 3 3 3.9 3 5V19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V12H19V19ZM14 3V5H17.59L7.76 14.83L9.17 16.24L19 6.41V10H21V3H14Z" fill="currentColor" />
                    </svg>
                    <span>Xem website</span>
                </a>

                <a href="logout.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.59L17 17L22 12L17 7ZM4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z" fill="currentColor" />
                    </svg>
                    <span>Đăng xuất</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="topbar">
                <div class="topbar-left">
                    <h1>Dashboard</h1>
                    <p>Chào mừng trở lại, <strong><?php echo htmlspecialchars($admin_username); ?></strong>!</p>
                </div>
                <div class="topbar-right">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($admin_username, 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-wrapper">
                <div class="stats-grid">
                    <div class="stat-card stat-primary">
                        <div class="stat-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <path d="M18 4L20 8H17L15 4H13L15 8H12L10 4H8L10 8H7L5 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V4H18Z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>Tổng phim</h3>
                            <div class="stat-value"><?php echo number_format($statistics['total_movies']); ?></div>
                            <p class="stat-label">Phim trong hệ thống</p>
                        </div>
                    </div>

                    <div class="stat-card stat-success">
                        <div class="stat-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>Lượt xem</h3>
                            <div class="stat-value"><?php echo number_format($statistics['total_views']); ?></div>
                            <p class="stat-label">Tổng lượt xem</p>
                        </div>
                    </div>

                    <div class="stat-card stat-warning">
                        <div class="stat-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L2 7V17L12 22L22 17V7L12 2Z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>Thể loại</h3>
                            <div class="stat-value"><?php echo number_format($statistics['total_categories']); ?></div>
                            <p class="stat-label">Danh mục phim</p>
                        </div>
                    </div>

                    <div class="stat-card stat-info">
                        <div class="stat-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <path d="M4 6H2V20C2 21.1 2.9 22 4 22H18V20H4V6ZM20 2H8C6.9 2 6 2.9 6 4V16C6 17.1 6.9 18 8 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H8V4H20V16ZM10 9H18V11H10V9ZM10 12H14V14H10V12ZM10 6H18V8H10V6Z" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="stat-info">
                            <h3>Tập phim</h3>
                            <div class="stat-value"><?php echo number_format($statistics['total_episodes']); ?></div>
                            <p class="stat-label">Tổng tập phim</p>
                        </div>
                    </div>
                </div>

                <div class="content-grid">
                    <div class="content-box">
                        <div class="box-header">
                            <h2>📽️ Phim mới nhất</h2>
                            <a href="http://localhost/movie-site/list_movies.php" class="btn btn-small">Xem tất cả</a>
                        </div>
                        <div class="movie-list">
                            <?php if (count($recent_movies) > 0): ?>
                                <?php foreach ($recent_movies as $movie): ?>
                                    <div class="movie-item">
                                        <img src="/movie-site/<?php echo htmlspecialchars($movie['poster']); ?>">
                                        <div class="movie-info">
                                            <h4><?php echo htmlspecialchars($movie['title']); ?></h4>
                                            <p><?php echo $movie['release_year']; ?> • <?php echo $movie['is_series'] ? 'Phim bộ' : 'Phim lẻ'; ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-data">Chưa có phim nào</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="content-box">
                        <div class="box-header">
                            <h2>🔥 Phim xem nhiều</h2>
                            <a href="http://localhost/movie-site/list_movies.php" class="btn btn-small">Xem tất cả</a>
                        </div>
                        <div class="movie-list">
                            <?php if (count($top_movies) > 0): ?>
                                <?php foreach ($top_movies as $movie): ?>
                                    <div class="movie-item">
                                        <img src="/movie-site/<?php echo htmlspecialchars($movie['poster']); ?>">
                                        <div class="movie-info">
                                            <h4><?php echo htmlspecialchars($movie['title']); ?></h4>
                                            <p><?php echo number_format($movie['views']); ?> lượt xem</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-data">Chưa có dữ liệu</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

    <!-- Dashboard Scripts -->
    <script src="/movie-site/assets/js/dashboard.js"></script>
    <script src="/movie-site/assets/js/dashboard-charts.js"></script>
</body>

</html>
