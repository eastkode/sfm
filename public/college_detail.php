<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';

$college = null;
$college_id = $_GET['id'] ?? null;

if ($college_id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT c.*, s.name as state_name FROM colleges c LEFT JOIN states s ON c.state_id = s.id WHERE c.id = ?");
    $stmt->bind_param("i", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $college = $result->fetch_assoc();
    }
    $stmt->close();
    $conn->close();
}

$page_title = $college ? $college['name'] : "College Not Found";

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
        .college-detail-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .college-detail-card h1 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .college-detail-card .detail-item {
            margin-bottom: 10px;
        }
        .college-detail-card .detail-item strong {
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
        <?php if ($college): ?>
            <div class="college-detail-card">
                <div class="text-center mb-4">
                    <?php if (!empty($college['logo_url'])): ?>
                        <img src="<?php echo htmlspecialchars($college['logo_url']); ?>" alt="<?php echo htmlspecialchars($college['name']); ?> Logo" class="img-fluid" style="max-height: 150px;">
                    <?php endif; ?>
                    <h1 class="mt-3"><?php echo htmlspecialchars($college['name']); ?></h1>
                </div>
                <hr>
                <div class="detail-item">
                    <strong>Description:</strong>
                    <p><?php echo nl2br(htmlspecialchars($college['description'])); ?></p>
                </div>
                <div class="row">
                    <div class="col-md-6 detail-item">
                        <strong>Website:</strong> <a href="<?php echo htmlspecialchars($college['website']); ?>" target="_blank"><?php echo htmlspecialchars($college['website']); ?></a><br>
                        <strong>Address:</strong> <?php echo htmlspecialchars($college['address']); ?><br>
                    </div>
                    <div class="col-md-6 detail-item">
                        <strong>City:</strong> <?php echo htmlspecialchars($college['city']); ?><br>
                        <strong>State:</strong> <?php echo htmlspecialchars($college['state_name']); ?><br>
                    </div>
                </div>

                <h3 class="mt-5 mb-3">Scholarships from <?php echo htmlspecialchars($college['name']); ?></h3>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <!-- Scholarships offered by this college will be loaded here -->
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">College Scholarship Example 1</h5>
                                <p class="card-text">A scholarship offered directly by this college.</p>
                                <a href="scholarship_detail.php?id=1" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">College Scholarship Example 2</h5>
                                <p class="card-text">Another scholarship from this institution.</p>
                                <a href="scholarship_detail.php?id=2" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                The college you are looking for does not exist or has been removed.
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
