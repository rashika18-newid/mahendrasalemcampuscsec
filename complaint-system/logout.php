<?php
// Secure logout: clear session data, destroy session, expire cookie.
// No DB needed — logout must work even if MySQL is down.
if (!defined('BASE_URL')) define('BASE_URL', '');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/includes/functions.php';

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

// Fresh session just for the goodbye toast on the landing page.
session_start();
$_SESSION['flash'] = ['message' => 'You have been logged out safely.', 'type' => 'info'];
// Landing page shows its own UI; pass flash via session and redirect to login for simplicity.
redirect(BASE_URL . '/login.php');
