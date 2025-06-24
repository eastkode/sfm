<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';

// Ensure only logged-in admins can access this page
if (!is_logged_in() || get_current_user_role() !== 'admin') {
    redirect('login.php');
}

$college_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if (!$college_id) {
    $_SESSION['error_message'] = 'No college ID provided for deletion.';
    redirect('admin/manage_colleges.php');
}

$conn = getDbConnection();

// Prepare and execute the delete statement
$stmt = $conn->prepare("DELETE FROM colleges WHERE id = ?");
$stmt->bind_param("i", $college_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = 'College deleted successfully!';
    } else {
        $_SESSION['error_message'] = 'College not found or already deleted.';
    }
} else {
    $_SESSION['error_message'] = 'Failed to delete college: ' . $conn->error;
}

$stmt->close();
$conn->close();

redirect('admin/manage_colleges.php');
?>