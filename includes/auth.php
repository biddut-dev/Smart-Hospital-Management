<?php
/**
 * Smart Hospital Management System
 * Authentication & Session Helper
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the user is authenticated as Admin
 */
function require_login() {
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        $_SESSION['flash_error'] = "Please log in to access the system.";
        $prefix = isset($GLOBALS['path_prefix']) ? $GLOBALS['path_prefix'] : './';
        header("Location: " . $prefix . "login.php");
        exit;
    }
}

/**
 * Set flash alert message
 */
function set_flash($type, $message) {
    $_SESSION['flash_' . $type] = $message;
}

/**
 * Render flash alert messages in Bootstrap 5 style
 */
function display_flash() {
    if (isset($_SESSION['flash_success'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>' . htmlspecialchars($_SESSION['flash_success']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['flash_success']);
    }
    if (isset($_SESSION['flash_error'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>' . htmlspecialchars($_SESSION['flash_error']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['flash_error']);
    }
}
