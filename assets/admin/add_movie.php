<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/AddMovieController.php";

// Khởi tạo controller
$controller = new AddMovieController($conn);

// Lấy dữ liệu từ controller
$data = $controller->index();
$categories = $data['categories'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm phim mới - Admin Panel</title>
    <link rel="stylesheet" href="/movie-site/assets/css/admin-style.css">
    <link rel="stylesheet" href="/movie-site/assets/css/add-movie.css">
</head>
<body>
    <div class="admin-container">
        <div class="header">
            <h1>➕ Thêm phim mới</h1>
            <div class="header-actions">
                <a href="../movies.php" class="btn btn-secondary">← Quay lại</a>
                <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
            </div>
        </div>

        <div class="form-container">
            <form method="post" enctype="multipart/form-data" id="addMovieForm">
                <div class="form-grid">
                    <!-- Left Column -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="title">Tên phim *</label>
                            <input type="text" id="title" name="title" placeholder="Nhập tên phim..." required>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả phim</label>
                            <textarea id="description" name="description" rows="6" placeholder="Nhập mô tả chi tiết về phim..."></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="category_id">Thể loại *</label>
                                <select id="category_id" name="category_id" required>
                                    <option value="">-- Chọn thể loại --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_series">Loại phim *</label>
                                <select id="is_series" name="is_series" required>
                                    <option value="0">Phim lẻ</option>
                                    <option value="1">Phim bộ</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="release_year">Năm phát hành</label>
                                <input type="number" id="release_year" name="release_year" placeholder="VD: 2024" min="1900" max="2099">
                            </div>

                            <div class="form-group">
                                <label for="country">Quốc gia</label>
                                <input type="text" id="country" name="country" placeholder="VD: Việt Nam, Hàn Quốc...">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="poster">Poster phim</label>
                            <div class="upload-area" id="uploadArea">
                                <input type="file" id="poster" name="poster" accept="image/*" onchange="previewImage(this)">
                                <div class="upload-placeholder">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none">
                                        <path d="M21 19V5C21 3.9 20.1 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19ZM8.5 13.5L11 16.51L14.5 12L19 18H5L8.5 13.5Z" fill="currentColor"/>
                                    </svg>
                                    <p>Click để chọn ảnh hoặc kéo thả vào đây</p>
                                    <small>PNG, JPG, WEBP (Tối đa 5MB)</small>
                                </div>
                                <img id="preview" class="preview-image" style="display: none;">
                            </div>
                        </div>

                        <div class="info-box">
                            <h4>💡 Lưu ý khi thêm phim:</h4>
                            <ul>
                                <li>Tên phim và thể loại là bắt buộc</li>
                                <li>Nên upload poster chất lượng cao</li>
                                <li>Mô tả chi tiết giúp SEO tốt hơn</li>
                                <li>Sau khi thêm, có thể thêm tập phim</li>
                                <li><strong>Tên file tự động xóa số ở đầu</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z" fill="currentColor"/>
                        </svg>
                        <span>Thêm phim</span>
                    </button>
                    <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5V1L7 6L12 11V7C15.31 7 18 9.69 18 13C18 16.31 15.31 19 12 19C8.69 19 6 16.31 6 13H4C4 17.42 7.58 21 12 21C16.42 21 20 17.42 20 13C20 8.58 16.42 5 12 5Z" fill="currentColor"/>
                        </svg>
                        <span>Làm mới</span>
                    </button>
                </div>  
            </form>
        </div>
    </div>

    <script src="/movie-site/assets/js/add-movie.js"></script>
</body>
</html>
