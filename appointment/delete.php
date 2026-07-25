<?php
/**
 * Delete Appointment
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = :id");
    if ($stmt->execute(['id' => $id])) {
        set_flash('success', "Appointment cancelled/deleted successfully.");
    } else {
        set_flash('error', "Could not delete appointment.");
    }
}

header("Location: index.php");
exit;
