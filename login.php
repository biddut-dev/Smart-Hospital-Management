<?php
/**
 * Smart Hospital Management System
 * Admin Login Page
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please fill in both username and password.";
    } else {
        $authenticated = false;

        try {
            // Query users table
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && (password_verify($password, $user['password']) || ($username === 'admin' && $password === 'admin123'))) {
                $authenticated = true;
            }
        } catch (Exception $e) {
            // Fallback for demo credentials
            if ($username === 'admin' && $password === 'admin123') {
                $authenticated = true;
            }
        }

        // Demo fallback check
        if (!$authenticated && $username === 'admin' && $password === 'admin123') {
            $authenticated = true;
        }

        if ($authenticated) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = $username;
            
            set_flash('success', 'Welcome back! Logged in successfully.');
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password. Try admin / admin123";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Hospital Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 p-3">

<div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="max-width: 420px; width: 100%;">
    <div class="bg-primary text-white text-center p-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-20 rounded-circle p-3 mb-2">
            <i class="bi bi-hospital fs-1"></i>
        </div>
        <h4 class="fw-bold mb-1">Smart Hospital</h4>
        <p class="small text-white-50 mb-0">DBMS Lab Project Administration</p>
    </div>

    <div class="card-body p-4">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold text-secondary">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-primary"></i></span>
                    <input type="text" class="form-control bg-light border-start-0" id="username" name="username" placeholder="admin" value="admin" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-primary"></i></span>
                    <input type="password" class="form-control bg-light border-start-0" id="password" name="password" placeholder="admin123" value="admin123" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="mt-4 p-3 bg-light rounded-3 text-center border">
            <small class="text-muted d-block fw-semibold"><i class="bi bi-info-circle me-1"></i> Demo Credentials</small>
            <span class="badge bg-primary-subtle text-primary mt-1 me-1">Username: admin</span>
            <span class="badge bg-primary-subtle text-primary mt-1">Password: admin123</span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
