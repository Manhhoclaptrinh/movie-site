<?php
require_once __DIR__ . "/../../controllers/CommentAdminController.php";
?>

<h2>💬 Quản lý bình luận</h2>

<table border="1" width="100%" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Phim</th>
        <th>Người bình luận</th>
        <th>Nội dung</th>
        <th>Thời gian</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($comments as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['movie_title']) ?></td>
            <td><?= htmlspecialchars($c['username'] ?? 'Ẩn danh') ?></td>
            <td><?= nl2br(htmlspecialchars($c['content'])) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
            <td>
                <form method="POST" action="/movie-site/controllers/CommentAdminController.php"
                    onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?');">
                    <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
                    <button type="submit">🗑️ Xóa</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
