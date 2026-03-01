<?php
class SessionModel {
    /**
     * Xóa tất cả session variables
     */
    public function clearSessionData() {
        $_SESSION = array();
    }
    
    /**
     * Xóa session cookie
     */
    public function clearSessionCookie() {
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }
    
    /**
     * Hủy session
     */
    public function destroySession() {
        session_destroy();
    }
}
?>
