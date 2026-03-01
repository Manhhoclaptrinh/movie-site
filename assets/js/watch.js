const video = document.getElementById("videoPlayer");
if (video) {

    // ⏱ Lưu mỗi 10s
    setInterval(() => {
        if (!video.paused) {
            fetch("actions/save_history.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    movie_id: video.dataset.movie,
                    episode: video.dataset.episode,
                    time: Math.floor(video.currentTime)
                })
            });
        }
    }, 10000);
}
