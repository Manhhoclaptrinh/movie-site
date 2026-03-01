<div class="movie-info-box">

    <h1 class="movie-title">
        <?= htmlspecialchars($movie['title']) ?>
    </h1>

    <div class="movie-meta">
        <span>📅 <?= $movie['release_year'] ?? 'N/A' ?></span>
        <span>🌍 <?= $movie['country'] ?? 'N/A' ?></span>
        <span>👁️ <?= number_format($movie['views']) ?> lượt xem</span>
    </div>

    <div class="movie-description">
        <?= nl2br(htmlspecialchars($movie['description'])) ?>
    </div>

</div>

<style>
/* ===== MOVIE INFO – DARK PREMIUM ===== */
.movie-info-box {
    margin-top: 26px;
    padding: 28px 30px;
    background: linear-gradient(
        180deg,
        rgba(20,20,20,.95),
        rgba(10,10,10,.95)
    );
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0,0,0,.6);
    border: 1px solid rgba(255,255,255,.05);
}

/* TITLE */
.movie-title {
    font-size: 32px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 14px;
    letter-spacing: .3px;
}

/* META */
.movie-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 18px;
}

.movie-meta span {
    background: rgba(255,255,255,.06);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 14px;
    color: #eee;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* DESCRIPTION */
.movie-description {
    color: #d1d1d1;
    font-size: 15.5px;
    line-height: 1.8;
    max-width: 900px;
}

</style>
