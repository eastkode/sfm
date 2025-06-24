<?php
// app/functions.php

// Ensure config is loaded for database connection and BASE_URL
require_once __DIR__ . '/config.php';

/**
 * Redirects to a specified URL.
 * @param string $url The URL to redirect to.
 */
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

/**
 * Sanitizes input data to prevent XSS attacks.
 * @param string $data The input string to sanitize.
 * @return string The sanitized string.
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Generates a CSRF token and stores it in the session.
 * @return string The generated CSRF token.
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifies a CSRF token against the one stored in the session.
 * @param string $token The token to verify.
 * @return bool True if the token is valid, false otherwise.
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Checks if a user is logged in.
 * @return bool True if a user is logged in, false otherwise.
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Gets the current logged-in user's ID.
 * @return int|null The user ID if logged in, null otherwise.
 */
function get_current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Gets the current logged-in user's role.
 * @return string|null The user role if logged in, null otherwise.
 */
function get_current_user_role() {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Checks if the current user has a specific role.
 * @param string $role The role to check against.
 * @return bool True if the user has the specified role, false otherwise.
 */
function has_role($role) {
    return get_current_user_role() === $role;
}

// Dummy data for top private universities in India
$top_private_universities = [
    [
        'name' => 'Amity University',
        'location' => 'Noida, UP',
        'email' => 'info@amity.edu',
        'logo' => 'https://upload.wikimedia.org/wikipedia/en/9/9e/Amity_University_Logo.png'
    ],
    [
        'name' => 'VIT University',
        'location' => 'Vellore, TN',
        'email' => 'info@vit.ac.in',
        'logo' => 'https://upload.wikimedia.org/wikipedia/en/6/6e/VIT_University_Logo.png'
    ],
    [
        'name' => 'SRM Institute of Science and Technology',
        'location' => 'Chennai, TN',
        'email' => 'info@srmist.edu.in',
        'logo' => 'https://upload.wikimedia.org/wikipedia/en/2/2e/SRM_University_Logo.png'
    ],
    [
        'name' => 'Manipal Academy of Higher Education',
        'location' => 'Manipal, KA',
        'email' => 'info@manipal.edu',
        'logo' => 'https://upload.wikimedia.org/wikipedia/en/7/7e/Manipal_University_Logo.png'
    ],
    [
        'name' => 'Symbiosis International University',
        'location' => 'Pune, MH',
        'email' => 'info@symbiosis.ac.in',
        'logo' => 'https://upload.wikimedia.org/wikipedia/en/2/2a/Symbiosis_International_University_Logo.png'
    ],
];

?>