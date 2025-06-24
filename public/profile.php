<?php
// profile.php
// Dummy user profile page
$type = $_GET['type'] ?? 'student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1><?php echo ucfirst($type); ?> Profile</h1>
    <div class="profile-card">
        <?php if ($type === 'student'): ?>
            <p><strong>Name:</strong> Rahul Sharma</p>
            <p><strong>Email:</strong> rahul@student.com</p>
            <p><strong>Class:</strong> 12th</p>
            <p><strong>Marks:</strong> 92%</p>
        <?php elseif ($type === 'college'): ?>
            <p><strong>College Name:</strong> Amity University</p>
            <p><strong>Email:</strong> info@amity.edu</p>
            <p><strong>Location:</strong> Noida, UP</p>
        <?php else: ?>
            <p><strong>Admin Name:</strong> Admin User</p>
            <p><strong>Email:</strong> admin@dashboard.com</p>
        <?php endif; ?>
    </div>
</body>
</html> 