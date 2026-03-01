<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/EditUserController.php";

// Khởi tạo controller
$controller = new EditUserController($conn);

// Lấy dữ liệu từ controller
$data = $controller->index();
$user = $data['user'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa tài khoản - Admin Panel</title>
    <link rel="stylesheet" href="/movie-site/assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-box { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            max-width: 500px; 
            margin: 50px auto; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: #374151; 
        }
        input, select { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            font-size: 14px; 
            box-sizing: border-box;
        }
        .btn-save { 
            background: #2563eb; 
            color: white; 
            width: 100%; 
            padding: 12px; 
            border: none; 
            border-radius: 8px; 
            font-weight: bold; 
            cursor: pointer; 
            margin-top: 10px; 
        }
        .btn-save:hover { 
            background: #1d4ed8; 
        }
        .btn-back { 
            display: block; 
            text-align: center; 
            margin-top: 15px; 
            color: #6b7280; 
            text-decoration: none; 
        }
        .btn-back:hover {
            color: #374151;
        }
        .password-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <main class="main-content" style="margin-left: 0; width: 100%;">
            <div class="form-box">
                <h2 style="text-align: center; margin-bottom: 20px; color: #111827;">
                    <i class="fas fa-user-edit"></i> Cập nhật thông tin
                </h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-phone"></i> Số điện thoại (Tài khoản):
                        </label>
                        <input type="text" 
                               name="phone" 
                               value="<?php echo htmlspecialchars($user['phone']); ?>" 
                               required
                               placeholder="Nhập số điện thoại">
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-user"></i> Họ và tên:
                        </label>
                        <input type="text" 
                               name="full_name" 
                               value="<?php echo htmlspecialchars($user['full_name']); ?>" 
                               required
                               placeholder="Nhập họ và tên">
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-lock"></i> Mật khẩu mới:
                        </label>
                        <input type="password" 
                               name="password" 
                               placeholder="Nhập mật khẩu mới...">
                        <div class="password-hint">
                            * Để trống nếu không muốn thay đổi mật khẩu
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-shield-alt"></i> Vai trò:
                        </label>
                        <select name="role" required>
                            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>
                                <i class="fas fa-user"></i> Khách hàng (User)
                            </option>
                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>
                                <i class="fas fa-user-shield"></i> Quản trị viên (Admin)
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                    <a href="manage_users.php" class="btn-back">
                        <i class="fas fa-times"></i> Hủy bỏ, quay lại
                    </a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
