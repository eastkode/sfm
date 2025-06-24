<?php
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';

// This file will display a list of colleges with search and filter options.
// For now, it's a placeholder. Logic to fetch colleges will be added later.

$page_title = "College Directory";

// Example of fetching colleges (will be implemented properly later)
$colleges = [
    ['name' => 'University of Example', 'description' => 'A leading institution for higher education.', 'logo' => 'https://via.placeholder.com/100x100?text=Uni1'],
    ['name' => 'Tech Institute of Innovation', 'description' => 'Specializing in cutting-edge technology programs.', 'logo' => 'https://via.placeholder.com/100x100?text=Tech'],
    ['name' => 'Arts & Humanities College', 'description' => 'Fostering creativity and critical thinking.', 'logo' => 'https://via.placeholder.com/100x100?text=Arts'],
];

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
        .college-card {
            transition: transform 0.2s ease-in-out;
        }
        .college-card:hover {
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
        <h1 class="mb-4 text-center">Our Partner Colleges</h1>

        <div class="row">
            <div class="col-md-3">
                <div class="filter-sidebar">
                    <h4 class="mb-3">Filter Colleges</h4>
                    <form>
                        <div class="mb-3">
                            <label for="college_keyword" class="form-label">Keyword</label>
                            <input type="text" class="form-control" id="college_keyword" placeholder="Search by name or description">
                        </div>
                        <div class="mb-3">
                            <label for="college_state" class="form-label">State</label>
                            <select class="form-select" id="college_state">
                                <option value="">All States</option>
                                <!-- States will be loaded dynamically -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <button type="reset" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</button>
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php if (!empty($colleges)): ?>
                        <?php foreach ($colleges as $college): ?>
                            <div class="col">
                                <div class="card h-100 college-card shadow-sm">
                                    <div class="card-body text-center">
                                        <?php if (!empty($college['logo'])): ?>
                                            <img src="<?php echo htmlspecialchars($college['logo']); ?>" alt="<?php echo htmlspecialchars($college['name']); ?> Logo" class="img-fluid mb-3" style="max-height: 80px;">
                                        <?php endif; ?>
                                        <h5 class="card-title text-primary"><?php echo htmlspecialchars($college['name']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($college['description']); ?></p>
                                        <a href="college_detail.php?id=<?php echo $college['id'] ?? '1'; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center" role="alert">
                                No colleges found matching your criteria.
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
