<?php
/**
 * Admit Patient to Room
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Admit Patient";
$path_prefix = '../';

// Fetch Patients
$patients = $pdo->query("SELECT id, name, phone FROM patients ORDER BY name ASC")->fetchAll();
// Fetch Available Rooms
$rooms = $pdo->query("SELECT id, room_number, room_type, floor, charge_per_day FROM rooms WHERE status = 'Available' ORDER BY room_number ASC")->fetchAll();

$patient_id = $room_id = $admission_date = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = intval($_POST['patient_id'] ?? 0);
    $room_id = intval($_POST['room_id'] ?? 0);
    $admission_date = trim($_POST['admission_date'] ?? date('Y-m-d'));

    if ($patient_id <= 0 || $room_id <= 0 || empty($admission_date)) {
        $error = "Please select a patient, an available room, and an admission date.";
    } else {
        try {
            $pdo->beginTransaction();

            // Insert Admission
            $stmt = $pdo->prepare("
                INSERT INTO admissions (patient_id, room_id, admission_date, status)
                VALUES (:patient_id, :room_id, :admission_date, 'Admitted')
            ");
            $stmt->execute([
                'patient_id' => $patient_id,
                'room_id' => $room_id,
                'admission_date' => $admission_date
            ]);

            // Update Room Status to 'Occupied'
            $stmt_room = $pdo->prepare("UPDATE rooms SET status = 'Occupied' WHERE id = :room_id");
            $stmt_room->execute(['room_id' => $room_id]);

            $pdo->commit();

            set_flash('success', "Patient admitted successfully and room marked as Occupied.");
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to process patient admission: " . $e->getMessage();
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-box-arrow-in-right text-primary me-2"></i>Admit Patient to Room</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>

        <div class="custom-card p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="add.php">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Patient <span class="text-danger">*</span></label>
                        <select class="form-select" name="patient_id" required>
                            <option value="">-- Choose Patient --</option>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo ($patient_id == $p['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p['name']); ?> (<?php echo htmlspecialchars($p['phone']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Available Room <span class="text-danger">*</span></label>
                        <select class="form-select" name="room_id" required>
                            <option value="">-- Choose Available Room --</option>
                            <?php if (empty($rooms)): ?>
                                <option value="" disabled>No available rooms currently!</option>
                            <?php else: ?>
                                <?php foreach ($rooms as $r): ?>
                                    <option value="<?php echo $r['id']; ?>" <?php echo ($room_id == $r['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($r['room_number']); ?> - <?php echo htmlspecialchars($r['room_type']); ?> ($<?php echo number_format($r['charge_per_day'], 2); ?>/day)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Admission Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="admission_date" value="<?php echo $admission_date ?: date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold" <?php echo empty($rooms) ? 'disabled' : ''; ?>>
                        <i class="bi bi-check-circle me-1"></i> Confirm Admission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
