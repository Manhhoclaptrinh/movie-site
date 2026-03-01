<?php
// register.php 
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/RegisterController.php";

// Khởi tạo controller
$registerController = new RegisterController($conn);

// Xử lý đăng ký
$registerController->handleRegister();

// Lấy thông báo
$error = $registerController->getError();
$success = $registerController->getSuccess();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Tài Khoản</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Giữ nguyên giao diện đẹp giống trang Login */
        * { box-sizing: border-box; }
        body { background: linear-gradient(135deg, #667eea, #764ba2); height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; }
        .box { background: #ffffff; padding: 40px 30px; border-radius: 15px; width: 100%; max-width: 400px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; margin: 0 0 20px 0; font-weight: 700; }
        .input-group { position: relative; margin-bottom: 15px; }
        .icon-left { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 14px; pointer-events: none; }
        input { width: 100%; padding: 12px 45px 12px 40px; border: 1px solid #eee; background-color: #f9f9f9; border-radius: 8px; outline: none; transition: 0.3s; }
        input:focus { border-color: #8e44ad; background-color: #fff; }
        
        button { width: 100%; padding: 12px; background: #8e44ad; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 15px; transition: 0.3s; margin-top: 10px; }
        button:hover { background: #732d91; transform: translateY(-2px); }
        
        .footer-link { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .footer-link a { text-decoration: none; color: #8e44ad; font-weight: 700; }
        
        /* Thông báo lỗi/thành công */
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; text-align: center; }
        .alert-error { background: #fde8e8; color: #c81e1e; border: 1px solid #f8b4b4; }
        .alert-success { background: #def7ec; color: #03543f; border: 1px solid #84e1bc; }
    </style>
</head>
<body>
    <div class="box">
        <h2>ĐĂNG KÝ</h2>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <i class="fas fa-user icon-left"></i>
                <input type="text" name="full_name" placeholder="Họ và tên" required>
            </div>

            <div class="input-group">
                <i class="fas fa-phone icon-left"></i>
                <input type="text" name="phone" placeholder="Số điện thoại" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock icon-left"></i>
                <input type="password" name="password" placeholder="Mật khẩu" required>
            </div>

            <div class="input-group">
                <i class="fas fa-check-circle icon-left"></i>
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>
            
            <button type="submit">ĐĂNG KÝ NGAY</button>
        </form>

        <div class="footer-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập</a>
        </div>
    </div>
</body>
</html>
