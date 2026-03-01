<?php
$conn = new mysqli("localhost", "root", "", "movie_site", 3306);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

// Set charset UTF-8
$conn->set_charset("utf8mb4");

function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $conn->real_escape_string($data);
}

//Hàm tạo slug từ tiêu đề tiếng Việt
function create_slug($string) {
    // Chuyển về chữ thường
    $string = mb_strtolower($string, 'UTF-8');
    
    // Chuyển đổi ký tự có dấu tiếng Việt
    $vietnamese = array(
        'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
        'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
        'ì', 'í', 'ị', 'ỉ', 'ĩ',
        'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
        'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
        'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
        'đ'
    );
    
    $replacement = array(
        'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
        'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
        'i', 'i', 'i', 'i', 'i',
        'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
        'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
        'y', 'y', 'y', 'y', 'y',
        'd'
    );
    
    $string = str_replace($vietnamese, $replacement, $string);
    
    // Xóa ký tự đặc biệt
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    
    // Xóa khoảng trắng thừa
    $string = preg_replace('/\s+/', ' ', $string);
    
    // Thay khoảng trắng bằng dấu gạch ngang
    $string = str_replace(' ', '-', $string);
    
    // Xóa dấu gạch ngang thừa
    $string = preg_replace('/-+/', '-', $string);
    
    // Xóa dấu gạch ngang ở đầu và cuối
    $string = trim($string, '-');
    
    return $string;
}

//Hàm upload file (poster, banner)

function upload_file($file, $target_dir = '../uploads/posters/') {
    // Tạo thư mục nếu chưa có
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Kiểm tra file
    if (!isset($file) || $file['error'] != 0) {
        return array('success' => false, 'message' => 'Lỗi upload file');
    }
    
    // Kiểm tra kích thước file (max 5MB)
    if ($file['size'] > 5242880) {
        return array('success' => false, 'message' => 'File quá lớn (max 5MB)');
    }
    
    // Kiểm tra loại file
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_types)) {
        return array('success' => false, 'message' => 'Chỉ cho phép file ảnh (jpg, png, gif, webp)');
    }
    
    // Tạo tên file mới (tránh trùng)
    $new_filename = uniqid() . '_' . time() . '.' . $file_ext;
    $target_file = $target_dir . $new_filename;
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return array('success' => true, 'filename' => $new_filename, 'path' => $target_file);
    }
    
    return array('success' => false, 'message' => 'Lỗi khi lưu file');
}

//Hàm xóa file
function delete_file($filename, $target_dir = '../uploads/posters/') {
    if (empty($filename)) {
        return false;
    }
    
    $file_path = $target_dir . $filename;
    if (file_exists($file_path)) {
        return unlink($file_path);
    }
    return false;
}

//Hàm format thời gian đã trôi qua

function time_elapsed_string($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    if ($diff->y > 0) {
        return $diff->y . ' năm trước';
    }
    if ($diff->m > 0) {
        return $diff->m . ' tháng trước';
    }
    if ($diff->d > 0) {
        return $diff->d . ' ngày trước';
    }
    if ($diff->h > 0) {
        return $diff->h . ' giờ trước';
    }
    if ($diff->i > 0) {
        return $diff->i . ' phút trước';
    }
    return 'Vừa xong';
}

//Hàm format số lượt xem

function format_views($views) {
    if ($views >= 1000000) {
        return round($views / 1000000, 1) . 'M';
    } elseif ($views >= 1000) {
        return round($views / 1000, 1) . 'K';
    }
    return $views;
}

//Hàm format thời lượng phim (phút -> giờ:phút)
function format_duration($minutes) {
    if ($minutes < 60) {
        return $minutes . ' phút';
    }
    
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    
    if ($mins > 0) {
        return $hours . ' giờ ' . $mins . ' phút';
    }
    return $hours . ' giờ';
}

//Hàm ghi log xem phim

function log_view($movie_id, $action = 'view', $ip_address = null) {
    global $conn;
    
    if ($ip_address === null) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    $stmt = $conn->prepare("INSERT INTO logs (movie_id, action, ip_address) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $movie_id, $action, $ip_address);
    $stmt->execute();
    $stmt->close();
    
    // Cập nhật lượt xem
    $update_stmt = $conn->prepare("UPDATE movies SET views = views + 1 WHERE id = ?");
    $update_stmt->bind_param("i", $movie_id);
    $update_stmt->execute();
    $update_stmt->close();
}

//Hàm lấy phim ngẫu nhiên
function get_random_movies($limit = 6, $exclude_id = null) {
    global $conn;
    
    $sql = "SELECT * FROM movies";
    if ($exclude_id) {
        $sql .= " WHERE id != " . intval($exclude_id);
    }
    $sql .= " ORDER BY RAND() LIMIT " . intval($limit);
    
    return $conn->query($sql);
}

//Hàm lấy phim hot (nhiều lượt xem nhất)
function get_hot_movies($limit = 10) {
    global $conn;
    
    $sql = "SELECT m.*, c.name as category_name 
            FROM movies m 
            LEFT JOIN categories c ON m.category_id = c.id 
            ORDER BY m.views DESC 
            LIMIT " . intval($limit);
    
    return $conn->query($sql);
}

//Hàm lấy phim mới nhất
function get_latest_movies($limit = 10) {
    global $conn;
    
    $sql = "SELECT m.*, c.name as category_name 
            FROM movies m 
            LEFT JOIN categories c ON m.category_id = c.id 
            ORDER BY m.created_at DESC 
            LIMIT " . intval($limit);
    
    return $conn->query($sql);
}

//Hàm kiểm tra slug đã tồn tại chưa
function slug_exists($slug, $table = 'movies', $exclude_id = null) {
    global $conn;
    
    $sql = "SELECT id FROM $table WHERE slug = ?";
    if ($exclude_id) {
        $sql .= " AND id != ?";
    }
    
    $stmt = $conn->prepare($sql);
    if ($exclude_id) {
        $stmt->bind_param("si", $slug, $exclude_id);
    } else {
        $stmt->bind_param("s", $slug);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    
    return $exists;
}

//Hàm tạo slug unique
function generate_unique_slug($title, $table = 'movies', $exclude_id = null) {
    $slug = create_slug($title);
    $original_slug = $slug;
    $counter = 1;
    
    while (slug_exists($slug, $table, $exclude_id)) {
        $slug = $original_slug . '-' . $counter;
        $counter++;
    }
    
    return $slug;
}

//Hàm phân trang
function paginate($total_items, $items_per_page, $current_page) {
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $items_per_page;
    
    return array(
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'limit' => $items_per_page
    );
}

//Hàm redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

//àm hiển thị thông báo flash
function set_flash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function get_flash($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');
?>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "movie_site"; 
$port = 3306;         

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Lỗi kết nối CSDL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
date_default_timezone_set("Asia/Ho_Chi_Minh");

?>
