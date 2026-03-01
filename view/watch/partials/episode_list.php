<div class="episodes-section">
    <h3 class="episodes-title">📺 Danh sách tập</h3>

    <div class="episodes-grid">
        <?php
        $stmt = $conn->prepare("
            SELECT 
                e.episode_number,
                e.video_url,
                h.last_time
            FROM episodes e
            LEFT JOIN watch_history h 
                ON h.movie_id = e.movie_id
                AND h.episode_number = e.episode_number
                AND h.user_id = ?
            WHERE e.movie_id = ?
            ORDER BY e.episode_number
        ");
        $stmt->bind_param("ii", $user_id, $movie['id']);
        $stmt->execute();
        $list = $stmt->get_result();

        while ($e = $list->fetch_assoc()):
            $isActive = ($e['episode_number'] == $ep);
            $hasVideo = !empty($e['video_url']);
            $isWatched = !empty($e['last_time']);
        ?>
            <?php if ($hasVideo): ?>
                <a
                    href="watch.php?slug=<?= urlencode($movie['slug']) ?>&ep=<?= (int)$e['episode_number'] ?>"
                    class="episode-btn 
                        <?= $isActive ? 'active' : '' ?> 
                        <?= $isWatched ? 'watched' : '' ?>"
                    title="<?= $isWatched ? 'Đã xem' : 'Chưa xem' ?>"
                >
                    <?= $e['episode_number'] ?>
                </a>
            <?php else: ?>
                <span class="episode-btn disabled" title="Chưa có video">
                    <?= $e['episode_number'] ?>
                </span>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</div>
