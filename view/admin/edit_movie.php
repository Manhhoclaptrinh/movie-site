<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/EditMovieController.php";

// Khởi tạo controller
$controller = new EditMovieController($conn);

// Lấy dữ liệu từ controller
$data = $controller->index();
$movie = $data['movie'];
$categories = $data['categories'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa phim - Admin Panel</title>
    <link rel="stylesheet" href="/movie-site/assets/css/admin-style.css">
    <link rel="stylesheet" href="/movie-site/assets/css/add-movie.css">
</head>
<body>
    <div class="admin-container">
        <div class="header">
            <h1>✏️ Sửa phim: <?php echo htmlspecialchars($movie['title']); ?></h1>
            <div class="header-actions">
                <a href="manage_movies.php" class="btn btn-secondary">← Quay lại</a>
                <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
            </div>
        </div>

        <div class="form-container">
            <form method="post" enctype="multipart/form-data" id="editMovieForm">
                <div class="form-grid">
                    <!-- Left Column -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="title">Tên phim *</label>
                            <input type="text" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($movie['title']); ?>" 
                                   placeholder="Nhập tên phim..." required>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả phim</label>
                            <textarea id="description" name="description" rows="6" 
                                      placeholder="Nhập mô tả chi tiết về phim..."><?php echo htmlspecialchars($movie['description']); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="category_id">Thể loại *</label>
                                <select id="category_id" name="category_id" required>
                                    <option value="">-- Chọn thể loại --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" 
                                                <?php echo $cat['id'] == $movie['category_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_series">Loại phim *</label>
                                <select id="is_series" name="is_series" required>
                                    <option value="0" <?php echo $movie['is_series'] == 0 ? 'selected' : ''; ?>>Phim lẻ</option>
                                    <option value="1" <?php echo $movie['is_series'] == 1 ? 'selected' : ''; ?>>Phim bộ</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="release_year">Năm phát hành</label>
                                <input type="number" id="release_year" name="release_year" 
                                       value="<?php echo htmlspecialchars($movie['release_year']); ?>"
                                       placeholder="VD: 2024" min="1900" max="2099">
                            </div>

                            <div class="form-group">
                                <label for="country">Quốc gia</label>
                                <input type="text" id="country" name="country" 
                                       value="<?php echo htmlspecialchars($movie['country']); ?>"
                                       placeholder="VD: Việt Nam, Hàn Quốc...">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="poster">Poster phim</label>
                            
                            <!-- Hiển thị poster hiện tại -->
                            <?php if (!empty($movie['poster'])): ?>
                                <div style="margin-bottom: 15px;">
                                    <p style="font-weight: bold; color: #666;">Poster hiện tại:</p>
                                    <img src="/movie-site/uploads/posters/<?php echo htmlspecialchars($movie['poster']); ?>" 
                                         alt="Current Poster" 
                                         style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                </div>
                            <?php endif; ?>
                            
                            <div class="upload-area" id="uploadArea">
                                <input type="file" id="poster" name="poster" accept="image/*" onchange="previewImage(this)">
                                <div class="upload-placeholder">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none">
                                        <path d="M21 19V5C21 3.9 20.1 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19ZM8.5 13.5L11 16.51L14.5 12L19 18H5L8.5 13.5Z" fill="currentColor"/>
                                    </svg>
                                    <p>Click để chọn ảnh mới hoặc kéo thả vào đây</p>
                                    <small>PNG, JPG, WEBP (Tối đa 5MB)</small>
                                </div>
                                <img id="preview" class="preview-image" style="display: none;">
                            </div>
                        </div>

                        <div class="info-box">
                            <h4>💡 Lưu ý khi sửa phim:</h4>
                            <ul>
                                <li>Tên phim và thể loại là bắt buộc</li>
                                <li>Để trống poster nếu không muốn thay đổi</li>
                                <li>Upload poster mới sẽ xóa poster cũ</li>
                                <li>Mô tả chi tiết giúp SEO tốt hơn</li>
                                <li><strong>Tên file tự động xóa số ở đầu</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update" class="btn btn-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z" fill="currentColor"/>
                        </svg>
                        <span>Cập nhật phim</span>
                    </button>
                    <a href="manage_movies.php" class="btn btn-secondary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" fill="currentColor"/>
                        </svg>
                        <span>Hủy bỏ</span>
                    </a>
                </div>  
            </form>
        </div>
    </div>

    <script src="/movie-site/assets/js/add-movie.js"></script>
</body>
</html>
