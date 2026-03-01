    <?php
    require_once __DIR__ . "/../models/AdminModel.php";

    class LoginController {
        private $model;
        
        public function __construct($dbConnection) {
            $this->model = new AdminModel($dbConnection);
        }
        
        /**
         * Xử lý đăng nhập
         */
        public function handleLogin($username, $password) {
            try {
                $admin = $this->model->findByUsername($username);
                
                if (!$admin) {
                    return [
                        'success' => false,
                        'error' => 'Tài khoản không tồn tại hoặc đã bị vô hiệu hóa!'
                    ];
                }
                
                if (!password_verify($password, $admin['password'])) {
                    return [
                        'success' => false,
                        'error' => 'Sai mật khẩu!'
                    ];
                }
                
                // Đăng nhập thành công
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                
                // Cập nhật last login
                $this->model->updateLastLogin($admin['id']);
                
                return [
                    'success' => true,
                    'admin' => $admin
                ];
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'error' => 'Lỗi hệ thống: ' . $e->getMessage()
                ];
            }
        }
        
        /**
         * Kiểm tra đã đăng nhập chưa
         */
        public function isLoggedIn() {
            return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
        }
    }
    ?>
