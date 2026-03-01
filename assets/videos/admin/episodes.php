<?php
session_start();
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../controllers/EpisodeController.php";

// Khởi tạo controller
$controller = new EpisodeController($conn);

// Lấy dữ liệu từ controller
$data = $controller->index();
$movie = $data['movie'];
$episodes = $data['episodes'];
$episode_count = $data['episode_count'];
$poster_src = $data['poster_src'];
$movie_id = $data['movie_id'];
$add_result = $data['add_result'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tập phim - Admin Panel</title>
    <link rel="stylesheet" href="/movie-site/assets/css/admin-style.css">
    <link rel="stylesheet" href="/movie-site/assets/css/episodes.css">
</head>
<body>

<div class="admin-container">

    <!-- HEADER -->
    <div class="header">
        <h1>📺 Quản lý tập phim</h1>
        <a href="/movie-site/view/admin/manage_movies.php" class="btn btn-secondary">← Danh sách phim</a>
    </div>

    <!-- TÊN PHIM -->
    <h2 style="margin-bottom:20px;">
        <?= htmlspecialchars($movie->title) ?>
    </h2>

    <?php if ($add_result && !$add_result['success']): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background: #fee; border-left: 4px solid #f00; border-radius: 4px;">
            <?= htmlspecialchars($add_result['message']) ?>
        </div>
    <?php endif; ?>

    <div class="grid-2">

        <!-- FORM THÊM TẬP -->
        <div class="card">
            <h3>➕ Thêm tập mới</h3>

            <form method="post">
                <div class="form-group">
                    <label>Số tập</label>
                    <input type="number" name="episode_number" required min="1" placeholder="Nhập số tập (VD: 1, 2, 3...)">
                </div>

                <div class="form-group">
                    <label>Link video</label>
                    <input type="text" name="video_url" required placeholder="Nhập link video (YouTube, Drive, v.v.)">
                    <small style="color: #6b7280; font-size: 12px;">* Nhập URL đầy đủ của video</small>
                </div>

                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="vertical-align: middle; margin-right: 5px;">
                        <path d="M19 13H13V19H11V13H5V11H11V5H13V11H19V13Z" fill="currentColor"/>
                    </svg>
                    Thêm tập
                </button>
            </form>
        </div>

        <!-- THÔNG TIN PHIM -->
        <div class="card">
            <?php if ($poster_src): ?>
                <img src="<?= $poster_src ?>"
                     style="width:300pt; height:300pt; object-fit:cover; border-radius: 8px;"
                     alt="<?= htmlspecialchars($movie->title) ?>">
            <?php else: ?>
                <div style="width:300pt; height:300pt; background:#e0e0e0; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                    <p style="color:#999; text-align:center;">Phim này<br>chưa có poster</p>
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px;">
                <p><strong>📊 Số tập:</strong> <?= $episode_count ?></p>
                <p><strong>📅 Năm:</strong> <?= $movie->release_year ?></p>
                <p><strong>🌍 Quốc gia:</strong> <?= htmlspecialchars($movie->country) ?></p>
            </div>
        </div>

    </div>

    <!-- DANH SÁCH TẬP -->
    <div class="card" style="margin-top:30px;">
        <h3>📄 Danh sách tập</h3>

        <table class="table">
            <thead>
                <tr>
                    <th width="100">#</th>
                    <th>Link video</th>
                    <th width="120">Hành động</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($episodes) > 0): ?>
                <?php foreach ($episodes as $ep): ?>
                <tr>
                    <td><strong>Tập <?= $ep->episode_number ?></strong></td>
                    <td style="word-break: break-all;">
                        <a href="<?= htmlspecialchars($ep->video_url) ?>" target="_blank" style="color: #2563eb;">
                            <?= htmlspecialchars($ep->video_url) ?>
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-danger btn-small"
                           onclick="return confirm('Bạn có chắc muốn xóa tập <?= $ep->episode_number ?> này không?')"
                           href="delete_episode.php?id=<?= $ep->id ?>&movie_id=<?= $movie_id ?>">
                           <svg width="14" height="14" viewBox="0 0 24 24" fill="none" style="vertical-align: middle;">
                               <path d="M6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V7H6V19ZM19 4H15.5L14.5 3H9.5L8.5 4H5V6H19V4Z" fill="currentColor"/>
                           </svg>
                           Xóa
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align:center; padding: 30px; color: #6b7280;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" style="opacity: 0.3; margin-bottom: 10px;">
                            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="currentColor"/>
                        </svg>
                        <br>
                        <strong>Chưa có tập nào</strong>
                        <br>
                        <small>Hãy thêm tập phim đầu tiên bằng form bên trái</small>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
