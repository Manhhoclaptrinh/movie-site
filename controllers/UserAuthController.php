    <?php
    //Tùng's Code
    require_once __DIR__ . "/../models/UserAuthModel.php";

    class UserAuthController {
        private $model;
        
        public function __construct($dbConnection) {
            $this->model = new UserAuthModel($dbConnection);
        }
        
        /**
         * Xử lý đăng nhập
         */
        public function handleLogin($phone, $password) {
            $phone = trim($phone);
            
            // Tìm user
            $user = $this->model->findUserByPhone($phone);
            
            if (!$user) {
                // Ghi log thất bại
                $this->model->writeLoginLog($phone, 'Đăng nhập thất bại (Số điện thoại không tồn tại)');
                
                return [
                    'success' => false,
                    'message' => 'Sai số điện thoại hoặc mật khẩu!'
                ];
            }
            
            // Kiểm tra mật khẩu
            if (!password_verify($password, $user['password'])) {
                // Ghi log thất bại
                $this->model->writeLoginLog($phone, 'Đăng nhập thất bại (Sai mật khẩu)');
                
                return [
                    'success' => false,
                    'message' => 'Sai số điện thoại hoặc mật khẩu!'
                ];
            }
            
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            // Ghi log thành công
            $this->model->writeLoginLog($phone, 'Đăng nhập thành công');
            
            return [
                'success' => true,
                'message' => 'Đang vào trang chủ...',
                'user' => $user
            ];
        }
        
        /**
         * Kiểm tra đã đăng nhập chưa
         */
        public function isLoggedIn() {
            return isset($_SESSION['user_id']);
        }
    }
    ?>
