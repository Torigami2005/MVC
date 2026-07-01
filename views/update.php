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

function getStatusBadgeClass($status) {
    $statusKey = strtolower(trim((string)$status));

    return match ($statusKey) {
        'active' => 'bg-success',
        'inactive' => 'bg-danger',
        'pending' => 'bg-warning text-dark',
        default => 'bg-secondary'
    };
}

function getStatusLabel($status) {
    return ucfirst(strtolower(trim((string)$status)));
}

$db = new Database();
$userModel = new Users($db);
$userController = new UserController($userModel);

$user = null;
$error = '';
$success = '';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user = $userController->getUserById($id);
    
    if (!$user) {
        $error = "User not found!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = intval($_POST['id']);
        $name = $_POST['name'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        if (empty($name) || empty($description) || empty($status)) {
            $error = "All fields are required!";
        } else {
            if ($userController->updateUser($id, $name, $description, $status)) {
                $success = "User updated successfully!";
                header("Location: display.php");
                exit;
            } else {
                $error = "Error updating user!";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-vh-100 bg-primary bg-gradient d-flex justify-content-center align-items-center p-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 p-4 p-md-5">
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($user): ?>
            <div class="text-center mb-4">
                <h1 class="h3 mb-2"><i class="fas fa-edit"></i> Edit User</h1>
                <p class="text-muted">Update user information</p>
            </div>

            <form action="" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                <div class="mb-3">
                    <label for="name" class="form-label fw-600">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-600">Description</label>
                    <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($user['description']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-600">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="Active" <?php echo (strtolower(trim((string)($user['status'] ?? ''))) === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="Inactive" <?php echo (strtolower(trim((string)($user['status'] ?? ''))) === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        <option value="Pending" <?php echo (strtolower(trim((string)($user['status'] ?? ''))) === 'pending') ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>

                <div class="d-grid gap-2 d-sm-flex mt-4">
                    <button type="submit" class="btn btn-primary btn-lg flex-fill">
                        <i class="fas fa-save"></i> Update User
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg flex-fill" onclick="window.location.href='display.php'">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center">
                <h1 class="h3 mb-2">Error</h1>
                <p class="text-muted mb-4">Unable to load user information</p>
                <a href="display.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
