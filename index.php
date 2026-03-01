<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/controllers/HomeController.php";

$homeController = new HomeController($conn);
$data = $homeController->index();

$banner       = $data['banner'];
$koreanMovies = $data['koreanMovies'];
$chinaMovies  = $data['chinaMovies'];
$usMovies     = $data['usMovies'];

$hotMovies    = $conn->query("SELECT id, title, slug, poster FROM movies ORDER BY views DESC LIMIT 5");
$likedMovies  = $conn->query("SELECT id, title, slug, poster FROM movies ORDER BY likes DESC LIMIT 5");
$latestMovies = $conn->query("SELECT id, title, slug, poster FROM movies ORDER BY id DESC LIMIT 10");
$top3Anime    = $conn->query("SELECT * FROM top_phim_bo_homnay ORDER BY rank ASC LIMIT 3");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ITPhim Clone</title>

    <!-- CSS quan trọng trước -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- Defer các CDN không critical -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" media="print" onload="this.media='all'">

</head>
<body>
<style>
.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
</style>
<!-- ── HEADER ── -->
<header class="header">
    <div class="logo">ITPhim</div>
    <nav class="menu">
        <a href="movies.php?type=le">Phim Lẻ</a>
        <a href="movies.php?type=bo">Phim Bộ</a>
        <a href="categories.php">Thể loại</a>
        <a href="countries.php">Quốc gia</a>
    </nav>
    <div class="right">
        <form action="search.php" method="GET" class="search-form">
            <input type="text" name="q" class="search"
                   placeholder="Tìm kiếm phim, diễn viên" autocomplete="off">
        </form>
        <!-- Thay pravatar bằng ảnh local để tránh phụ thuộc domain ngoài -->
        <a href="profile.php" style="display:flex;align-items:center;text-decoration:none">
            <img id="randomAvatar" class="avatar" alt="Avatar">
        </a>
    </div>
</header>

<!-- ── HERO / BANNER ── -->
<?php if (!empty($banner)): ?>
<section class="hero" style="background-image:url('<?= htmlspecialchars($banner['image']) ?>')">
    <div class="hero-bg">
        <!-- Preload ảnh hero vì đây là LCP element -->
        <img src="<?= htmlspecialchars($banner['image']) ?>"
             alt="<?= htmlspecialchars($banner['title']) ?>"
             fetchpriority="high">
    </div>
    <div class="hero-overlay">
        <h1><?= htmlspecialchars($banner['title']) ?></h1>
        <p><?= htmlspecialchars($banner['description']) ?></p>
        <div class="hero-actions">
            <a href="movie.php?slug=<?= urlencode($banner['movie_slug']) ?>" class="btn-play">▶ Xem ngay</a>
            <a href="movie.php?slug=<?= urlencode($banner['movie_slug']) ?>" class="btn-more">ℹ Thông tin</a>
        </div>
    </div>
</section>
<?php else: ?>
<section class="hero">
    <div class="hero-overlay">
        <h1>Chưa có banner</h1>
        <p>Vui lòng thêm banner trong CSDL</p>
    </div>
</section>
<?php endif; ?>

<!-- ── PHIM HÀN QUỐC ── -->
<section class="section">
    <div class="section-left">
        <h2>Phim Hàn<br>Quốc mới</h2>
        <a href="#">Xem toàn bộ ›</a>
    </div>
    <div class="slider">
        <?php while ($m = $koreanMovies->fetch_assoc()): ?>
        <a href="movie.php?slug=<?= urlencode($m['movie_slug']) ?>" class="slide-card" style="text-decoration:none;color:inherit">
            <img src="<?= htmlspecialchars($m['poster']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" loading="lazy">
            <span class="badge">👁 <?= (int)$m['views'] ?></span>
            <h3><?= htmlspecialchars($m['title']) ?></h3>
        </a>
        <?php endwhile; ?>
    </div>
</section>

<!-- ── PHIM TRUNG QUỐC ── -->
<section class="section">
    <div class="section-left">
        <h2>Phim Trung<br>Quốc mới</h2>
        <a href="#">Xem toàn bộ ›</a>
    </div>
    <div class="slider">
        <?php while ($m = $chinaMovies->fetch_assoc()): ?>
        <a href="movie.php?slug=<?= urlencode($m['movie_slug']) ?>" class="movie-card" style="text-decoration:none;color:inherit">
            <img src="<?= htmlspecialchars($m['poster']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" loading="lazy">
            <span class="badge">👁 <?= (int)$m['views'] ?></span>
            <h3><?= htmlspecialchars($m['title']) ?></h3>
        </a>
        <?php endwhile; ?>
    </div>
