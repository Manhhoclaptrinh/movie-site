<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$movie_id = (int)($_POST['movie_id'] ?? 0);
$episode = isset($_POST['episode']) ? (int)$_POST['episode'] : null;
$time = (int)($_POST['current_time'] ?? 0);

if ($movie_id <= 0) exit;

$stmt = $conn->prepare("
    INSERT INTO watch_history (user_id, movie_id, episode_number, last_time)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        episode_number = VALUES(episode_number),
        last_time = VALUES(last_time)
");
$stmt->bind_param("iiii", $user_id, $movie_id, $episode, $time);
$stmt->execute();
