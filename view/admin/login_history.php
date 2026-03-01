<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/LoginHistoryController.php";

// Kiểm tra quyền Admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Khởi tạo Controller
$controller = new LoginHistoryController($conn);

// Xử lý xóa lịch sử
$controller->handleClearHistory();

// Lấy dữ liệu
$result = $controller->getHistoryData(100);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đăng nhập</title>
    <link rel="stylesheet" href="/movie-site/assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f3f4f6; margin: 0; padding: 20px; font-family: 'Segoe UI', sans-serif; }
        .main-container { max-width: 1000px; margin: 0 auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 20px 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .btn-header { padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; border: 1px solid transparent; cursor: pointer; }
        .btn-exit { background-color: #f3f4f6; color: #4b5563; border-color: #e5e7eb; }
        .btn-exit:hover { background-color: #e5e7eb; color: #111827; }
        .btn-clear { background-color: #fef2f2; color: #dc2626; border-color: #fee2e2; }
        .btn-clear:hover { background-color: #dc2626; color: white; }
        .table-box { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; }
        th { text-align: left; padding: 15px; font-size: 13px; font-weight: 700; color: #6b7280; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #f3f4f6; color: #374151; font-size: 14px; }
        .status-success { color: #059669; font-weight: 600; background: #ecfdf5; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .status-fail { color: #dc2626; font-weight: 600; background: #fef2f2; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="page-header">
            <div>
                <h1 style="margin: 0; font-size: 24px; color: #111827;">Nhật ký hoạt động</h1>
                <p style="margin: 5px 0 0; color: #6b7280; font-size: 14px;">Xem 100 lượt truy cập gần nhất</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sạch lịch sử?');">
                    <button type="submit" name="clear_history" class="btn-header btn-clear">
                        <i class="fas fa-trash-alt"></i> Xóa lịch sử
                    </button>
                </form>
                <a href="dashboard.php" class="btn-header btn-exit">
                    <i class="fas fa-home"></i> Về Dashboard
                </a>
            </div>
        </div>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="30%">Tài khoản</th>
                        <th width="35%">Hành động</th>
                        <th width="25%">Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo isset($row['log_id']) ? $row['log_id'] : $row['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                <td>
                                    <?php 
                                        $act = htmlspecialchars($row['action']);
                                        if (strpos(strtolower($act), 'thất bại') !== false) {
                                            echo "<span class='status-fail'>$act</span>";
                                        } else {
                                            echo "<span class='status-success'>$act</span>";
                                        }
                                    ?>
                                </td>
                                <td style="color: #666;">
                                    <?php 
                                        echo isset($row['created_at']) ? date('H:i - d/m/Y', strtotime($row['created_at'])) : 'N/A'; 
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center; padding: 30px;">Chưa có lịch sử nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