</section>

<!-- ── PHIM US-UK ── -->
<section class="section">
    <div class="section-left pink">
        <h2>Phim US-<br>UK mới</h2>
        <a href="#">Xem toàn bộ ›</a>
    </div>
    <div class="slider">
        <?php while ($m = $usMovies->fetch_assoc()): ?>
        <a href="movie.php?slug=<?= urlencode($m['movie_slug']) ?>" class="movie-card" style="text-decoration:none;color:inherit">
            <img src="<?= htmlspecialchars($m['poster']) ?>" alt="<?= htmlspecialchars($m['title']) ?>" loading="lazy">
            <span class="badge">👁 <?= (int)$m['views'] ?></span>
            <h3><?= htmlspecialchars($m['title']) ?></h3>
        </a>
        <?php endwhile; ?>
    </div>
</section>

<!-- ── TOP SÔI NỔI / YÊU THÍCH ── -->
<div class="top-wrapper">
    <div class="top-box">
        <div class="box-title">🎬 SÔI NỔI NHẤT</div>
        <?php $i = 1; while ($m = $hotMovies->fetch_assoc()): ?>
        <div class="top-item">
            <span class="rank"><?= $i++ ?>.</span>
            <span class="dash">—</span>
            <img src="<?= htmlspecialchars($m['poster']) ?>" alt="" loading="lazy">
            <a href="movie.php?slug=<?= htmlspecialchars($m['slug']) ?>" class="movie-title">
                <?= htmlspecialchars($m['title']) ?>
            </a>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="top-box">
        <div class="box-title">💛 YÊU THÍCH NHẤT</div>
        <?php $i = 1; while ($m = $likedMovies->fetch_assoc()): ?>
        <div class="top-item">
            <span class="rank"><?= $i++ ?>.</span>
            <span class="dash">—</span>
            <img src="<?= htmlspecialchars($m['poster']) ?>" alt="" loading="lazy">
            <a href="movie.php?slug=<?= htmlspecialchars($m['slug']) ?>" class="movie-title">
                <?= htmlspecialchars($m['title']) ?>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- ── PHIM ĐIỆN ẢNH MỚI ── -->
<section class="movie-section">
    <div class="section-header">
        <h2>Phim Điện Ảnh Mới Có</h2>
        <div class="nav-buttons">
            <button type="button" onclick="scrollRow(-1)">❮</button>
            <button type="button" onclick="scrollRow(1)">❯</button>
        </div>
    </div>
    <div class="movie-container" id="movieContainer">
        <?php while ($m = $latestMovies->fetch_assoc()): ?>
        <div class="movie-card">
            <a href="movie.php?slug=<?= htmlspecialchars($m['slug']) ?>" class="movie-link">
                <div class="poster">
                    <img src="<?= htmlspecialchars($m['poster']) ?>"
                         alt="<?= htmlspecialchars($m['title']) ?>"
                         loading="lazy"
                         onerror="this.onerror=null; this.src='assets/img/no-poster.jpg'">
                    <span class="badge">P.Đề</span>
                </div>
                <div class="info">
                    <h4><?= htmlspecialchars($m['title']) ?></h4>
                </div>
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- ── TOP 3 ANIME ── -->
<section class="top10-section">
    <h2 class="top10-title">Top 3 phim Anime hôm nay</h2>

    <?php if ($top3Anime === false): ?>
        <p style="color:red;text-align:center">Lỗi truy vấn: <?= htmlspecialchars($conn->error) ?></p>
    <?php elseif ($top3Anime->num_rows === 0): ?>
        <p style="text-align:center;color:#999">Chưa có dữ liệu top 3 hôm nay.</p>
    <?php else:
        $movies = [];
        $rank = 1;
        while ($m = $top3Anime->fetch_assoc()) { $m['rank'] = $rank++; $movies[] = $m; }
    ?>
    <div class="carousel-container">
        <div class="swiper top10-carousel">
            <div class="swiper-wrapper">
                <?php foreach ($movies as $m): ?>
                <div class="swiper-slide">
                    <div class="carousel-item position-relative overflow-hidden rounded-3">
                        <img src="<?= htmlspecialchars($m['poster_url'] ?? '') ?>"
                             alt="<?= htmlspecialchars($m['title_vi'] ?? 'Phim không tên') ?>"
                             class="carousel-img w-100"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='assets/img/no-poster.jpg'">
                        <div class="carousel-overlay position-absolute bottom-0 start-0 w-100 p-4 text-white">
                            <h3 class="fs-4 fw-bold mb-1"><?= htmlspecialchars($m['title_vi'] ?? 'Unknown') ?></h3>
                            <p class="fs-6 opacity-75 mb-1"><?= htmlspecialchars($m['title_en'] ?? '') ?></p>
                            <p class="fs-6 opacity-75"><?= htmlspecialchars($m['episode_info'] ?? 'Chưa cập nhật') ?></p>
                        </div>
                        <div class="rank-badge position-absolute top-0 start-0 m-3 fw-bold text-white bg-danger px-3 py-1 rounded-pill fs-3">
                            <?= $m['rank'] ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    <?php endif; ?>
