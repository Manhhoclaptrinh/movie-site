<?php
class UserModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    /**
     * Lấy thông tin user theo ID
     */
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Xóa user theo ID
     */
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Lấy tất cả users
     */
    public function getAllUsers() {
        $result = $this->conn->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }
    
    /**
     * Cập nhật thông tin user (không đổi password)
     */
    public function updateUser($id, $data) {
        $stmt = $this->conn->prepare("UPDATE users SET full_name=?, phone=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $data['full_name'], $data['phone'], $data['role'], $id);
        return $stmt->execute();
    }
    
    /**
     * Cập nhật thông tin user (có đổi password)
     */
    public function updateUserWithPassword($id, $data) {
        $stmt = $this->conn->prepare("UPDATE users SET full_name=?, phone=?, role=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $data['full_name'], $data['phone'], $data['role'], $data['password'], $id);
        return $stmt->execute();
    }
    
    /**
     * Lấy tất cả users (trả về mysqli_result để dùng với fetch_assoc)
     */
    public function getAllUsersResult() {
        return $this->conn->query("SELECT * FROM users ORDER BY id DESC");
    }
    
    /**
     * Đếm tổng số users
     */
    public function countAllUsers() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users");
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Đếm users theo role
     */
    public function countUsersByRole($role) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = ?");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'];
    }
    
    /**
     * Tìm kiếm users theo từ khóa (tên hoặc số điện thoại)
     */
    public function searchUsers($keyword) {
        $searchTerm = "%{$keyword}%";
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE full_name LIKE ? OR phone LIKE ? 
            ORDER BY created_at DESC
        ");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $stmt->close();
        return $users;
    }
    
    /**
     * Kiểm tra số điện thoại đã tồn tại chưa (trừ user hiện tại)
     */
    public function isPhoneExist($phone, $excludeUserId = null) {
        if ($excludeUserId) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE phone = ? AND id != ?");
            $stmt->bind_param("si", $phone, $excludeUserId);
        } else {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE phone = ?");
            $stmt->bind_param("s", $phone);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] > 0;
    }
    
    /**
     * Lấy danh sách users với phân trang
     */
    public function getUsersWithPagination($limit, $offset) {
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $stmt->close();
        return $users;
    }
        
    /**
     * Xóa tất cả session variables
     */
    public function clearSessionData() {
        $_SESSION = array();
    }
    
    /**
     * Hủy session hiện tại
     */
    public function destroySession() {
        session_destroy();
    }
    
    /**
     * Xóa cookie ghi nhớ đăng nhập
     */
    public function clearRememberCookie() {
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, '/');
        }
    }

    /**
     * Kiểm tra số điện thoại đã tồn tại chưa
     */
    public function checkPhoneExists($phone) {
        $check = $this->conn->prepare("SELECT id FROM users WHERE phone = ?");
        $check->bind_param("s", $phone);
        $check->execute();
        $check->store_result();
        
        return $check->num_rows > 0;
    }

    /**
     * Tạo tài khoản mới
     */
    public function createUser($full_name, $phone, $password, $role = 'user') {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (full_name, phone, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $phone, $hashed_password, $role);
        
        return $stmt->execute();
    }
}
?>
