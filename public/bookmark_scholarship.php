<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';

// Ensure only logged-in students can bookmark
if (!is_logged_in() || get_current_user_role() !== 'student') {
    redirect('login.php');
}

$user_id = get_current_user_id();
$scholarship_id = $_POST['scholarship_id'] ?? null;
$csrf_token = $_POST['csrf_token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $scholarship_id) {
    if (!verify_csrf_token($csrf_token)) {
        $_SESSION['error_message'] = 'Invalid CSRF token.';
        redirect('scholarship_detail.php?id=' . $scholarship_id);
    }

    $conn = getDbConnection();

    // Check if student has already bookmarked this scholarship
    $stmt = $conn->prepare("SELECT id FROM bookmarks WHERE student_id = ? AND scholarship_id = ?");
    $stmt->bind_param("ii", $user_id, $scholarship_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = 'You have already bookmarked this scholarship.';
    } else {
        // Insert bookmark into database
        $stmt = $conn->prepare("INSERT INTO bookmarks (student_id, scholarship_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $scholarship_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Scholarship bookmarked successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to bookmark scholarship. Please try again.';
        }
    }

    $stmt->close();
    $conn->close();

    redirect('scholarship_detail.php?id=' . $scholarship_id);
} else {
    $_SESSION['error_message'] = 'Invalid request.';
    redirect('scholarships.php');
}
?>