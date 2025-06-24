<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';

// Check if user is logged in and is a student
if (!is_logged_in() || get_current_user_role() !== 'student') {
    redirect('login.php');
}

$user_id = get_current_user_id();
$username = '';

$conn = getDbConnection();
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
}
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Scholarships for Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .dashboard-container {
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <header class="bg-light py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="../index.php" class="navbar-brand text-primary fw-bold fs-4">Scholarships for Me</a>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../scholarships.php">Scholarships</a></li>
                    <li class="nav-item"><a class="nav-link" href="../colleges.php">Colleges</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2" href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="dashboard-container">
            <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <p class="lead">This is your student dashboard. Here you can manage your applications and bookmarked scholarships.</p>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">My Applications</h5>
                            <p class="card-text">View the scholarships you have applied for.</p>
                            <a href="my_applications.php" class="btn btn-primary">View Applications</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">My Bookmarks</h5>
                            <p class="card-text">See the scholarships you have saved for later.</p>
                            <a href="my_bookmarks.php" class="btn btn-secondary">View Bookmarks</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Future sections for recommendations, profile settings etc. -->
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2023 Scholarships for Me. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
