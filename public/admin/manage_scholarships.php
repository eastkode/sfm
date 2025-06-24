<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';

// Ensure only logged-in admins can access this page
if (!is_logged_in() || get_current_user_role() !== 'admin') {
    redirect('login.php');
}

$scholarships = [];
$conn = getDbConnection();

// Fetch all scholarships
$stmt = $conn->prepare("SELECT s.id, s.title, s.type, s.amount, s.deadline, s.featured, s.created_at, 
                               CASE WHEN s.provider_type = 'college' THEN c.name ELSE 'Admin' END as provider_name
                        FROM scholarships s
                        LEFT JOIN colleges c ON s.provider_id = c.id AND s.provider_type = 'college'
                        ORDER BY s.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $scholarships[] = $row;
}

$stmt->close();
$conn->close();

$page_title = "Manage Scholarships";
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
            <h1 class="mb-4">Manage Scholarships</h1>
            <a href="add_scholarship.php" class="btn btn-success mb-3">Add New Scholarship</a>

            <?php if (!empty($scholarships)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Deadline</th>
                                <th>Provider</th>
                                <th>Featured</th>
                                <th>Added On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scholarships as $scholarship): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($scholarship['id']); ?></td>
                                    <td><?php echo htmlspecialchars($scholarship['title']); ?></td>
                                    <td><?php echo htmlspecialchars($scholarship['type']); ?></td>
                                    <td><?php echo htmlspecialchars($scholarship['amount'] ? '₹' . number_format($scholarship['amount'], 2) : 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($scholarship['deadline']))); ?></td>
                                    <td><?php echo htmlspecialchars($scholarship['provider_name']); ?></td>
                                    <td><?php echo $scholarship['featured'] ? 'Yes' : 'No'; ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($scholarship['created_at']))); ?></td>
                                    <td>
                                        <a href="edit_scholarship.php?id=<?php echo $scholarship['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete_scholarship.php?id=<?php echo $scholarship['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this scholarship?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    No scholarships found.
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
