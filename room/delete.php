<?php
/**
 * Delete Room
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = :id");
    if ($stmt->execute(['id' => $id])) {
        set_flash('success', "Room deleted successfully.");
    } else {
        set_flash('error', "Could not delete room.");
    }
}

header("Location: index.php");
exit;
