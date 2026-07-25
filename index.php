<?php
/**
 * Smart Hospital Management System
 * Entry Point / Router Redirect
 */
session_start();

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
