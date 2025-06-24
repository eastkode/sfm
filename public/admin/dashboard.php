<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';

// Check if user is logged in and is an admin
if (!is_logged_in() || get_current_user_role() !== 'admin') {
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
    <title>Admin Dashboard - Scholarships for Me</title>
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
            <h2 class="mb-4">Welcome, Admin <?php echo htmlspecialchars($username); ?>!</h2>
            <p class="lead">This is your administrator dashboard. Here you can manage the entire platform.</p>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">User Management</h5>
                            <p class="card-text">Approve/ban users, assign roles.</p>
                            <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">College Management</h5>
                            <p class="card-text">Approve/edit/delete colleges.</p>
                            <a href="manage_colleges.php" class="btn btn-secondary">Manage Colleges</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Scholarship Moderation</h5>
                            <p class="card-text">Approve/edit/delete scholarships.</p>
                            <a href="manage_scholarships.php" class="btn btn-info text-white">Moderate Scholarships</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Platform Statistics</h5>
                            <p class="card-text">View total scholarships, colleges, users, applications.</p>
                            <a href="view_stats.php" class="btn btn-success">View Statistics</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Category & Board Management</h5>
                            <p class="card-text">Manage scholarship categories and boards.</p>
                            <a href="manage_categories_boards.php" class="btn btn-warning text-white">Manage Categories & Boards</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">State Management</h5>
                            <p class="card-text">Manage states for scholarships and colleges.</p>
                            <a href="manage_states.php" class="btn btn-danger">Manage States</a>
                        </div>
                    </div>
                </div>
            </div>
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
