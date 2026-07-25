<?php
/**
 * Edit Admission / Discharge Patient
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Edit Admission";
$path_prefix = '../';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("
    SELECT adm.*, p.name AS patient_name, r.room_number
    FROM admissions adm
    JOIN patients p ON adm.patient_id = p.id
    JOIN rooms r ON adm.room_id = r.id
    WHERE adm.id = :id
");
$stmt->execute(['id' => $id]);
$adm = $stmt->fetch();

if (!$adm) {
    set_flash('error', "Admission record not found.");
    header("Location: index.php");
    exit;
}

$admission_date = $adm['admission_date'];
$discharge_date = $adm['discharge_date'] ?: date('Y-m-d');
$status = $adm['status'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admission_date = trim($_POST['admission_date'] ?? '');
    $discharge_date = trim($_POST['discharge_date'] ?? '');
    $status = trim($_POST['status'] ?? 'Admitted');

    if (empty($admission_date)) {
        $error = "Admission date is required.";
    } else {
        try {
            $pdo->beginTransaction();

            $stmt_update = $pdo->prepare("
                UPDATE admissions
                SET admission_date = :admission_date, discharge_date = :discharge_date, status = :status
                WHERE id = :id
            ");
            $stmt_update->execute([
                'admission_date' => $admission_date,
                'discharge_date' => ($status === 'Discharged') ? $discharge_date : NULL,
                'status' => $status,
                'id' => $id
            ]);

            // If discharged, free up the room
            if ($status === 'Discharged') {
                $stmt_room = $pdo->prepare("UPDATE rooms SET status = 'Available' WHERE id = :room_id");
                $stmt_room->execute(['room_id' => $adm['room_id']]);
            } else {
                $stmt_room = $pdo->prepare("UPDATE rooms SET status = 'Occupied' WHERE id = :room_id");
                $stmt_room->execute(['room_id' => $adm['room_id']]);
            }

            $pdo->commit();

            set_flash('success', "Admission record updated successfully.");
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to update admission: " . $e->getMessage();
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Update Admission Record</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>

        <div class="custom-card p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="p-3 bg-light rounded border mb-3">
                <div class="fw-bold text-slate-800"><?php echo htmlspecialchars($adm['patient_name']); ?></div>
                <small class="text-muted">Assigned Room: <strong><?php echo htmlspecialchars($adm['room_number']); ?></strong></small>
            </div>

            <form method="POST" action="edit.php?id=<?php echo $id; ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Admission Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="admission_date" value="<?php echo $admission_date; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Discharge Date</label>
                        <input type="date" class="form-control" name="discharge_date" value="<?php echo $discharge_date; ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Admission Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Admitted" <?php echo ($status == 'Admitted') ? 'selected' : ''; ?>>Admitted (Occupies Room)</option>
                            <option value="Discharged" <?php echo ($status == 'Discharged') ? 'selected' : ''; ?>>Discharged (Frees Room)</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Save Updates</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
