<?php
/**
 * PLAYER.PHP - Fixed
 * - Hỗ trợ cả video local (uploads/...) và URL ngoài (https://...)
 * - Fix tên biến $episode vs $currentEpisode
 * - Resume xem tiếp
 */

// =======================
// FIX: Thống nhất tên biến
// watch/index.php dùng $currentEpisode, watch/watch.php dùng $episode
// =======================
if (!isset($episode) && isset($currentEpisode)) {
    $episode = $currentEpisode;
}
if (!isset($episode_number)) {
    $episode_number = $episode['episode_number'] ?? $ep ?? 1;
}

// =======================
// XÁC ĐỊNH VIDEO URL
// =======================
$video_url = '';

if (!empty($episode['video_url'])) {
    $video_url = $episode['video_url'];
} elseif (!empty($movie['video_url'])) {
    $video_url = $movie['video_url'];
}

// Phân loại: URL ngoài hay file local
$is_external = str_starts_with($video_url, 'http://') || str_starts_with($video_url, 'https://');
$is_local    = !$is_external && $video_url !== '';

// =======================
// RESUME – LẤY LỊCH SỬ XEM
// =======================
$last_time = 0;

if (isset($_SESSION['user_id']) && isset($conn)) {
    $stmt = $conn->prepare("
        SELECT last_time FROM watch_history
        WHERE user_id = ? AND movie_id = ? AND episode_number = ?
        LIMIT 1
    ");
    if ($stmt) {
        $stmt->bind_param("iii", $_SESSION['user_id'], $movie['id'], $episode_number);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $last_time = (int)($row['last_time'] ?? 0);
        $stmt->close();
    }
}
?>

<!-- ===================== -->
<!-- CSS PLAYER -->
<!-- ===================== -->
<style>
.player-wrapper {
    margin-top: 24px;
    padding: 16px;
    background: linear-gradient(180deg, #141414, #0b0b0b);
    border-radius: 22px;
    box-shadow: 0 20px 50px rgba(0,0,0,.8);
}

.player-frame {
    width: 100%;
    aspect-ratio: 16/9;
    min-height: 320px;
    max-height: 820px;
    background: #000;
    border-radius: 18px;
    overflow: hidden;
    position: relative;
}

.player-frame video,
.player-frame iframe {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 18px;
    background: #000;
}

.player-frame video {
    object-fit: contain;
}

.no-video {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    min-height: 320px;
    color: #aaa;
    font-size: 16px;
    gap: 12px;
}

.no-video span { font-size: 48px; }

@media (max-width: 768px) {
    .player-frame { min-height: 220px; }
}
</style>

<!-- ===================== -->
<!-- PLAYER HTML -->
<!-- ===================== -->
<div class="player-wrapper">
    <div class="player-frame">

        <?php if ($video_url === ''): ?>
            <!-- Chưa có video -->
            <div class="no-video">
                <span>🎬</span>
                <p>Tập này chưa có video. Vui lòng chờ cập nhật.</p>
            </div>

        <?php elseif ($is_local): ?>
            <!-- Video local: uploads/videos/... -->
            <video
                id="videoPlayer"
                controls
                preload="metadata"
                style="width:100%;height:100%;background:#000;object-fit:contain"
            >
                <source src="/movie-site/<?= htmlspecialchars($video_url) ?>" type="video/mp4">
                Trình duyệt không hỗ trợ video.
            </video>

        <?php elseif ($is_external): ?>
            <!-- URL ngoài: dùng iframe (hỗ trợ HLS, embed link) -->
            <iframe
                src="<?= htmlspecialchars($video_url) ?>"
                allowfullscreen
                allow="autoplay; encrypted-media; picture-in-picture"
                referrerpolicy="no-referrer"
            ></iframe>
            <p style="color:#f87171;font-size:13px;margin-top:8px;text-align:center">
                ⚠️ Link video này là demo (example.com). Cần thay bằng link thật trong DB.
            </p>

        <?php endif; ?>

    </div>
</div>

<!-- ===================== -->
<!-- SCRIPT RESUME + SAVE (chỉ cho video local) -->
<!-- ===================== -->
<?php if ($is_local): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const video = document.getElementById("videoPlayer");
    if (!video) return;

    /* RESUME */
    <?php if ($last_time > 5): ?>
    video.addEventListener("loadedmetadata", function () {
        video.currentTime = <?= $last_time ?>;
    });
    <?php endif; ?>

    /* LƯU LỊCH SỬ MỖI 5 GIÂY */
    let saveInterval = null;

    function saveProgress() {
        if (!video.duration) return;
        fetch("actions/save_history.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                movie_id:       <?= (int)$movie['id'] ?>,
                episode_number: <?= (int)$episode_number ?>,
                last_time:      Math.floor(video.currentTime),
                duration:       Math.floor(video.duration)
            })
        }).catch(e => console.warn("Save error:", e));
    }

    video.addEventListener("play",  () => { saveInterval = saveInterval || setInterval(saveProgress, 5000); });
    video.addEventListener("pause", saveProgress);
    window.addEventListener("beforeunload", saveProgress);

    /* DOUBLE CLICK TUA 10s */
    let lastTap = 0;
    video.addEventListener("click", function (e) {
        const now = Date.now();
        if (now - lastTap < 300) {
            const x    = e.clientX - video.getBoundingClientRect().left;
            const half = video.getBoundingClientRect().width / 2;
            video.currentTime = x < half
                ? Math.max(0, video.currentTime - 10)
                : Math.min(video.duration, video.currentTime + 10);
        }
        lastTap = now;
    });
});
</script>
<?php endif; ?>
