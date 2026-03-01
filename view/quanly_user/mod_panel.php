<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
// 1. Chưa đăng nhập -> Login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 2. Lấy quyền từ DB
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'moderator' && $user['role'] !== 'admin') {
    echo "<script>
        alert('⛔ Dừng lại! Khu vực này chỉ dành cho Moderator.'); 
        window.location.href='profile.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khu Vực Moderator</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #fdfbf7; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        .header { background: #fff; padding: 20px 30px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-bottom: 4px solid #e67e22; }
        .btn-back { text-decoration: none; color: #333; font-weight: 600; padding: 10px 20px; background: #eee; border-radius: 30px; }
        .btn-back:hover { background: #ddd; }

        .grid-work { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        
        .card-task { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: 0.3s; border-left: 5px solid #ccc; }
        .card-task.urgent { border-left-color: #e74c3c; } /* Việc gấp màu đỏ */
        .card-task.normal { border-left-color: #3498db; } /* Việc thường màu xanh */

        .card-task:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        .actions { margin-top: 15px; display: flex; gap: 10px; }
        .btn { flex: 1; border: none; padding: 8px; border-radius: 5px; color: white; font-weight: bold; cursor: pointer; }
        .btn-ok { background: #2ecc71; }
        .btn-del { background: #e74c3c; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h2 style="margin: 0; color: #d35400;">🛡️ BẢNG ĐIỀU KHIỂN MOD</h2>
                <p style="margin: 5px 0 0 0; color: #7f8c8d;">Chào sếp <strong><?=$user['full_name']?></strong>, chúc một ngày làm việc hiệu quả!</p>
            </div>
            <a href="http://localhost/movie-site/profile.php" class="btn-back">← Về trang cá nhân</a>
        </div>

        <h3 style="margin-top: 30px; color: #555;">📋 Danh sách phim/bình luận chờ duyệt:</h3>

        <div class="grid-work">
            <div class="card-task urgent">
                <div style="color: #e74c3c; font-weight: bold; font-size: 12px; margin-bottom: 5px;">🔥 BÁO CÁO VI PHẠM</div>
                <h3 style="margin: 0 0 10px 0;">Comment: "Web lừa đảo..."</h3>
                <p style="font-size: 13px; color: #666;">User: <strong>HackerLỏ</strong> | Tại phim: <strong>Mai</strong></p>
                <div class="actions">
                    <button class="btn btn-del">Xóa & Ban</button>
                    <button class="btn btn-ok">Bỏ qua</button>
                </div>
            </div>

            <div class="card-task normal">
                <div style="color: #3498db; font-weight: bold; font-size: 12px; margin-bottom: 5px;">🎬 PHIM MỚI</div>
                <h3 style="margin: 0 0 10px 0;">Phim: Đào, Phở và Piano</h3>
                <p style="font-size: 13px; color: #666;">Người đăng: <strong>CTV_Tung</strong></p>
                <div class="actions">
                    <button class="btn btn-ok">Duyệt đăng</button>
                    <button class="btn btn-del">Từ chối</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
