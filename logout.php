<?php
// Initialize the session.
// If you are using session_name("something"), don't forget it now!
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
if (isset($_COOKIE['login-user']) && isset($_COOKIE['login-token'])) {
    setcookie('login-user', '', time()-7000000);
    setcookie('login-token', '', time()-7000000);
}
// Finally, destroy the session.
session_destroy();
header('location: index');
exit;
