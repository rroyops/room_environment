<?php
/**
 * Room No. 320 Environment - Configuration File
 * Initializes sessions, database configurations, and global utility helpers.
 */

// Secure session configuration
if (session_status() === PHP_SESSION_NONE) {
    // Configure session cookie lifetime & security rules
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    // Secure attribute if using HTTPS, otherwise optional for local testing
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'room320_environment');

// App Settings
define('SITE_NAME', 'Room No. 320 Environment');
define('BASE_URL', 'http://localhost/Room-No-320-Environment/');

// XSS Protection Helper
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// CSRF Token Generation
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Token Validation
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if logged in user is admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Redirection helper
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Alert messaging helper (stores flash message in session)
function set_flash_message($type, $message) {
    $_SESSION['flash_msg'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'text' => $message
    ];
}

// Render flash message
function display_flash_message() {
    if (isset($_SESSION['flash_msg'])) {
        $msg = $_SESSION['flash_msg'];
        unset($_SESSION['flash_msg']);
        echo '<div class="alert alert-' . $msg['type'] . ' alert-dismissible fade show" role="alert" id="flash-alert">' .
             $msg['text'] .
             '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' .
             '</div>';
    }
}
?>
