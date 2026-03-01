<?php
session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/LoginController.php";

// Khởi tạo controller
$controller = new LoginController($conn);

// Nếu đã đăng nhập, redirect về dashboard
if ($controller->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Xử lý form login
if (isset($_POST['login'])) {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    
    $result = $controller->handleLogin($username, $password);
    
    if ($result['success']) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = $result['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - Movie Website</title>
    <link rel="stylesheet" href="/movie-site/assets/css/login.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-container { 
            width: 100%; 
            max-width: 450px; 
            padding: 20px; 
        }
        .login-box {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 8px;
            color: #1e293b;
        }
        .login-header p {
            color: #64748b;
            font-size: 14px;
        }
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .password-wrapper {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
            padding: 5px;
        }
        .btn-login {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }
        .login-footer {
            margin-top: 25px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .login-footer p {
            font-size: 13px;
            color: #94a3b8;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #64748b;
        }
        small {
            color: #64748b;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo">
                    <svg width="50" height="50" viewBox="0 0 24 24" fill="none">
                        <path d="M19 4H5C3.9 4 3 4.9 3 6V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V6C21 4.9 20.1 4 19 4ZM19 18H5V8H19V18Z" fill="currentColor"/>
                        <path d="M10 9L15 12L10 15V9Z" fill="currentColor"/>
                    </svg>
                </div>
                <h1>Admin Panel</h1>
                <p>Đăng nhập để quản lý hệ thống</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="currentColor"/>
                    </svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['logged_out'])): ?>
                <div class="alert alert-success">
                    <span>✓ Đã đăng xuất thành công!</span>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="username">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="currentColor"/>
                        </svg>
                        Tên đăng nhập
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Nhập tên đăng nhập"
                        required 
                        autocomplete="username"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M18 8H17V6C17 3.24 14.76 1 12 1C9.24 1 7 3.24 7 6V8H6C4.9 8 4 8.9 4 10V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V10C20 8.9 19.1 8 18 8ZM12 17C10.9 17 10 16.1 10 15C10 13.9 10.9 13 12 13C13.1 13 14 13.9 14 15C14 16.1 13.1 17 12 17ZM15.1 8H8.9V6C8.9 4.29 10.29 2.9 12 2.9C13.71 2.9 15.1 4.29 15.1 6V8Z" fill="currentColor"/>
                        </svg>
                        Mật khẩu
                    </label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Nhập mật khẩu"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z" fill="currentColor"/>
                            </svg>
                            <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" style="display:none;">
                                <path d="M12 7C14.76 7 17 9.24 17 12C17 12.65 16.87 13.26 16.64 13.83L19.56 16.75C21.07 15.49 22.26 13.86 23 12C21.27 7.61 17 4.5 12 4.5C10.6 4.5 9.26 4.75 8.04 5.2L10.17 7.33C10.74 7.13 11.35 7 12 7ZM2 4.27L4.28 6.55L4.74 7.01C3.08 8.3 1.78 10.02 1 12C2.73 16.39 7 19.5 12 19.5C13.55 19.5 15.03 19.2 16.38 18.66L16.8 19.08L19.73 22L21 20.73L3.27 3L2 4.27ZM7.53 9.8L9.08 11.35C9.03 11.56 9 11.78 9 12C9 13.66 10.34 15 12 15C12.22 15 12.44 14.97 12.65 14.92L14.2 16.47C13.53 16.8 12.79 17 12 17C9.24 17 7 14.76 7 12C7 11.21 7.2 10.47 7.53 9.8Z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                    <small>💡 Mặc định: admin / admin123</small>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <button type="submit" name="login" class="btn-login">
                    <span>Đăng nhập</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 4L10.59 5.41L16.17 11H4V13H16.17L10.59 18.59L12 20L20 12L12 4Z" fill="currentColor"/>
                    </svg>
                </button>
            </form>

            <div class="login-footer">
                <p>© 2025 Movie Website. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.querySelector('.eye-open');
            const eyeClosed = document.querySelector('.eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        }
    </script>
</body>
</html>
