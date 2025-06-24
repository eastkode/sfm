<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';

// Ensure only logged-in admins can access this page
if (!is_logged_in() || get_current_user_role() !== 'admin') {
    redirect('login.php');
}

$errors = [];
$success_message = '';
$college = null;
$states = [];

$conn = getDbConnection();

// Fetch states for the dropdown
$stmt = $conn->prepare("SELECT id, name FROM states ORDER BY name ASC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $states[] = $row;
}
$stmt->close();

$college_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if (!$college_id) {
    $_SESSION['error_message'] = 'No college ID provided.';
    redirect('admin/manage_colleges.php');
}

// Fetch college details for editing
$stmt = $conn->prepare("SELECT * FROM colleges WHERE id = ?");
$stmt->bind_param("i", $college_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $college = $result->fetch_assoc();
} else {
    $_SESSION['error_message'] = 'College not found.';
    redirect('admin/manage_colleges.php');
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $website = sanitize_input($_POST['website']);
    $address = sanitize_input($_POST['address']);
    $city = sanitize_input($_POST['city']);
    $state_id = filter_var($_POST['state_id'], FILTER_VALIDATE_INT);
    $csrf_token = $_POST['csrf_token'] ?? '';
    $current_logo_url = $_POST['current_logo_url'] ?? null;

    if (!verify_csrf_token($csrf_token)) {
        $errors[] = 'Invalid CSRF token.';
    }

    if (empty($name)) {
        $errors[] = 'College name is required.';
    }
    if (empty($description)) {
        $errors[] = 'Description is required.';
    }
    if (empty($website) || !filter_var($website, FILTER_VALIDATE_URL)) {
        $errors[] = 'Valid website URL is required.';
    }
    if (empty($address)) {
        $errors[] = 'Address is required.';
    }
    if (empty($city)) {
        $errors[] = 'City is required.';
    }
    if (!$state_id) {
        $errors[] = 'State is required.';
    }

    $logo_url = $current_logo_url; // Keep existing logo if no new one is uploaded
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . '/../uploads/logos/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid('logo_', true) . '.' . $file_extension;
        $target_file = $target_dir . $new_file_name;

        // Basic image validation
        $check = getimagesize($_FILES['logo']['tmp_name']);
        if ($check === false) {
            $errors[] = 'File is not an image.';
        } elseif ($_FILES['logo']['size'] > 500000) { // 500KB
            $errors[] = 'Sorry, your file is too large.';
        } elseif (!in_array($file_extension, ['jpg', 'png', 'jpeg', 'gif'])) {
            $errors[] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        } else {
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
                $logo_url = BASE_URL . 'uploads/logos/' . $new_file_name;
                // Optionally, delete old logo file if it exists
                if ($current_logo_url && strpos($current_logo_url, BASE_URL . 'uploads/logos/') === 0) {
                    $old_file_name = basename($current_logo_url);
                    $old_file_path = $target_dir . $old_file_name;
                    if (file_exists($old_file_path)) {
                        unlink($old_file_path);
                    }
                }
            } else {
                $errors[] = 'Sorry, there was an error uploading your file.';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE colleges SET name = ?, description = ?, website = ?, address = ?, city = ?, state_id = ?, logo_url = ? WHERE id = ?");
        $stmt->bind_param("sssssisi", $name, $description, $website, $address, $city, $state_id, $logo_url, $college_id);

        if ($stmt->execute()) {
            $success_message = 'College updated successfully!';
            // Refresh college data after update
            $stmt_refresh = $conn->prepare("SELECT * FROM colleges WHERE id = ?");
            $stmt_refresh->bind_param("i", $college_id);
            $stmt_refresh->execute();
            $result_refresh = $stmt_refresh->get_result();
            $college = $result_refresh->fetch_assoc();
            $stmt_refresh->close();
        } else {
            $errors[] = 'Failed to update college: ' . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title ?? 'Edit College'); ?> - Scholarships for Me</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-container {
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
        <div class="form-container">
            <h1 class="mb-4">Edit College: <?php echo htmlspecialchars($college['name'] ?? ''); ?></h1>
            <a href="manage_colleges.php" class="btn btn-secondary mb-3">Back to Manage Colleges</a>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <form action="edit_college.php?id=<?php echo htmlspecialchars($college_id); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="current_logo_url" value="<?php echo htmlspecialchars($college['logo_url'] ?? ''); ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">College Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? $college['name'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($_POST['description'] ?? $college['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="website" class="form-label">Website URL</label>
                    <input type="url" class="form-control" id="website" name="website" value="<?php echo htmlspecialchars($_POST['website'] ?? $college['website'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($_POST['address'] ?? $college['address'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($_POST['city'] ?? $college['city'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="state_id" class="form-label">State</label>
                    <select class="form-select" id="state_id" name="state_id" required>
                        <option value="">Select State</option>
                        <?php foreach ($states as $state): ?>
                            <option value="<?php echo htmlspecialchars($state['id']); ?>" <?php echo (isset($_POST['state_id']) && $_POST['state_id'] == $state['id']) || (!isset($_POST['state_id']) && $college['state_id'] == $state['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($state['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">College Logo (Max 500KB, JPG, PNG, GIF)</label>
                    <?php if (!empty($college['logo_url'])): ?>
                        <div class="mb-2">
                            Current Logo: <img src="<?php echo htmlspecialchars($college['logo_url']); ?>" alt="Current Logo" style="max-height: 80px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/jpeg,image/png,image/gif">
                    <small class="form-text text-muted">Leave blank to keep current logo.</small>
                </div>
                <button type="submit" class="btn btn-primary">Update College</button>
            </form>
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
