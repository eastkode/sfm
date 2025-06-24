<?php
// contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Contact Us</h1>
    <form method="post">
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Message: <textarea name="message" required></textarea></label><br>
        <button type="submit">Send</button>
    </form>
    <div class="contact-info">
        <h2>Our Office</h2>
        <p>Education Elements, Noida, UP, India</p>
        <p>Email: support@educationelements.com</p>
        <p>Phone: +91-12345-67890</p>
    </div>
</body>
</html> 