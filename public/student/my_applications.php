<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';

// Ensure only logged-in students can view their applications
if (!is_logged_in() || get_current_user_role() !== 'student') {
    redirect('login.php');
}

$user_id = get_current_user_id();
$applications = [];

$conn = getDbConnection();

// Fetch applications for the current student
$stmt = $conn->prepare("SELECT s.title, s.description, s.deadline, a.application_date, a.status, s.id as scholarship_id
                        FROM applications a
                        JOIN scholarships s ON a.scholarship_id = s.id
                        WHERE a.student_id = ?
                        ORDER BY a.application_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

$stmt->close();
$conn->close();

$page_title = "My Applications";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Scholarships for Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .application-card {
            transition: transform 0.2s ease-in-out;
        }
        .application-card:hover {
            transform: translateY(-5px);
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

    <main class="container my-5">
        <h1 class="mb-4 text-center">My Applications</h1>

        <?php if (!empty($applications)): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($applications as $app): ?>
                    <div class="col">
                        <div class="card h-100 application-card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($app['title']); ?></h5>
                                <p class="card-text"><strong>Applied On:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($app['application_date']))); ?></p>
                                <p class="card-text"><strong>Status:</strong> <span class="badge bg-<?php 
                                    if ($app['status'] == 'approved') echo 'success';
                                    else if ($app['status'] == 'rejected') echo 'danger';
                                    else if ($app['status'] == 'shortlisted') echo 'info';
                                    else echo 'warning';
                                ?>"><?php echo htmlspecialchars(ucfirst($app['status'])); ?></span></p>
                                <p class="card-text"><strong>Deadline:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($app['deadline']))); ?></p>
                                <a href="../scholarship_detail.php?id=<?php echo $app['scholarship_id']; ?>" class="btn btn-sm btn-outline-primary">View Scholarship</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center" role="alert">
                You haven't applied for any scholarships yet.
                <br><a href="../scholarships.php" class="alert-link">Browse scholarships now!</a>
            </div>
        <?php endif; ?>
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
