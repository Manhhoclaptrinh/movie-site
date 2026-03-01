  <?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/CategoryController.php";

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Khởi tạo controller
$controller = new CategoryController($conn);

$success_msg = '';
$error_msg = '';

// Xử lý thêm category
if (isset($_POST['add_category'])) {
    $result = $controller->handleAddCategory(
        $_POST['name'],
        $_POST['slug'],
        $_POST['description']
    );
    
    if ($result['success']) {
        $success_msg = $result['message'];
    } else {
        $error_msg = $result['message'];
    }
}

// Xử lý cập nhật category
if (isset($_POST['update_category'])) {
    $result = $controller->handleUpdateCategory(
        $_POST['id'],
        $_POST['name'],
        $_POST['slug'],
        $_POST['description']
    );
    
    if ($result['success']) {
        $success_msg = $result['message'];
    } else {
        $error_msg = $result['message'];
    }
}

// Xử lý xóa category
if (isset($_GET['delete'])) {
    $result = $controller->handleDeleteCategory($_GET['delete']);
    
    if ($result['success']) {
        $success_msg = $result['message'];
    } else {
        $error_msg = $result['message'];
    }
}

// Lấy danh sách categories
$categories = $controller->getAllCategories();

// Lấy thông tin category cần sửa
$edit_category = null;
if (isset($_GET['edit'])) {
    $edit_category = $controller->getCategoryForEdit($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thể loại - Admin</title>
    <link rel="stylesheet" href="/movie-site/assets/css/admin_manage_categories.css">
    
</head>
<body>
    <div class="admin-container">
        <div class="header glass header-flex">
        <h1>📁 Quản lý Thể loại</h1>

        <a href="dashboard.php" class="btn btn-dashboard">
            ← Dashboard
        </a>
    </div>
        <?php if ($success_msg): ?>
            <div class="alert alert-success">✓ <?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div class="alert alert-error">✗ <?php echo $error_msg; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <h2 style="margin-bottom: 25px; font-size: 24px;">
                <?php echo $edit_category ? '✏️ Sửa thể loại' : '➕ Thêm thể loại mới'; ?>
            </h2>
            
            <form method="POST" action="">
                <?php if ($edit_category): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_category['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Tên thể loại *</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo $edit_category ? htmlspecialchars($edit_category['name']) : ''; ?>" 
                           placeholder="Ví dụ: Hành động, Tình cảm..."
                           required>
                </div>
                
                <div class="form-group">
                    <label for="slug">Slug (URL thân thiện) *</label>
                    <input type="text" id="slug" name="slug" 
                           value="<?php echo $edit_category ? htmlspecialchars($edit_category['slug']) : ''; ?>" 
                           placeholder="vi-du: hanh-dong" required>
                    <small>Chỉ dùng chữ thường, số và dấu gạch ngang</small>
                </div>
                
                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea id="description" name="description" 
                              placeholder="Mô tả ngắn về thể loại..."><?php echo $edit_category ? htmlspecialchars($edit_category['description']) : ''; ?></textarea>
                </div>
                
                <div class="actions">
                    <button type="submit" name="<?php echo $edit_category ? 'update_category' : 'add_category'; ?>" 
                            class="btn">
                        <?php echo $edit_category ? '✓ Cập nhật' : '+ Thêm mới'; ?>
                    </button>
                    <?php if ($edit_category): ?>
                        <a href="manage_categories.php" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <h2 style="margin-bottom: 20px; font-size: 24px;">📋 Danh sách thể loại</h2>
        
        <div class="table-container">
            <?php if ($categories->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Tên thể loại</th>
                            <th>Slug</th>
                            <th>Mô tả</th>
                            <th style="width: 120px;">Số phim</th>
                            <th style="width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($category = $categories->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $category['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($category['name']); ?></strong></td>
                                <td><code><?php echo htmlspecialchars($category['slug']); ?></code></td>
                                <td><?php echo htmlspecialchars(substr($category['description'] ?? '', 0, 60)); ?><?php echo strlen($category['description'] ?? '') > 60 ? '...' : ''; ?></td>
                                <td>
                                    <span class="badge"><?php echo $category['movie_count']; ?> phim</span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="?edit=<?php echo $category['id']; ?>" 
                                           class="btn btn-small btn-edit">✏️ Sửa</a>
                                        <a href="?delete=<?php echo $category['id']; ?>" 
                                           class="btn btn-small btn-delete"
                                           onclick="return confirm('Bạn có chắc muốn xóa thể loại này?')">
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
                    <p>📭 Chưa có thể loại nào. Hãy thêm thể loại mới!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto generate slug from name
        document.getElementById('name').addEventListener('input', function() {
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
    </script>
</body>
</html>
