document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById("videoPlayer");
    if (!video) return;

    // ❌ Chặn fullscreen mặc định khi dblclick
    video.addEventListener("dblclick", (e) => {
        e.preventDefault();
        e.stopPropagation();

        const rect = video.getBoundingClientRect();
        const x = e.clientX - rect.left;

        if (x < rect.width / 2) {
            // ⏪ lùi 10s
            video.currentTime = Math.max(0, video.currentTime - 10);
        } else {
            // ⏩ tiến 10s
            video.currentTime = Math.min(video.duration, video.currentTime + 10);
        }
    });
});
