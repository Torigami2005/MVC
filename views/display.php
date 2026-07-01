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

$success = '';
$error = '';

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    if ($userController->deleteUser($id)) {
        $success = "User deleted successfully!";
    } else {
        $error = "Error deleting user!";
    }
}

$users = $userController->getALLUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1"><i class="fas fa-users"></i> User Management</span>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-light btn-sm">
                    <i class="fas fa-plus-circle"></i> Add New
                </a>
                <a href="login.php?logout=true" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-3">
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-4 shadow-sm overflow-hidden">
            <?php if (count($users) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($user['id']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['description']); ?></td>
                                    <td>
                                        <span class="badge <?php echo getStatusBadgeClass($user['status'] ?? ''); ?>">
                                            <?php echo htmlspecialchars(getStatusLabel($user['status'] ?? '')); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="update.php?id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-danger btn-sm" onclick="showDeleteModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" title="Delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <div class="display-1 mb-3">📭</div>
                    <h2>No Users Found</h2>
                    <p>Start by adding your first user to the system.</p>
                    <a href="index.php" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-plus-circle"></i> Add New User
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-muted text-center mt-3">
            <small><i class="fas fa-database"></i> Total Users: <?php echo count($users); ?></small>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-trash"></i> Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteForm" method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to delete user: <strong><span id="userName"></span></strong>?</p>
                        <p class="text-muted small">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showDeleteModal(id, name) {
            document.getElementById('deleteId').value = id;
            document.getElementById('userName').textContent = name;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
</body>
</html>