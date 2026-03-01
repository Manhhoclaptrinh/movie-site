<?php
// header.php – Header cho trang watch
?>
<header class="watch-header">
    <div class="header-container">

        <!-- LOGO -->
        <div class="logo">
            <a href="/movie-site/index.php">
                <span class="logo-icon">🎬</span>
                <span class="logo-text">MovieSite</span>
            </a>
        </div>

        <!-- MENU -->
        <nav class="main-nav">
            <a href="/movie-site/index.php">Trang chủ</a>
            <a href="/movie-site/movies.php">Phim lẻ</a>
            <a href="/movie-site/movies.php?type=series">Phim bộ</a>
            <a href="/movie-site/search.php">Tìm kiếm</a>
        </nav>

        <!-- USER -->
        <div class="user-area">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="welcome">
                    👋 <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
                </span>
                <a href="/movie-site/view/quanly_user/logout.php" class="btn btn-ghost">Đăng xuất</a>
            <?php else: ?>
                <a href="/movie-site/view/quanly_user/login.php" class="btn btn-primary">Đăng nhập</a>
            <?php endif; ?>
        </div>

    </div>
</header>

<style>
/* ===== HEADER NETFLIX STYLE ===== */
.watch-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: linear-gradient(180deg, rgba(0,0,0,.95), rgba(0,0,0,.85));
    backdrop-filter: blur(6px);
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.header-container {
    max-width: 1400px;
    margin: auto;
    padding: 18px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* LOGO */
.logo a {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 22px;
    font-weight: 700;
    text-decoration: none;
}

.logo-icon {
    font-size: 22px;
}

.logo-text {
    color: #e50914;
    letter-spacing: .5px;
}

/* MENU */
.main-nav {
    display: flex;
    gap: 26px;
}

.main-nav a {
    color: #ddd;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    position: relative;
    padding-bottom: 4px;
    transition: color .25s ease;
}

.main-nav a::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 2px;
    background: #e50914;
    transition: width .25s ease;
}

.main-nav a:hover {
    color: #fff;
}

.main-nav a:hover::after {
    width: 100%;
}

/* USER */
.user-area {
    display: flex;
    align-items: center;
    gap: 14px;
}

.welcome {
    color: #bbb;
    font-size: 14px;
}

/* BUTTON */
.btn {
    padding: 7px 18px;
    border-radius: 22px;
    font-size: 14px;
    text-decoration: none;
    font-weight: 500;
    transition: all .25s ease;
}

.btn-primary {
    background: #e50914;
    color: #fff;
}

.btn-primary:hover {
    background: #f6121d;
    transform: translateY(-1px);
}

.btn-ghost {
    background: rgba(255,255,255,.08);
    color: #fff;
}

.btn-ghost:hover {
    background: rgba(255,255,255,.15);
}
</style>
