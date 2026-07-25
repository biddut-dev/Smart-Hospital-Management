<?php
/**
 * Edit Room Details
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Edit Room";
$path_prefix = '../';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $id]);
$room = $stmt->fetch();

if (!$room) {
    set_flash('error', "Room record not found.");
    header("Location: index.php");
    exit;
}

$room_number = $room['room_number'];
$room_type = $room['room_type'];
$floor = $room['floor'];
$status = $room['status'];
$charge_per_day = $room['charge_per_day'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = trim($_POST['room_number'] ?? '');
    $room_type = trim($_POST['room_type'] ?? 'General');
    $floor = intval($_POST['floor'] ?? 1);
    $status = trim($_POST['status'] ?? 'Available');
    $charge_per_day = floatval($_POST['charge_per_day'] ?? 500.00);

    if (empty($room_number) || $floor <= 0 || $charge_per_day < 0) {
        $error = "Please fill in all fields properly.";
    } else {
        $stmt_check = $pdo->prepare("SELECT id FROM rooms WHERE room_number = :room_number AND id != :id");
        $stmt_check->execute(['room_number' => $room_number, 'id' => $id]);
        if ($stmt_check->fetch()) {
            $error = "Another room already has number '{$room_number}'.";
        } else {
            $stmt_update = $pdo->prepare("
                UPDATE rooms 
                SET room_number = :room_number, room_type = :room_type, floor = :floor, status = :status, charge_per_day = :charge_per_day 
                WHERE id = :id
            ");
            if ($stmt_update->execute([
                'room_number' => $room_number,
                'room_type' => $room_type,
                'floor' => $floor,
                'status' => $status,
                'charge_per_day' => $charge_per_day,
                'id' => $id
            ])) {
                set_flash('success', "Room details updated successfully.");
                header("Location: index.php");
                exit;
            } else {
                $error = "Failed to update room.";
            }
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Room Details</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>

        <div class="custom-card p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit.php?id=<?php echo $id; ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Room Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="room_number" value="<?php echo htmlspecialchars($room_number); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Room Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="room_type" required>
                            <option value="General" <?php echo ($room_type == 'General') ? 'selected' : ''; ?>>General</option>
                            <option value="Private" <?php echo ($room_type == 'Private') ? 'selected' : ''; ?>>Private</option>
                            <option value="ICU" <?php echo ($room_type == 'ICU') ? 'selected' : ''; ?>>ICU</option>
                            <option value="VIP" <?php echo ($room_type == 'VIP') ? 'selected' : ''; ?>>VIP</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Floor Level <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="floor" min="1" max="20" value="<?php echo $floor; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Daily Charge ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="charge_per_day" value="<?php echo $charge_per_day; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Available" <?php echo ($status == 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Occupied" <?php echo ($status == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
                            <option value="Maintenance" <?php echo ($status == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Update Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
