<?php
require_once "config/db.php";

// Lấy danh sách thể loại
$sql = "SELECT * FROM categories ORDER BY name ASC";
$categories = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thể loại phim</title>
    <link rel="stylesheet" href="assets/css/categories.css">
</head>
<body>

<div class="container">
    <h1>🎭 Thể loại phim</h1>
    <p class="subtitle">Khám phá phim theo thể loại yêu thích</p>
    
    <div class="category-grid">
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <a href="movies.php?category=<?= urlencode($cat['slug']) ?>" class="category-card">
                <div class="category-icon">🎬</div>
                <h3><?= htmlspecialchars($cat['name']) ?></h3>
                <p class="category-desc"><?= htmlspecialchars($cat['description'] ?? 'Xem phim ' . $cat['name']) ?></p>
            </a>
        <?php endwhile; ?>
    </div>
    
    <?php if ($categories->num_rows === 0): ?>
        <p class="no-data">❌ Chưa có thể loại nào</p>
    <?php endif; ?>
</div>

</body>
</html>
