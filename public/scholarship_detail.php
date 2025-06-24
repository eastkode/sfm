<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';

$scholarship = null;
$scholarship_id = $_GET['id'] ?? null;

if ($scholarship_id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM scholarships WHERE id = ?");
    $stmt->bind_param("i", $scholarship_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $scholarship = $result->fetch_assoc();
    }
    $stmt->close();
    $conn->close();
}

$page_title = $scholarship ? $scholarship['title'] : "Scholarship Not Found";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Scholarships for Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .scholarship-detail-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .scholarship-detail-card h1 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .scholarship-detail-card .detail-item {
            margin-bottom: 10px;
        }
        .scholarship-detail-card .detail-item strong {
            color: #343a40;
        }
    </style>
</head>
<body>
    <header class="bg-light py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="navbar-brand text-primary fw-bold fs-4">Scholarships for Me</a>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="scholarships.php">Scholarships</a></li>
                    <li class="nav-item"><a class="nav-link" href="colleges.php">Colleges</a></li>
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo get_current_user_role(); ?>/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-2" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container my-5">
        <?php if ($scholarship): ?>
            <div class="scholarship-detail-card">
                <h1 class="text-center"><?php echo htmlspecialchars($scholarship['title']); ?></h1>
                <hr>
                <div class="detail-item">
                    <strong>Description:</strong>
                    <p><?php echo nl2br(htmlspecialchars($scholarship['description'])); ?></p>
                </div>
                <div class="row">
                    <div class="col-md-6 detail-item">
                        <strong>Type:</strong> <?php echo htmlspecialchars($scholarship['type']); ?><br>
                        <strong>Amount:</strong> <?php echo htmlspecialchars($scholarship['amount'] ? '₹' . number_format($scholarship['amount'], 2) : 'N/A'); ?><br>
                        <strong>Minimum Percentage:</strong> <?php echo htmlspecialchars($scholarship['min_percentage'] ? $scholarship['min_percentage'] . '%' : 'N/A'); ?><br>
                    </div>
                    <div class="col-md-6 detail-item">
                        <strong>Deadline:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($scholarship['deadline']))); ?><br>
                        <strong>Provider:</strong> <?php echo htmlspecialchars($scholarship['provider_type']); // Will fetch actual provider name later ?><br>
                        <strong>Featured:</strong> <?php echo $scholarship['featured'] ? 'Yes' : 'No'; ?><br>
                    </div>
                </div>

                <?php if ($scholarship['logo_url']): ?>
                    <div class="text-center my-4">
                        <img src="<?php echo htmlspecialchars($scholarship['logo_url']); ?>" alt="Scholarship Logo" class="img-fluid" style="max-height: 150px;">
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                    <?php if (is_logged_in() && get_current_user_role() === 'student'): ?>
                        <form action="apply_scholarship.php" method="POST">
                            <input type="hidden" name="scholarship_id" value="<?php echo $scholarship['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <button type="submit" class="btn btn-primary btn-lg">Apply Now</button>
                        </form>
                        <form action="bookmark_scholarship.php" method="POST">
                            <input type="hidden" name="scholarship_id" value="<?php echo $scholarship['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <button type="submit" class="btn btn-outline-secondary btn-lg">Bookmark</button>
                        </form>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-lg">Login to Apply</a>
                        <a href="register.php" class="btn btn-outline-secondary btn-lg">Register to Apply</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                The scholarship you are looking for does not exist or has been removed.
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2023 Scholarships for Me. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
