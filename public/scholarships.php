<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';

// This file will display a list of scholarships with search and filter options.
// For now, it's a placeholder. Logic to fetch scholarships will be added later.

$page_title = "Scholarship Listings";

// Get form values from homepage
$user_class = $_GET['class'] ?? '';
$user_marks = $_GET['marks'] ?? '';

// Example scholarships with eligibility
$scholarships = [
    [
        'id' => 1,
        'title' => 'Merit Scholarship for 12th',
        'description' => 'For students scoring above 90% in 12th.',
        'amount' => '₹50,000',
        'deadline' => '2024-12-31',
        'class' => '12',
        'min_marks' => 90
    ],
    [
        'id' => 2,
        'title' => 'UG Excellence Award',
        'description' => 'For UG students with CGPA above 8.5.',
        'amount' => '₹1,00,000',
        'deadline' => '2024-11-15',
        'class' => 'UG',
        'min_marks' => 8.5
    ],
    [
        'id' => 3,
        'title' => '10th Board Achiever',
        'description' => 'For 10th students with marks above 85%.',
        'amount' => '₹25,000',
        'deadline' => '2025-01-31',
        'class' => '10',
        'min_marks' => 85
    ],
    [
        'id' => 4,
        'title' => 'PG Research Grant',
        'description' => 'For PG students with CGPA above 8.0.',
        'amount' => '₹1,50,000',
        'deadline' => '2024-10-30',
        'class' => 'PG',
        'min_marks' => 8.0
    ],
];

// Function to parse marks input
function parse_marks($input) {
    if (strpos($input, '%') !== false) {
        return floatval(str_replace('%', '', $input));
    } elseif (stripos($input, 'cgpa') !== false) {
        return floatval(str_ireplace('cgpa', '', $input));
    } else {
        return floatval($input);
    }
}

$filtered = [];
if ($user_class && $user_marks) {
    $user_marks_val = parse_marks($user_marks);
    foreach ($scholarships as $sch) {
        if (
            strtolower($sch['class']) === strtolower($user_class) &&
            $user_marks_val >= $sch['min_marks']
        ) {
            $filtered[] = $sch;
        }
    }
} else {
    $filtered = $scholarships;
}
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
        .scholarship-card {
            transition: transform 0.2s ease-in-out;
        }
        .scholarship-card:hover {
            transform: translateY(-5px);
        }
        .filter-sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
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
        <h1 class="mb-4 text-center">Available Scholarships</h1>
        <?php if ($user_class && $user_marks): ?>
            <div class="alert alert-info text-center mb-4">
                Showing scholarships for <strong><?php echo htmlspecialchars($user_class); ?></strong> with marks/CGPA <strong><?php echo htmlspecialchars($user_marks); ?></strong>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-3">
                <div class="filter-sidebar">
                    <h4 class="mb-3">Filter Scholarships</h4>
                    <form>
                        <div class="mb-3">
                            <label for="keyword" class="form-label">Keyword</label>
                            <input type="text" class="form-control" id="keyword" placeholder="Search by title or description">
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type">
                                <option value="">All Types</option>
                                <option value="Cash">Cash</option>
                                <option value="Fee Waiver">Fee Waiver</option>
                                <option value="Gift">Gift</option>
                                <option value="Certificate">Certificate</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category">
                                <option value="">All Categories</option>
                                <!-- Categories will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="board" class="form-label">Board</label>
                            <select class="form-select" id="board">
                                <option value="">All Boards</option>
                                <!-- Boards will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select" id="state">
                                <option value="">All States</option>
                                <!-- States will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Deadline Before</label>
                            <input type="date" class="form-control" id="deadline">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <button type="reset" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</button>
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php if (!empty($filtered)): ?>
                        <?php foreach ($filtered as $scholarship): ?>
                            <div class="col">
                                <div class="card h-100 scholarship-card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary"><?php echo htmlspecialchars($scholarship['title']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($scholarship['description']); ?></p>
                                        <p class="card-text"><strong>Amount:</strong> <?php echo htmlspecialchars($scholarship['amount']); ?></p>
                                        <p class="card-text"><strong>Deadline:</strong> <?php echo htmlspecialchars($scholarship['deadline']); ?></p>
                                        <a href="scholarship_detail.php?id=<?php echo $scholarship['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-warning text-center" role="alert">
                                No scholarships found matching your criteria.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Pagination Placeholder -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
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
