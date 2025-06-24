<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';

// Ensure only logged-in admins can access this page
if (!is_logged_in() || get_current_user_role() !== 'admin') {
    redirect('login.php');
}

$colleges = [];
$conn = getDbConnection();

// Fetch all colleges
$stmt = $conn->prepare("SELECT c.id, c.name, c.website, c.city, s.name as state_name, c.created_at FROM colleges c LEFT JOIN states s ON c.state_id = s.id ORDER BY c.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $colleges[] = $row;
}

$stmt->close();
$conn->close();

$page_title = "Manage Colleges";
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
        .admin-table-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
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
        <div class="admin-table-container">
            <h1 class="mb-4">Manage Colleges</h1>
            <a href="add_college.php" class="btn btn-success mb-3">Add New College</a>

            <?php if (!empty($colleges)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Website</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Added On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($colleges as $college): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($college['id']); ?></td>
                                    <td><?php echo htmlspecialchars($college['name']); ?></td>
                                    <td><a href="<?php echo htmlspecialchars($college['website']); ?>" target="_blank"><?php echo htmlspecialchars($college['website']); ?></a></td>
                                    <td><?php echo htmlspecialchars($college['city']); ?></td>
                                    <td><?php echo htmlspecialchars($college['state_name']); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($college['created_at']))); ?></td>
                                    <td>
                                        <a href="edit_college.php?id=<?php echo $college['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete_college.php?id=<?php echo $college['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this college?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    No colleges found.
                </div>
            <?php endif; ?>
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
