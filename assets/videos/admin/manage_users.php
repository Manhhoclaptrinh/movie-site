<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/UserController.php";

// Kiểm tra quyền Admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Khởi tạo controller
$controller = new UserController($conn);

// Lấy danh sách users
$users = $controller->getAllUsers();

// Đếm tổng số users
$totalUsers = $controller->getTotalUsers();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tài khoản</title>
    <link rel="stylesheet" href="/movie-site/assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', sans-serif;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        h1 { margin: 0; font-size: 24px; color: #111827; }
        .sub-text { margin: 5px 0 0; color: #6b7280; font-size: 14px; }

        /* Nút Thoát */
        .btn-exit {
            background-color: #fef2f2;
            color: #dc2626;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #fee2e2;
            transition: all 0.2s;
        }
        .btn-exit:hover {
            background-color: #dc2626;
            color: white;
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
        }

        /* Lưới thẻ User */
        .user-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 25px; 
        }

        .user-card { 
            background: white; 
            border-radius: 16px; 
            padding: 25px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
            border: 1px solid #f3f4f6; 
            transition: 0.3s; 
            display: flex;
            flex-direction: column;
        }
        .user-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        
        .card-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        
        /* Avatar */
        .avatar-circle { 
            width: 55px; height: 55px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 22px; font-weight: bold; text-transform: uppercase; 
        }
        .avatar-admin { background: #dbeafe; color: #1e40af; } 
        .avatar-user { background: #f3e8ff; color: #7e22ce; } 
        
        .user-info h3 { margin: 0; font-size: 17px; color: #111827; font-weight: 700; }
        .user-info p { margin: 4px 0 0; color: #6b7280; font-size: 14px; }
        
        /* Thông tin chi tiết */
        .card-body { 
            border-top: 1px solid #f3f4f6; 
            padding-top: 15px; 
            margin-bottom: 20px;
            flex-grow: 1; 
        }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .badge-admin { background: #dbeafe; color: #1e40af; }
        .badge-user { background: #f3e8ff; color: #7e22ce; }

        /* Nút hành động (Sửa/Xóa) */
        .card-actions { display: flex; gap: 10px; }
        .btn-action { 
            flex: 1; padding: 10px; border-radius: 8px; text-align: center; text-decoration: none; 
            font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px; 
            transition: 0.2s;
        }
        .btn-edit { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
        .btn-edit:hover { background: #ffedd5; }
        
        .btn-delete { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }
        .btn-delete:hover { background: #fee2e2; }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="page-header">
            <div>
                <h1>Danh sách thành viên</h1>
                <p class="sub-text">Tổng số: <?php echo $totalUsers; ?> người</p>
            </div>
            
            <a href="dashboard.php" class="btn-exit">
                <i class="fas fa-home"></i> Về Dashboard
            </a>
        </div>

        <div class="user-grid">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $row): ?>
                    <?php 
                        $roleClass = ($row['role'] == 'admin') ? 'admin' : 'user';
                        $roleName = ($row['role'] == 'admin') ? 'Quản trị viên' : 'Khách hàng';
                        $displayName = !empty($row['full_name']) ? $row['full_name'] : 'Không tên';
                        $firstLetter = mb_substr($displayName, 0, 1, 'UTF-8');
                    ?>
                    
                    <div class="user-card">
                        <div class="card-header">
                            <div class="avatar-circle avatar-<?php echo $roleClass; ?>">
                                <?php echo strtoupper($firstLetter); ?>
                            </div>
                            <div class="user-info">
                                <h3><?php echo htmlspecialchars($displayName); ?></h3>
                                <p><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($row['phone']); ?></p>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="info-row">
                                <span style="color: #6b7280;">Vai trò:</span>
                                <span class="badge badge-<?php echo $roleClass; ?>">
                                    <?php echo $roleName; ?>
                                </span>
                            </div>
                            <div class="info-row">
                                <span style="color: #6b7280;">Ngày tham gia:</span>
                                <span><?php echo isset($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : 'N/A'; ?></span>
                            </div>
                        </div>

                        <div class="card-actions">
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" 
                               class="btn-action btn-delete" 
                               onclick="return confirm('Bạn có chắc chắn muốn XÓA tài khoản <?php echo $displayName; ?>?');">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; padding: 40px; background: white; border-radius: 12px; color: #6b7280;">
                    Chưa có tài khoản nào.
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
