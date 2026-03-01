<?php
session_start();
require_once "config/db.php";
require_once "controllers/MovieDetailController.php";

// Kiểm tra slug
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    die("❌ Không tìm thấy phim");
}

$slug = $_GET['slug'];

// Khởi tạo controller
$movieDetailController = new MovieDetailController($conn);

// Xử lý đánh giá
$movieDetailController->handleRating($slug);

// Xử lý bình luận
$movieDetailController->handleComment($slug);

// Hiển thị chi tiết phim
$movieDetailController->show($slug);

// Lấy dữ liệu để hiển thị
$movie = $movieDetailController->getMovie();
$tags = $movieDetailController->getTags();
$comments = $movieDetailController->getComments();
$episodes = $movieDetailController->getEpisodes();
$ratingStats = $movieDetailController->getRatingStats();

$avg_rating = $ratingStats['avg_rating'];
$total_rating = $ratingStats['total_rating'];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title']) ?> - Xem phim online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/movie-multi-theme.css">
</head>

<body>

    <header class="header">
        <div class="container">
            <a href="index.php" class="back-link">
                ← Trang chủ
            </a>
        </div>
    </header>

    <div class="container">
        <section class="hero">
            <div class="hero-gradient"></div>
            <div class="hero-content">
                <div class="poster-wrapper">
                    <div class="poster">
                        <img src="<?= htmlspecialchars($movie['poster']) ?>"
                            alt="<?= htmlspecialchars($movie['title']) ?>"
                            crossorigin="anonymous">
                    </div>
                </div>

                <div class="movie-info">
                    <h1 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h1>

                    <div class="meta-info">
                        <div class="meta-item">
                            <span class="meta-label">Thể loại</span>
                            <span class="meta-value"><?= htmlspecialchars($movie['category_name'] ?? 'Chưa phân loại') ?></span>
                        </div>

                        <?php if ($movie['release_year']): ?>
                            <div class="meta-item">
                                <span class="meta-label">Năm</span>
                                <span class="meta-value"><?= htmlspecialchars($movie['release_year']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($movie['country']): ?>
                            <div class="meta-item">
                                <span class="meta-label">Quốc gia</span>
                                <span class="meta-value"><?= htmlspecialchars($movie['country']) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="meta-item">
                            <span class="meta-label">Lượt xem</span>
                            <span class="meta-value">
                                <span class="views-badge">
                                    👁️ <?= number_format($movie['views'] + 1) ?>
                                </span>
                            </span>
                        </div>

                        <!-- BOX BÁO LỖI PHIM -->
                        <div class="movie-report-box">

                            <?php if (isset($_GET['success'])): ?>
                                <div class="report-success">
                                    ✅ Gửi báo lỗi thành công! Cảm ơn bạn đã phản hồi 💙
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="controllers/MovieErrorReportController.php">
                                <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">

                                <textarea name="content" required
                                    placeholder="Nhập lỗi bạn gặp (không xem được, sai tập, lỗi phụ đề...)"></textarea>

                                <button type="submit">🚨 Gửi báo lỗi</button>
                            </form>
                        </div>



                        <script>
                            function openReport(movieId) {
                                fetch('/movie-site/view/report_movie.php?movie_id=' + movieId)
                                    .then(res => res.text())
                                    .then(html => {
                                        document.body.insertAdjacentHTML(
                                            'beforeend',
                                            `<div id="report-modal">${html}</div>`
                                        );
                                    });
                            }
                        </script>


                    </div>

                    <?php if ($movie['description']): ?>
                        <div class="description">
                            <?= nl2br(htmlspecialchars($movie['description'])) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($tags)): ?>
                        <div class="tags-section">
                            <div class="meta-label">Tags</div>
                            <div class="tags-container">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="tag"><?= htmlspecialchars($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php if ((int)$movie['is_series'] === 1): ?>
            <section class="episodes-section">
                <h2 class="section-title">Danh sách tập phim</h2>

                <?php if (!empty($episodes)): ?>
                    <ul class="episodes-grid">
                        <?php foreach ($episodes as $ep): ?>
                            <li class="episode-card">
                                <div class="episode-number">
                                    Tập <?= (int)$ep['episode_number'] ?>
                                </div>

                                <a class="episode-link"
                                    href="http://localhost/movie-site/view/watch/watch.php?slug=<?= urlencode($movie['slug']) ?>&ep=<?= (int)$ep['episode_number'] ?>">
                                    <span class="play-icon">▶</span>
                                    Xem ngay
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📺</div>
                        <p>Chưa có tập phim nào</p>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>

    <section class="rating-section">
        <div class="rating-summary">
            <div class="rating-score">
                <?= $avg_rating ?: '0.0' ?>
            </div>

            <div class="rating-stars">
                <?php
                $avg = round($avg_rating);
                for ($i = 1; $i <= 5; $i++):
                ?>
                    <span class="<?= $i <= $avg ? 'active' : '' ?>">★</span>
                <?php endfor; ?>
            </div>

            <div class="rating-count">
                <?= $total_rating ?> đánh giá
            </div>
        </div>

        <?php if (isset($_SESSION['admin_id'])): ?>
            <form method="POST" class="rating-form">
                <div class="rating-input">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                        <label for="star<?= $i ?>">★</label>
                    <?php endfor; ?>
                </div>
                <button type="submit">Gửi đánh giá</button>
            </form>
        <?php else: ?>
            <div class="login-required">🔒 Vui lòng đăng nhập để đánh giá</div>
        <?php endif; ?>
    </section>

    <section class="comments-section">
        <h2 class="section-title">💬 Bình luận</h2>

        <?php if (isset($_SESSION['admin_id'])): ?>
            <form method="POST" class="comment-form">
                <textarea
                    name="comment_content"
                    placeholder="Nhập bình luận của bạn..."
                    required></textarea>
                <button type="submit">Gửi bình luận</button>
            </form>
        <?php else: ?>
            <div class="login-required">
                🔒 Vui lòng đăng nhập để tham gia bình luận
            </div>
        <?php endif; ?>

        <div class="comment-list">
            <?php if (empty($comments)): ?>
                <p class="empty-comment">Chưa có bình luận nào.</p>
            <?php else: ?>
                <?php foreach ($comments as $c): ?>
                    <div class="comment-item">
                        <div class="avatar">
                            <img src="<?= $c['avatar'] ?: 'assets/img/default-avatar.png' ?>">
                        </div>
                        <div class="comment-body">
                            <div class="comment-author">
                                <?= htmlspecialchars($c['username'] ?? 'Ẩn danh') ?>
                            </div>
                            <div class="comment-text">
                                <?= nl2br(htmlspecialchars($c['content'])) ?>
                            </div>
                            <div class="comment-time">
                                <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // Thanh cuộn mượt
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Hiệu ứng xuất hiện cho tập phim
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.episode-card');
            cards.forEach((card, index) => {
                card.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1}s both`;
            });
        });
    </script>

    <script src="assets/js/theme-switcher.js"></script>

</body>

</html>
