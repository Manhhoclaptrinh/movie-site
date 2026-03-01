<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/UserAuthController.php";

// Khởi tạo controller
$controller = new UserAuthController($conn);

// Nếu đã đăng nhập, redirect về trang chủ
if ($controller->isLoggedIn()) {
    header('Location: /movie-site/index.php');
    exit;
}

$alert = null;

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $controller->handleLogin($_POST['phone'], $_POST['password']);
    
    if ($result['success']) {
        $alert = [
            'type' => 'success',
            'title' => 'Thành công',
            'text' => $result['message'],
            'redirect' => 'http://localhost/movie-site/index.php'
        ];
    } else {
        $alert = [
            'type' => 'error',
            'title' => 'Lỗi',
            'text' => $result['message']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { background: linear-gradient(135deg, #667eea, #764ba2); height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif; }
        .box { background: #ffffff; padding: 40px 30px; border-radius: 15px; width: 100%; max-width: 400px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; margin: 0 0 30px 0; font-weight: 700; }
        .input-group { position: relative; margin-bottom: 20px; }
        .icon-left { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 14px; pointer-events: none; }
        input { width: 100%; padding: 14px 45px 14px 40px; border: 1px solid #eee; background-color: #f9f9f9; border-radius: 8px; outline: none; transition: 0.3s; }
        input:focus { border-color: #8e44ad; background-color: #fff; }
        .toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #aaa; }
        button { width: 100%; padding: 14px; background: #8e44ad; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 15px; transition: 0.3s; }
        button:hover { background: #732d91; transform: translateY(-2px); }
        .footer-link { text-align: center; margin-top: 25px; font-size: 14px; color: #666; }
        .footer-link a { text-decoration: none; color: #8e44ad; font-weight: 700; }
    </style>
</head>
<body>
    <div class="box">
        <h2>ĐĂNG NHẬP</h2>
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-phone icon-left"></i>
                <input type="text" name="phone" placeholder="Số điện thoại" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock icon-left"></i>
                <input type="password" name="password" id="passInput" placeholder="Mật khẩu" required>
                <i class="fas fa-eye toggle-password" onclick="togglePass()"></i>
            </div>
            
            <button type="submit">ĐĂNG NHẬP</button>
        </form>

        <div class="footer-link">
            Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePass() {
            const input = document.getElementById('passInput');
            const icon = document.querySelector('.toggle-password');
            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle('fa-eye-slash');
            icon.classList.toggle('fa-eye');
        }

        <?php if($alert): ?>
        Swal.fire({
            icon: '<?=$alert['type']?>',
            title: '<?=$alert['title']?>',
            text: '<?=$alert['text']?>',
            confirmButtonColor: '#8e44ad',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            <?php if(isset($alert['redirect'])): ?>
            window.location.href = '<?=$alert['redirect']?>';
            <?php endif; ?>
        });
        <?php endif; ?>
    </script>
</body>
</html>
