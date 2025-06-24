<?php
// app/config.php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'scholarships_for_me');

// Application base URL (useful for redirects and asset loading)
define('BASE_URL', 'http://localhost/scholarships-for-me/');

// Other configurations
define('APP_NAME', 'Scholarships for Me');
define('DEBUG_MODE', true); // Set to false in production

// Email configuration (for password resets, email verification, etc.)
define('MAIL_HOST', 'smtp.your-email-provider.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your_email@example.com');
define('MAIL_PASSWORD', 'your_email_password');
define('MAIL_FROM_EMAIL', 'noreply@example.com');
define('MAIL_FROM_NAME', 'Scholarships for Me');

// Session configuration
ini_set('session.gc_maxlifetime', 1440); // 24 minutes
session_set_cookie_params(1440); // 24 minutes
session_start();

// Error reporting
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Function to get a database connection
function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Basic utility functions (can be moved to a separate file later)
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

?>