<?php
/**
 * Room No. 320 Environment - Secure User Logout
 * Clear session variables, destroys session handlers, clears cookies,
 * and securely redirects back to the index interface.
 */
require_once __DIR__ . '/includes/config.php';

// Unset all session variables
$_SESSION = [];

// Destroy session cookie if set
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Redirect with a success notification if possible
// Since session is destroyed, we can pass via query param or simple redirect
header("Location: index.php");
exit();
?>
