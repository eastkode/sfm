<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';

// Ensure only logged-in students can apply
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

    // Check if student has already applied for this scholarship
    $stmt = $conn->prepare("SELECT id FROM applications WHERE student_id = ? AND scholarship_id = ?");
    $stmt->bind_param("ii", $user_id, $scholarship_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = 'You have already applied for this scholarship.';
    } else {
        // Insert application into database
        $stmt = $conn->prepare("INSERT INTO applications (student_id, scholarship_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $scholarship_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Application submitted successfully!';
            // TODO: Send email notification to student and college/admin
        } else {
            $_SESSION['error_message'] = 'Failed to submit application. Please try again.';
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