</section>

<!-- ── FOOTER ── -->
<footer class="itphim-footer">
    <div class="footer-inner">

        <!-- Logo -->
        <div class="footer-brand">
            <div class="footer-logo"><span>IT</span>Phim</div>
            <span class="footer-tagline">Stream Free · HD · Vietsub</span>
        </div>

        <!-- Sovereignty badge -->
        <div class="sovereignty-badge">
            <i class="fas fa-flag"></i>
            Hoàng Sa &amp; Trường Sa là của Việt Nam!
        </div>

        <!-- Grid -->
        <div class="footer-grid">

            <!-- About -->
            <div class="footer-about">
                <p>Trang xem phim online chất lượng cao miễn phí — Vietsub, thuyết minh, lồng tiếng Full HD. Kho phim không ngừng cập nhật từ Hàn Quốc, Trung Quốc, Nhật Bản, Âu Mỹ và Việt Nam.</p>
                <div class="social-row">
                    <?php
                    $socials = [
                        ['fab fa-telegram-plane','Telegram'],
                        ['fab fa-discord',       'Discord'],
                        ['fab fa-x-twitter',     'X'],
                        ['fab fa-tiktok',        'TikTok'],
                        ['fab fa-youtube',       'YouTube'],
                        ['fas fa-envelope',      'Email'],
                        ['fab fa-instagram',     'Instagram'],
                    ];
                    foreach($socials as [$icon,$label]): ?>
                    <a href="#" class="social-btn" title="<?= $label ?>"><i class="<?= $icon ?>"></i></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Khám phá -->
            <div class="footer-col">
                <h4>Khám phá</h4>
                <ul>
                    <li><a href="movies.php?type=le">Phim Lẻ</a></li>
                    <li><a href="movies.php?type=bo">Phim Bộ</a></li>
                    <li><a href="categories.php">Thể Loại</a></li>
                    <li><a href="countries.php">Quốc Gia</a></li>
                    <li><a href="#">Phim Anime</a></li>
                    <li><a href="#">Phim Chiếu Rạp</a></li>
                </ul>
            </div>

            <!-- Hỗ trợ -->
            <div class="footer-col">
                <h4>Hỗ trợ</h4>
                <ul>
                    <li><a href="#">Hỏi &amp; Đáp</a></li>
                    <li><a href="#">Liên hệ</a></li>
                    <li><a href="#">Báo lỗi phim</a></li>
                    <li><a href="#">Yêu cầu phim</a></li>
                </ul>
            </div>

            <!-- Pháp lý -->
            <div class="footer-col">
                <h4>Pháp lý</h4>
                <ul>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">DMCA</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <p>© 2025 <a href="#">ITPhim</a>. All Rights Reserved. — Không lưu trữ nội dung vi phạm bản quyền.</p>
            <div class="quality-badges">
                <span class="qbadge">HD</span>
                <span class="qbadge">4K</span>
                <span class="qbadge">VIETSUB</span>
            </div>
        </div>

    </div>
</footer>

<!-- JS cuối trang để không block render -->
<script src="assets/js/main.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.top10-carousel', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: { delay: 5000 },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
    });
});
</script>
<script>
    document.getElementById("randomAvatar").src =
        "https://picsum.photos/100?random=" + Math.random();
</script>
</body>
</html>
