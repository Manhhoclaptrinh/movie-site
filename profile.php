<?php
//Tùng's Code
session_start();
require_once 'config/db.php';
require_once 'controllers/ProfileController.php';

// Khởi tạo controller
$profileController = new ProfileController($conn);

// Kiểm tra đăng nhập
$profileController->checkAuth();

$user_id = $_SESSION['user_id'];

// Xử lý cập nhật thông tin
$profileController->updateInfo($user_id);

// Xử lý đổi mật khẩu
$profileController->changePassword($user_id);

// Lấy thông tin user
$user = $profileController->getUserInfo($user_id);

// Lấy thông báo
$message = $profileController->getMessage();
$msg_type = $profileController->getMessageType();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #0f172a; color: #e2e8f0; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .profile-container { background-color: #1e293b; padding: 40px; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .header-profile { text-align: center; margin-bottom: 30px; }
        .avatar-circle { width: 80px; height: 80px; background: linear-gradient(135deg, #6366f1, #a855f7); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; color: white; margin-bottom: 15px; text-transform: uppercase; }
        h2 { margin: 0; font-size: 24px; }
        p.role { color: #94a3b8; margin: 5px 0 0; font-size: 14px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #cbd5e1; }
        input { width: 100%; padding: 12px; background-color: #334155; border: 1px solid #475569; border-radius: 8px; color: white; font-size: 14px; box-sizing: border-box; }
        input:focus { outline: none; border-color: #818cf8; }
        input[readonly] { background-color: #1e293b; color: #64748b; cursor: not-allowed; }
        
        .btn-save { width: 100%; padding: 12px; background-color: #6366f1; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-save:hover { background-color: #4f46e5; }
        
        .divider { border-top: 1px solid #334155; margin: 30px 0; position: relative; }
        .divider span { position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: #1e293b; padding: 0 10px; color: #64748b; font-size: 12px; }
        
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px; }
        .alert-success { background: #064e3b; color: #6ee7b7; border: 1px solid #059669; }
        .alert-error { background: #450a0a; color: #fca5a5; border: 1px solid #dc2626; }
        
        .btn-back { display: block; text-align: center; margin-top: 20px; color: #94a3b8; text-decoration: none; font-size: 14px; }
        .btn-back:hover { color: white; }

        /* NÚT ĐĂNG XUẤT MỚI */
        .btn-logout {
            display: block; width: 100%; padding: 12px; box-sizing: border-box;
            background-color: #334155; color: #ef4444; 
            text-align: center; text-decoration: none; font-weight: 600; 
            border-radius: 8px; margin-top: 15px; border: 1px solid #475569; 
            transition: 0.2s;
        }
        .btn-logout:hover { background-color: #ef4444; color: white; border-color: #ef4444; }
    </style>
</head>
<body>

    <div class="profile-container">
        <?php if($message): ?>
            <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="header-profile">
            <div class="avatar-circle">
                <?php echo mb_substr($user['full_name'], 0, 1); ?>
            </div>
            <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
            <p class="role">Thành viên</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Tài khoản (SĐT):</label>
                <input type="text" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Họ tên:</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <button type="submit" name="btn_update_info" class="btn-save">Lưu thay đổi</button>
        </form>

        <div class="divider"><span>BẢO MẬT</span></div>

        <form method="POST">
            <div class="form-group">
                <input type="password" name="old_password" placeholder="Mật khẩu cũ" required>
            </div>
            <div class="form-group">
                <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
            </div>
            <button type="submit" name="btn_change_pass" class="btn-save" style="background-color: #0f172a; border: 1px solid #475569;">Đổi mật khẩu</button>
        </form>

        <a href="view/quanly_user/logout.php" class="btn-logout" onclick="return confirm('Bạn có chắc muốn đăng xuất?');">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </a>

        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Quay về trang chủ</a>
    </div>

</body>
</html>
