<?php
/**
 * Delete Admission
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    // Get room id to reset status
    $stmt_get = $pdo->prepare("SELECT room_id FROM admissions WHERE id = :id");
    $stmt_get->execute(['id' => $id]);
    $adm = $stmt_get->fetch();

    $stmt = $pdo->prepare("DELETE FROM admissions WHERE id = :id");
    if ($stmt->execute(['id' => $id])) {
        if ($adm) {
            $pdo->prepare("UPDATE rooms SET status = 'Available' WHERE id = :room_id")->execute(['room_id' => $adm['room_id']]);
        }
        set_flash('success', "Admission record deleted successfully.");
    } else {
        set_flash('error', "Could not delete admission.");
    }
}

header("Location: index.php");
exit;
