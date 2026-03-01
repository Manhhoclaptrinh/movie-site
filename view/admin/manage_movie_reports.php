<?php
session_start();
require_once "../../config/db.php";
require_once "../../models/MovieErrorReportModel.php";

$model = new MovieErrorReportModel($conn);
$reports = $model->getAll();
?>
<h2>🚨 Quản lý báo lỗi phim</h2>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Tên phim</th>
    <th>Nội dung lỗi</th>
    <th>Trạng thái</th>
    <th>Thời gian</th>
</tr>

<?php if ($reports->num_rows == 0): ?>
<tr><td colspan="5">Không có báo lỗi nào</td></tr>
<?php else: ?>
<?php while ($r = $reports->fetch_assoc()): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['title']) ?></td>
    <td><?= htmlspecialchars($r['content']) ?></td>
    <td><?= $r['status'] == 0 ? 'Chưa xử lý' : 'Đã xử lý' ?></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php endwhile; ?>
<?php endif; ?>
</table>
