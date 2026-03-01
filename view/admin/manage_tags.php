<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/TagController.php";

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Khởi tạo controller
$controller = new TagController($conn);

$success_msg = '';
$error_msg = '';

// Kiểm tra bảng tags có tồn tại không
$tags_table_exists = $controller->checkTableExists();

if (!$tags_table_exists) {
    $error_msg = "Bảng tags chưa được tạo. Vui lòng chạy file update_database.sql!";
}

// Xử lý thêm tag
if (isset($_POST['add_tag'])) {
    $result = $controller->handleAddTag($_POST['name'], $_POST['slug']);
    
    if ($result['success']) {
        $success_msg = $result['message'];
    } else {
        $error_msg = $result['message'];
    }
}

// Xử lý cập nhật tag
if (isset($_POST['update_tag'])) {
    $result = $controller->handleUpdateTag($_POST['id'], $_POST['name'], $_POST['slug']);
    
    if ($result['success']) {
        $success_msg = $result['message'];
    } else {
        $error_msg = $result['message'];
    }
}

// Xử lý xóa tag
if (isset($_GET['delete'])) {
    $result = $controller->handleDeleteTag($_GET['delete']);
    
    if ($result['success']) {
        $success_msg = $result['message'];
    } else {
        $error_msg = $result['message'];
    }
}

// Lấy danh sách tags
$tags = $controller->getAllTags();

// Lấy thông tin tag cần sửa
$edit_tag = null;
if (isset($_GET['edit'])) {
    $edit_tag = $controller->getTagForEdit($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tags - Admin</title>
    <link rel="stylesheet" href="/movie-site/assets/css/manage_tags.css">
</head>
<body>
    <div class="admin-container">
        <div class="header">
            <h1>🏷️ Quản lý Tags</h1>
            <a href="dashboard.php" class="btn btn-secondary">← Dashboard</a>
        </div>
        
        <?php if ($success_msg): ?>
            <div class="alert alert-success">✓ <?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div class="alert alert-error">✗ <?php echo $error_msg; ?></div>
        <?php endif; ?>
        
        <?php if (!$tags_table_exists): ?>
            <div class="alert alert-warning">
                ⚠️ Bảng tags chưa được tạo. Vui lòng chạy file <code>update_database.sql</code> để tạo cấu trúc database mới!
            </div>
        <?php endif; ?>
        
        <?php if ($tags_table_exists): ?>
        <div class="form-container">
            <h2 style="margin-bottom: 25px; font-size: 24px;">
                <?php echo $edit_tag ? '✏️ Sửa tag' : '➕ Thêm tag mới'; ?>
            </h2>
            
            <form method="POST" action="">
                <?php if ($edit_tag): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_tag['id']; ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Tên tag *</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo $edit_tag ? htmlspecialchars($edit_tag['name']) : ''; ?>" 
                               placeholder="Ví dụ: Hot, Trending, Vietsub..."
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="slug">Slug (URL thân thiện) *</label>
                        <input type="text" id="slug" name="slug" 
                               value="<?php echo $edit_tag ? htmlspecialchars($edit_tag['slug']) : ''; ?>" 
                               placeholder="vi-du: hot, trending, vietsub" 
                               required>
                        <small>Chỉ dùng chữ thường, số và dấu gạch ngang</small>
                    </div>
                </div>
                
                <div class="actions">
                    <button type="submit" name="<?php echo $edit_tag ? 'update_tag' : 'add_tag'; ?>" 
                            class="btn">
                        <?php echo $edit_tag ? '✓ Cập nhật' : '+ Thêm mới'; ?>
                    </button>
                    <?php if ($edit_tag): ?>
                        <a href="manage_tags.php" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <h2 style="margin-bottom: 20px; font-size: 24px;">📋 Danh sách Tags</h2>
        
        <div class="table-container">
            <?php if ($tags && $tags->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Tag</th>
                            <th>Slug</th>
                            <th style="width: 120px;">Số phim</th>
                            <th style="width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($tag = $tags->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $tag['id']; ?></td>
                                <td>
                                    <span class="tag-badge"><?php echo htmlspecialchars($tag['name']); ?></span>
                                </td>
                                <td><code><?php echo htmlspecialchars($tag['slug']); ?></code></td>
                                <td>
                                    <span class="count-badge"><?php echo $tag['movie_count']; ?> phim</span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="?edit=<?php echo $tag['id']; ?>" 
                                           class="btn btn-small btn-edit">✏️ Sửa</a>
                                        <a href="?delete=<?php echo $tag['id']; ?>" 
                                           class="btn btn-small btn-delete"
                                           onclick="return confirm('Bạn có chắc muốn xóa tag này?')">
                                            🗑️ Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>📭 <?php echo $tags_table_exists ? 'Chưa có tag nào. Hãy thêm tag mới!' : 'Vui lòng chạy update_database.sql để tạo bảng tags!'; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto generate slug from name
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('input', function() {
                if (!document.querySelector('input[name="id"]')) {
                    const slug = this.value
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/đ/g, 'd')
                        .replace(/Đ/g, 'd')
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim();
                    document.getElementById('slug').value = slug;
                }
            });
        }
    </script>
</body>
</html>
