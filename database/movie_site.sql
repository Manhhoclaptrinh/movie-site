-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th2 02, 2026 lúc 04:20 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `movie_site`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','moderator') DEFAULT 'admin',
  `status` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `full_name`, `avatar`, `role`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$qRJLKrB.OivOUIs2v6Sq2ONgdoLJQnT6YzWJarORsyCyNaIopb4Wi', 'admin@moviesite.com', 'Administrator', NULL, 'admin', 1, '2026-02-01 20:32:16', '2026-01-30 23:49:21', '2026-02-01 20:32:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `movie_id`, `image`, `status`, `sort_order`, `created_at`) VALUES
(1, 1, 'uploads/posters/han1.jpg', 1, 1, '2026-01-30 08:52:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Hàn Quốc', 'han-quoc', NULL, '2026-01-30 23:45:37'),
(2, 'Trung Quốc', 'trung-quoc', NULL, '2026-01-30 23:45:37'),
(3, 'US-UK', 'us-uk', NULL, '2026-01-30 23:45:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `movie_id`, `admin_id`, `content`, `created_at`, `status`) VALUES
(7, 1, 1, 'Phim này coi cuốn ghê 😍, nội dung nhẹ nhàng mà cảm xúc.', '2026-01-29 22:39:20', 1),
(8, 1, 1, 'Tập 3 với tập 4 xem mà nổi da gà luôn 😭', '2026-01-30 22:39:20', 1),
(9, 1, 1, 'Nhạc phim hay thật sự, ai coi cũng nên để ý phần OST nha.', '2026-01-31 00:39:20', 1),
(10, 1, 1, 'Mong ra thêm tập mới sớm sớm chứ chờ lâu quá 😢', '2026-01-31 12:39:20', 1),
(11, 1, 1, 'Diễn viên nữ đóng tự nhiên ghê, coi không bị giả.', '2026-01-31 17:39:20', 1),
(12, 1, 1, 'Xem ban đêm mà cuốn quá coi quên ngủ luôn 😆', '2026-01-31 21:39:20', 1),
(13, 1, 1, 'hay quá', '2026-01-31 22:39:38', 1),
(14, 1, 1, 'hay ghe', '2026-01-31 22:55:31', 1),
(15, 1, 1, 'hehe', '2026-02-01 00:42:50', 1),
(7, 1, 1, 'Phim này coi cuốn ghê 😍, nội dung nhẹ nhàng mà cảm xúc.', '2026-01-29 22:39:20', 1),
(8, 1, 1, 'Tập 3 với tập 4 xem mà nổi da gà luôn 😭', '2026-01-30 22:39:20', 1),
(9, 1, 1, 'Nhạc phim hay thật sự, ai coi cũng nên để ý phần OST nha.', '2026-01-31 00:39:20', 1),
(10, 1, 1, 'Mong ra thêm tập mới sớm sớm chứ chờ lâu quá 😢', '2026-01-31 12:39:20', 1),
(11, 1, 1, 'Diễn viên nữ đóng tự nhiên ghê, coi không bị giả.', '2026-01-31 17:39:20', 1),
(12, 1, 1, 'Xem ban đêm mà cuốn quá coi quên ngủ luôn 😆', '2026-01-31 21:39:20', 1),
(13, 1, 1, 'hay quá', '2026-01-31 22:39:38', 1),
(14, 1, 1, 'hay ghe', '2026-01-31 22:55:31', 1),
(15, 1, 1, 'hehe', '2026-02-01 00:42:50', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `created_at`) VALUES
(1, 'Hàn Quốc', 'KR', '2026-01-30 23:48:07'),
(2, 'Trung Quốc', 'CN', '2026-01-30 23:48:07'),
(3, 'US-UK', 'US', '2026-01-30 23:48:07'),
(4, 'Việt Nam', 'VN', '2026-01-30 23:48:07'),
(5, 'Nhật Bản', 'JP', '2026-01-30 23:48:07'),
(6, 'Thái Lan', 'TH', '2026-01-30 23:48:07'),
(7, 'Đài Loan', 'TW', '2026-01-30 23:48:07'),
(8, 'Ấn Độ', 'IN', '2026-01-30 23:48:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `episodes`
--

CREATE TABLE `episodes` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `episode_number` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'Thời lượng (phút)',
  `view_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `episodes`
--

INSERT INTO `episodes` (`id`, `movie_id`, `episode_number`, `title`, `video_url`, `duration`, `view_count`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'https://example.com/tinh-yeu-qua-canh-tap-1', NULL, 0, '2026-01-30 23:48:58', '2026-01-30 23:48:58'),
(2, 1, 2, NULL, 'https://example.com/tinh-yeu-qua-canh-tap-2', NULL, 0, '2026-01-30 23:48:58', '2026-01-30 23:48:58'),
(3, 3, 1, NULL, 'https://example.com/thu-thach-than-tuong-tap-1', NULL, 0, '2026-01-30 23:48:58', '2026-01-30 23:48:58'),
(4, 3, 2, NULL, 'https://example.com/thu-thach-than-tuong-tap-2', NULL, 0, '2026-01-30 23:48:58', '2026-01-30 23:48:58'),
(5, 5, 1, NULL, 'https://example.com/thieu-nien-ca-hanh-tap-1', NULL, 0, '2026-01-30 23:48:58', '2026-01-30 23:48:58'),
(6, 5, 2, NULL, 'https://example.com/thieu-nien-ca-hanh-tap-2', NULL, 0, '2026-01-30 23:48:58', '2026-01-30 23:48:58'),
(7, 7, 1, NULL, 'https://example.com/spartacus-ep-1', NULL, 0, '2026-01-30 23:48:58', '2026-01-30 23:48:58'),
(8, 7, 2, NULL, 'https://example.com/spartacus-ep-2', NULL, 0, '2026-01-30 23:48:58', '2026-01-30 23:48:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `phone_input` varchar(20) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `status` enum('success','failed') NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `phone_input`, `ip_address`, `browser`, `status`, `attempt_time`) VALUES
(1, 4, '0364336220', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', '2026-01-31 21:04:34'),
(2, NULL, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'failed', '2026-02-01 05:45:12'),
(3, NULL, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'failed', '2026-02-01 05:45:16'),
(4, NULL, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'failed', '2026-02-01 05:45:26'),
(5, NULL, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'failed', '2026-02-01 05:46:31'),
(6, NULL, 'Đoàn quang mạnh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'failed', '2026-02-01 05:54:05'),
(7, NULL, 'Đoàn quang mạnh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'failed', '2026-02-01 05:54:08'),
(8, 1, '0999999999', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'success', '2026-02-01 06:01:55'),
(9, 1, '0999999999', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', '2026-02-01 06:41:58'),
(10, NULL, 'Đoàn quang mạnh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'failed', '2026-02-01 06:53:50'),
(11, 5, '0338541310', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'success', '2026-02-01 07:04:31'),
(12, NULL, 'Đoàn quang mạnh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'failed', '2026-02-01 07:10:25'),
(13, 5, '0338541310', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'success', '2026-02-01 07:10:34'),
(14, 8, '0982009576', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', '2026-02-02 14:06:49'),
(15, 8, '0982009576', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', '2026-02-02 14:59:36'),
(16, 8, '0982009576', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', '2026-02-02 15:03:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `watched_time` int(11) DEFAULT 0,
  `duration` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `logs`
--

INSERT INTO `logs` (`id`, `movie_id`, `action`, `ip_address`, `created_at`, `watched_time`, `duration`) VALUES
(1, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 08:36:26', 0, 0),
(2, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 08:40:22', 0, 0),
(3, NULL, 'View movie: Spartacus', NULL, '2026-01-30 08:46:03', 0, 0),
(4, NULL, 'View movie: Thu Hút Mãnh Liệt', NULL, '2026-01-30 08:46:22', 0, 0),
(5, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 08:56:19', 0, 0),
(6, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 08:56:25', 0, 0),
(7, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 09:04:22', 0, 0),
(8, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-30 09:05:36', 0, 0),
(9, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 09:05:51', 0, 0),
(10, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 09:05:54', 0, 0),
(11, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 09:05:57', 0, 0),
(12, NULL, 'View movie: Thiếu Niên Ca Hành', NULL, '2026-01-30 09:06:11', 0, 0),
(13, NULL, 'View movie: Bệnh Viện Pitt', NULL, '2026-01-30 09:06:22', 0, 0),
(14, NULL, 'View movie: Spartacus', NULL, '2026-01-30 09:06:27', 0, 0),
(15, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-30 21:01:25', 0, 0),
(16, NULL, 'View movie: Thiếu Niên Ca Hành', NULL, '2026-01-30 21:02:30', 0, 0),
(17, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-30 21:02:58', 0, 0),
(18, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 21:03:14', 0, 0),
(19, NULL, 'View movie: Dòng Tộc Bridgerton', NULL, '2026-01-30 21:17:05', 0, 0),
(20, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 21:18:03', 0, 0),
(21, NULL, 'View movie: Bệnh Viện Pitt', NULL, '2026-01-30 21:21:30', 0, 0),
(22, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-30 21:33:09', 0, 0),
(23, NULL, 'View movie: Spartacus', NULL, '2026-01-30 21:35:15', 0, 0),
(24, NULL, 'View movie: Thiếu Niên Ca Hành', NULL, '2026-01-30 21:36:25', 0, 0),
(25, NULL, 'View movie: Spartacus', NULL, '2026-01-30 21:44:03', 0, 0),
(26, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 21:45:41', 0, 0),
(27, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 21:59:17', 0, 0),
(28, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 21:59:25', 0, 0),
(29, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:05:11', 0, 0),
(30, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:05:25', 0, 0),
(31, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:06:23', 0, 0),
(32, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:10:15', 0, 0),
(33, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:10:16', 0, 0),
(34, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:11:41', 0, 0),
(35, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:11:49', 0, 0),
(36, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:25:20', 0, 0),
(37, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:25:24', 0, 0),
(38, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:25:31', 0, 0),
(39, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:27:48', 0, 0),
(40, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:28:04', 0, 0),
(41, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:28:11', 0, 0),
(42, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:29:30', 0, 0),
(43, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:29:31', 0, 0),
(44, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:29:36', 0, 0),
(45, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:29:39', 0, 0),
(46, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:30:06', 0, 0),
(47, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:30:11', 0, 0),
(48, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:30:13', 0, 0),
(49, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:31:42', 0, 0),
(50, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:31:46', 0, 0),
(51, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:31:48', 0, 0),
(52, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:43:21', 0, 0),
(53, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:43:31', 0, 0),
(54, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:45:07', 0, 0),
(55, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:45:15', 0, 0),
(56, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:45:18', 0, 0),
(57, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:45:21', 0, 0),
(58, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-30 22:45:26', 0, 0),
(59, NULL, 'View movie: Thiếu Niên Ca Hành', NULL, '2026-01-30 22:45:30', 0, 0),
(60, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:45:34', 0, 0),
(61, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:46:41', 0, 0),
(62, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:47:01', 0, 0),
(63, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:47:12', 0, 0),
(64, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:47:37', 0, 0),
(65, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:47:44', 0, 0),
(66, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:48:24', 0, 0),
(67, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:48:27', 0, 0),
(68, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:48:46', 0, 0),
(69, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:48:50', 0, 0),
(70, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:49:10', 0, 0),
(71, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:49:17', 0, 0),
(72, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:49:21', 0, 0),
(73, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:49:23', 0, 0),
(74, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:49:35', 0, 0),
(75, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:49:39', 0, 0),
(76, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:49:44', 0, 0),
(77, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:49:49', 0, 0),
(78, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-30 22:49:56', 0, 0),
(79, NULL, 'View movie: Thiếu Niên Ca Hành', NULL, '2026-01-30 22:49:59', 0, 0),
(80, NULL, 'View movie: Thu Hút Mãnh Liệt', NULL, '2026-01-30 22:50:11', 0, 0),
(81, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:50:38', 0, 0),
(82, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:50:41', 0, 0),
(83, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:50:44', 0, 0),
(84, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:50:46', 0, 0),
(85, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:50:50', 0, 0),
(86, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:50:52', 0, 0),
(87, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-30 22:50:59', 0, 0),
(88, NULL, 'View movie: Spartacus', NULL, '2026-01-30 22:51:03', 0, 0),
(89, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:51:21', 0, 0),
(90, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 22:51:24', 0, 0),
(91, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-01-30 22:51:27', 0, 0),
(92, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:51:29', 0, 0),
(93, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-30 22:51:32', 0, 0),
(94, NULL, 'View movie: Dòng Tộc Bridgerton', NULL, '2026-01-30 22:51:47', 0, 0),
(95, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-30 22:52:23', 0, 0),
(96, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-30 23:16:13', 0, 0),
(97, NULL, 'View movie: Spartacus', NULL, '2026-01-31 00:20:45', 0, 0),
(98, NULL, 'View movie: Spartacus', NULL, '2026-01-31 00:22:18', 0, 0),
(99, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-01-31 00:22:28', 0, 0),
(100, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-31 00:22:42', 0, 0),
(101, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-01-31 01:07:11', 0, 0),
(102, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-31 01:36:39', 0, 0),
(103, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-31 02:10:35', 0, 0),
(104, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-01-31 05:11:04', 0, 0),
(105, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 20:32:38', 0, 0),
(106, NULL, 'View movie: Thử Thách Thần Tượng', NULL, '2026-02-01 20:37:40', 0, 0),
(107, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 20:44:49', 0, 0),
(108, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 20:44:53', 0, 0),
(109, NULL, 'View movie: Bệnh Viện Pitt', NULL, '2026-02-01 20:44:58', 0, 0),
(110, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-01 21:24:14', 0, 0),
(111, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-01 21:26:21', 0, 0),
(112, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:26:53', 0, 0),
(113, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-01 21:29:06', 0, 0),
(114, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-01 21:32:11', 0, 0),
(115, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:37:44', 0, 0),
(116, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:40:56', 0, 0),
(117, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-01 21:41:04', 0, 0),
(118, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-02-01 21:41:10', 0, 0),
(119, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-01 21:41:24', 0, 0),
(120, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:41:30', 0, 0),
(121, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:42:11', 0, 0),
(122, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:45:33', 0, 0),
(123, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:47:01', 0, 0),
(124, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-01 21:47:10', 0, 0),
(125, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:47:14', 0, 0),
(126, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:48:54', 0, 0),
(127, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-01 21:49:59', 0, 0),
(128, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-02-01 21:50:12', 0, 0),
(129, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-02-01 21:51:53', 0, 0),
(130, NULL, 'View movie: Cơn Say Mùa Xuân', NULL, '2026-02-01 21:53:00', 0, 0),
(131, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-02 13:52:57', 0, 0),
(132, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-02 13:53:32', 0, 0),
(133, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-02 13:54:52', 0, 0),
(134, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-02-02 13:56:40', 0, 0),
(135, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-02-02 13:57:31', 0, 0),
(136, NULL, 'View movie: Một Tình Yêu Bất Ngờ Đến', NULL, '2026-02-02 14:06:31', 0, 0),
(137, NULL, 'View movie: Liều Thuốc Cho Tình Yêu', NULL, '2026-02-02 15:02:55', 0, 0),
(138, NULL, 'View movie: Tình Yêu Quá Cảnh', NULL, '2026-02-02 15:17:35', 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `original_title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `cast` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'Thời lượng (phút)',
  `rating` decimal(3,1) DEFAULT 0.0,
  `quality` enum('HD','FullHD','CAM','4K') DEFAULT 'HD',
  `status` enum('upcoming','ongoing','completed') DEFAULT 'completed',
  `trailer_url` varchar(255) DEFAULT NULL,
  `is_series` tinyint(1) DEFAULT 0,
  `poster` varchar(255) NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `movies`
--

INSERT INTO `movies` (`id`, `title`, `original_title`, `slug`, `description`, `category_id`, `release_year`, `country_id`, `country`, `director`, `cast`, `duration`, `rating`, `quality`, `status`, `trailer_url`, `is_series`, `poster`, `banner`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Tình Yêu Quá Cảnh', NULL, 'tinh-yeu-qua-canh', 'Câu chuyện tình yêu lãng mạn vượt qua biên giới.', 1, 2024, 1, 'Hàn Quốc', 'Park Jin-pyo', 'Lee Jong-suk, Suzy Bae', 45, 8.5, 'FullHD', 'completed', NULL, 1, 'assets/img/han1.jpg', NULL, 70, '2026-01-30 08:19:07', '2026-02-02 15:17:35'),
(2, 'Cơn Say Mùa Xuân', NULL, 'con-say-mua-xuan', 'Mối quan hệ tình cảm nhẹ nhàng và sâu lắng.', 1, 2024, 1, 'Hàn Quốc', 'Lee Na-jung', 'Park Min-young, Seo Kang-joon', 50, 8.2, 'HD', 'completed', NULL, 1, 'assets/img/han2.jpg', NULL, 38, '2026-01-30 08:19:07', '2026-02-01 21:53:00'),
(3, 'Thử Thách Thần Tượng', NULL, 'thu-thach-than-tuong', 'Chương trình giải trí nổi tiếng của Hàn Quốc.', 1, 2023, 1, 'Hàn Quốc', 'Kim Tae-ho', 'Yoo Jae-suk, Jo Se-ho', 90, 9.0, 'FullHD', 'ongoing', NULL, 1, 'assets/img/han3.jpg', NULL, 804, '2026-01-30 08:19:07', '2026-02-01 20:37:40'),
(4, 'Một Tình Yêu Bất Ngờ Đến', NULL, 'mot-tinh-yeu-bat-ngo-den', 'Chuyện tình bất ngờ trong thập niên 90.', 2, 2023, 2, 'Trung Quốc', NULL, NULL, NULL, 0.0, 'HD', 'completed', NULL, 1, 'assets/img/cn1.jpg', NULL, 30, '2026-01-30 08:19:07', '2026-02-02 14:06:30'),
(5, 'Thiếu Niên Ca Hành', NULL, 'thieu-nien-ca-hanh', 'Hành trình trưởng thành của những thiếu niên anh hùng.', 2, 2022, 2, 'Trung Quốc', NULL, NULL, NULL, 0.0, 'HD', 'completed', NULL, 1, 'assets/img/cn2.jpg', NULL, 15, '2026-01-30 08:19:07', '2026-01-30 23:48:45'),
(6, 'Thu Hút Mãnh Liệt', NULL, 'thu-hut-manh-liet', 'Câu chuyện tình yêu đầy cảm xúc và thử thách.', 2, 2024, 2, 'Trung Quốc', NULL, NULL, NULL, 0.0, 'HD', 'completed', NULL, 1, 'assets/img/cn3.jpg', NULL, 26, '2026-01-30 08:19:07', '2026-01-30 23:48:45'),
(7, 'Spartacus', NULL, 'spartacus', 'Cuộc chiến sinh tồn của các võ sĩ giác đấu La Mã.', 3, 2010, 3, 'US-UK', NULL, NULL, NULL, 0.0, 'HD', 'completed', NULL, 1, 'assets/img/us1.jpg', NULL, 14, '2026-01-30 08:19:07', '2026-01-31 00:22:18'),
(8, 'Bệnh Viện Pitt', NULL, 'benh-vien-pitt', 'Những câu chuyện kịch tính trong bệnh viện.', 3, 2023, 3, 'US-UK', NULL, NULL, NULL, 0.0, 'HD', 'completed', NULL, 1, 'assets/img/us2.jpg', NULL, 7, '2026-01-30 08:19:07', '2026-02-01 20:44:58'),
(9, 'Dòng Tộc Bridgerton', NULL, 'dong-toc-bridgerton', 'Cuộc sống xa hoa của giới quý tộc Anh.', 3, 2022, 3, 'US-UK', NULL, NULL, NULL, 0.0, 'HD', 'completed', NULL, 1, 'assets/img/us3.jpg', NULL, 6, '2026-01-30 08:19:07', '2026-01-30 23:48:45'),
(10, 'Trò Chơi Kẻ Thao Túng', NULL, 'tr-ch-i-k-thao-t-ng', 'Kate Rafter về nhà sau biến cố chiến tranh kinh hoàng tại Iraq và nỗi đau mất mẹ. Trong lúc thu dọn đồ đạc của mẹ, cô dần tin rằng có điều gì đó kỳ lạ và đáng sợ đang xảy ra trong ngôi nhà bên cạnh', 3, 2026, NULL, 'UK', NULL, NULL, NULL, 0.0, 'HD', 'completed', NULL, 0, 'uploads/posters/1770005816_3824f892fc0d877ab24afdacc1d4264e.webp', NULL, 0, '2026-02-01 21:16:56', '2026-02-01 21:16:56'),
(11, 'Liều Thuốc Cho Tình Yêu', NULL, 'li-u-thu-c-cho-t-nh-y-u', 'Tại thị trấn Onjeong, nơi hai dòng họ lớn đã cắt đứt quan hệ suốt nhiều thập kỷ. Kong Ju A nỗ lực đối đầu với áp lực từ người mẹ nghiêm khắc để tuyên bố từ bỏ sự nghiệp y khoa ngay sau khi vừa nhận được giấy phép hành nghề. Cô quyết định quay trở lại với chiếc máy khâu và những bản thiết kế dang dở, vốn là năng khiếu thiên bẩm mà cô được thừa hưởng từ người ông vốn là bác sĩ phẫu thuật tài hoa. Yang Hyeon Bin là quản lý cấp cao của tập đoàn thời trang Taehan SNC, anh trở về quê hương để thực hiện một dự án nghiên cứu thị trường mới. Hai đứa con đến từ hai dòng họ mâu thuẫn suốt nhiều năm hứa hẹn mang lại những thước phim gia đình, đời thường hấp dẫn cho dòng phim cuối tuần.', 1, 2026, NULL, 'Hàn Quốc', NULL, NULL, NULL, 0.0, 'HD', 'completed', NULL, 1, 'uploads/posters/1770005875_d57fb1d8dac8bda511094bff7ccc926e.webp', NULL, 10, '2026-02-01 21:17:55', '2026-02-02 15:02:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movie_books`
--

CREATE TABLE `movie_books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn ảnh bìa',
  `author` varchar(255) DEFAULT NULL COMMENT 'Tác giả',
  `publish_year` int(4) DEFAULT NULL COMMENT 'Năm xuất bản',
  `language` varchar(100) DEFAULT 'Tiếng Việt',
  `pdf_file` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn file PDF',
  `movie_id` int(11) DEFAULT NULL COMMENT 'Liên kết với phim',
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movie_tags`
--

CREATE TABLE `movie_tags` (
  `movie_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `movie_tags`
--

INSERT INTO `movie_tags` (`movie_id`, `tag_id`) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(4, 1),
(4, 7),
(5, 8),
(5, 9),
(6, 2),
(6, 10),
(7, 9),
(7, 11),
(8, 10),
(8, 12),
(9, 1),
(9, 13);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ratings`
--

INSERT INTO `ratings` (`id`, `movie_id`, `admin_id`, `rating`, `created_at`) VALUES
(10, 1, 1, 3, '2026-02-01 21:51:23'),
(10, 1, 1, 3, '2026-02-01 21:51:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Tình cảm', 'tình-cảm', '2026-01-30 23:46:28'),
(2, 'Lãng mạn', 'lãng-mạn', '2026-01-30 23:46:28'),
(3, 'Tình yêu', 'tình-yeu', '2026-01-30 23:46:28'),
(4, 'Gia đình', 'gia-dình', '2026-01-30 23:46:28'),
(5, 'Giải trí', 'giải-trí', '2026-01-30 23:46:28'),
(6, 'Reality Show', 'reality-show', '2026-01-30 23:46:28'),
(7, 'Thanh xuân', 'thanh-xuan', '2026-01-30 23:46:28'),
(8, 'Kiếm hiệp', 'kiếm-hiệp', '2026-01-30 23:46:28'),
(9, 'Hành động', 'hành-dộng', '2026-01-30 23:46:28'),
(10, 'Drama', 'drama', '2026-01-30 23:46:28'),
(11, 'Lịch sử', 'lịch-sử', '2026-01-30 23:46:28'),
(12, 'Y khoa', 'y-khoa', '2026-01-30 23:46:28'),
(13, 'Quý tộc', 'quý-tộc', '2026-01-30 23:46:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','moderator','user') DEFAULT 'user',
  `avatar` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `phone`, `password`, `full_name`, `role`, `avatar`, `created_at`) VALUES
(1, '0999999999', '$2y$10$VHY8IOfn3Rl7vqU/Mg2XwutzS16C2EB3lniIrCRylGrRgme93dVFq', 'Tùng đẹp zai (Admin)', 'admin', 'default.png', '2026-01-31 20:50:30'),
(2, '0962910493', '$2y$10$wL4gqQ6A8.W5y.C.k1yCQ.uY.I.uY.I.uY.I.uY.I.uY.I', 'Ngô Văn Tùng (Mod)', 'moderator', 'default.png', '2026-01-31 20:50:30'),
(3, '0123456789', '$2y$10$wL4gqQ6A8.W5y.C.k1yCQ.uY.I.uY.I.uY.I.uY.I.uY.I', 'Khách Xem Phim', 'user', 'default.png', '2026-01-31 20:50:30'),
(4, '0364336220', '$2y$10$8DFk.B9hnOUL.EXpkpg2IerEVjvjgCczQuY9GHDmBpXKpI4H.X2Y2', 'Hà Thị Ánh', 'user', 'default.png', '2026-01-31 21:04:24'),
(5, '0338541310', '$2y$10$IUppJ9WiGRP3MgWH58PxrOqtWDbj.ZSg1ikrbwH9H3u2HCJHwKnki', 'Đoàn quang mạnh', 'user', 'default.png', '2026-02-01 05:53:55'),
(6, '0111111111111', '$2y$10$CY0wbpzAWu8GH5Jq5M4DBupjLIhiI7eS58kIorrAaG8JpPABgKRCa', 'Đoàn quang mạnh', 'user', 'default.png', '2026-02-01 06:23:03'),
(7, '000000000', '$2y$10$Gii.xHKrCBgycZCeXoiQee5SAJ/q10bty2s0hl.Nk4AvtVAJeVYSW', 'Đoàn quang mạnh', 'user', 'default.png', '2026-02-01 06:48:10'),
(8, '0982009576', '$2y$10$hi2B90/WDbMCg/ZRYIzQZOAY75kytmAnq4j2wlKL.y0cdycrsRqNG', 'Vũ Hồng Việt', 'user', 'default.png', '2026-02-02 13:52:17');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_banner_movie` (`movie_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `movie_id` (`movie_id`,`episode_number`);

--
-- Chỉ mục cho bảng `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_movie` (`movie_id`);

--
-- Chỉ mục cho bảng `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_movies_category` (`category_id`),
  ADD KEY `idx_movies_views` (`views`),
  ADD KEY `idx_movies_year` (`release_year`),
  ADD KEY `idx_movies_country` (`country`),
  ADD KEY `fk_movies_country` (`country_id`),
  ADD KEY `idx_movies_title` (`title`),
  ADD KEY `idx_movies_status` (`status`),
  ADD KEY `idx_movies_rating` (`rating`);

--
-- Chỉ mục cho bảng `movie_books`
--
ALTER TABLE `movie_books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `idx_title` (`title`),
  ADD KEY `idx_author` (`author`),
  ADD KEY `idx_publish_year` (`publish_year`);

--
-- Chỉ mục cho bảng `movie_tags`
--
ALTER TABLE `movie_tags`
  ADD PRIMARY KEY (`movie_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `episodes`
--
ALTER TABLE `episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT cho bảng `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `movie_books`
--
ALTER TABLE `movie_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `fk_banner_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `episodes`
--
ALTER TABLE `episodes`
  ADD CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`);

--
-- Các ràng buộc cho bảng `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_log_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_movies_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_movies_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `movie_books`
--
ALTER TABLE `movie_books`
  ADD CONSTRAINT `movie_books_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `movie_tags`
--
ALTER TABLE `movie_tags`
  ADD CONSTRAINT `movie_tags_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movie_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
