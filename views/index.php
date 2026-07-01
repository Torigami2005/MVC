<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit;
}

require_once '../database.php';
require_once '../models/Users.php';
require_once '../controllers/UserController.php';

$db = new Database();
$userModel = new Users($db);
$userController = new UserController($userModel);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? '';

    if (empty($name) || empty($description) || empty($status)) {
        $error = "All fields are required!";
    } else {
        if ($userController->createUser($name, $description, $status)) {
            $success = "User created successfully!";
            // Redirect after 2 seconds
            header("Refresh: 2; url=display.php");
        } else {
            $error = "Error creating user!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - User Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-vh-100 bg-primary bg-gradient d-flex justify-content-center align-items-center p-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 p-4 p-md-5">
        <div class="text-center mb-4">
            <h1 class="h3 mb-2"><i class="fas fa-plus-circle"></i> Create New User</h1>
            <p class="text-muted">Add a new user to the system</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?> Redirecting...
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label fw-600">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter user's full name" required>
                <div class="form-text">The complete name of the user</div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-600">Description <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter user description or role" required>
                <div class="form-text">Brief description or job role</div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label fw-600">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                    <option value="">Select a status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Pending">Pending</option>
                </select>
                <div class="form-text">Select the current status of the user</div>
            </div>

            <div class="d-grid gap-2 d-sm-flex mt-4">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="fas fa-save"></i> Create User
                </button>
                <button type="button" class="btn btn-outline-secondary btn-lg flex-fill" onclick="window.location.href='display.php'">
                    <i class="fas fa-users"></i> View Users
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="display.php" class="text-decoration-none text-primary">
                <i class="fas fa-arrow-left"></i> Back to Users List
            </a>
        </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>