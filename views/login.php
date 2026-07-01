<?php
session_start();

$error = '';
$success = '';

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_destroy();
    $_SESSION = array();
    header("Location: login.php?loggedout=1");
    exit;
}

// Show logout success message
if (isset($_GET['loggedout']) && $_GET['loggedout'] === '1') {
    $success = "Logged out successfully!";
}

// Simple login validation (you can enhance this with database validation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation - replace with actual database check
    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } elseif ($username === 'admin' && $password === 'password') {
        $_SESSION['user'] = $username;
        $_SESSION['logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}

// If already logged in, redirect to index
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - User Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-vh-100 bg-primary bg-gradient d-flex justify-content-center align-items-center p-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                <div class="card shadow-lg border-0 p-4 p-md-5">
        <div class="text-center mb-5">
            <div class="rounded-circle bg-primary bg-gradient text-white fs-1 mx-auto mb-3 p-4 d-inline-flex align-items-center justify-content-center">👤</div>
            <h1 class="h3 mb-2">Login</h1>
            <p class="text-muted small">User Management System</p>
        </div>

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

        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label fw-600">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-600">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold">Sign In</button>
        </form>

        <div class="alert alert-info mt-4 mb-3 border-start border-4 border-primary">
            <strong><i class="fas fa-info-circle"></i> Demo Credentials:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>password</code>
        </div>

        <div class="text-center small mt-4">
            <small>
                <a href="#" class="text-decoration-none">Forgot password?</a> | 
                <a href="#" class="text-decoration-none">Sign up</a>
            </small>
        </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
