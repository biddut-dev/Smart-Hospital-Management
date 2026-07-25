<?php
/**
 * Smart Hospital Management System
 * Shared Header Template
 */

if (!isset($path_prefix)) {
    $path_prefix = './';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Smart HMS' : 'Smart Hospital Management System'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo $path_prefix; ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar Navigation -->
        <?php include_once __DIR__ . '/sidebar.php'; ?>

        <!-- Main Content Wrapper -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-custom shadow-sm sticky-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-light me-3 border">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    
                    <span class="navbar-brand fw-bold text-primary mb-0 h1 d-none d-sm-inline">
                        <i class="bi bi-hospital me-2"></i>Smart Hospital Management System
                    </span>

                    <div class="ms-auto d-flex align-items-center gap-3">
                        <span class="user-badge"><i class="bi bi-person-check-fill me-1"></i> Logged in as: <strong>Admin</strong></span>
                        <a href="<?php echo $path_prefix; ?>logout.php" class="btn btn-outline-danger btn-sm text-decoration-none">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Page Body Container -->
            <div class="container-fluid p-4">
                <?php display_flash(); ?>
