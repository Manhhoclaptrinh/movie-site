<?php
require_once "config/db.php";

// Lấy danh sách quốc gia từ database (distinct)
$sql = "SELECT DISTINCT country, COUNT(*) as total 
        FROM movies 
        WHERE country IS NOT NULL AND country != ''
        GROUP BY country 
        ORDER BY total DESC, country ASC";
$countries = $conn->query($sql);

// Map quốc gia với cờ emoji (có thể mở rộng)
$countryFlags = [
    'Trung Quốc' => '🇨🇳',
    'Hàn Quốc' => '🇰🇷',
    'US-UK' => '🇺🇸🇬🇧',
    'Nhật Bản' => '🇯🇵',
    'Thái Lan' => '🇹🇭',
    'Việt Nam' => '🇻🇳',
    'Ấn Độ' => '🇮🇳',
    'Đài Loan' => '🇹🇼',
    'Hồng Kông' => '🇭🇰',
    'Pháp' => '🇫🇷',
    'Đức' => '🇩🇪',
    'Anh' => '🇬🇧',
    'Mỹ' => '🇺🇸',
    'Canada' => '🇨🇦',
    'Úc' => '🇦🇺',
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phim theo quốc gia</title>
    <link rel="stylesheet" href="assets/css/countries.css">
</head>
<body>

<div class="container">
    <h1>🌍 Phim theo quốc gia</h1>
    <p class="subtitle">Khám phá điện ảnh từ khắp thế giới</p>
    
    <div class="country-grid">
        <?php while ($country = $countries->fetch_assoc()): 
            $countryName = $country['country'];
            $flag = $countryFlags[$countryName] ?? '🎬';
        ?>
            <a href="movies.php?country=<?= urlencode($countryName) ?>" class="country-card">
                <div class="country-flag"><?= $flag ?></div>
                <h3><?= htmlspecialchars($countryName) ?></h3>
                <p class="country-count"><?= number_format($country['total']) ?> phim</p>
            </a>
        <?php endwhile; ?>
    </div>
    
    <?php if ($countries->num_rows === 0): ?>
        <p class="no-data">❌ Chưa có dữ liệu quốc gia</p>
    <?php endif; ?>
</div>

</body>
</html>
