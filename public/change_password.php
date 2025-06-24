<?php
// change_password.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Change Password</h1>
    <form method="post">
        <label>Old Password: <input type="password" name="old_password" required></label><br>
        <label>New Password: <input type="password" name="new_password" required></label><br>
        <label>Confirm Password: <input type="password" name="confirm_password" required></label><br>
        <button type="submit">Change Password</button>
    </form>
</body>
</html> 