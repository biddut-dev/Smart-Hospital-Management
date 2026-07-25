<?php
/**
 * Edit Appointment Details & Status
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Edit Appointment";
$path_prefix = '../';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = :id");
$stmt->execute(['id' => $id]);
$app = $stmt->fetch();

if (!$app) {
    set_flash('error', "Appointment record not found.");
    header("Location: index.php");
    exit;
}

$patients = $pdo->query("SELECT id, name FROM patients ORDER BY name ASC")->fetchAll();
$doctors = $pdo->query("
    SELECT d.id, d.name, dep.name AS department_name
    FROM doctors d
    JOIN departments dep ON d.department_id = dep.id
    ORDER BY d.name ASC
")->fetchAll();

$patient_id = $app['patient_id'];
$doctor_id = $app['doctor_id'];
$appointment_date = $app['appointment_date'];
$appointment_time = $app['appointment_time'];
$status = $app['status'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = intval($_POST['patient_id'] ?? 0);
    $doctor_id = intval($_POST['doctor_id'] ?? 0);
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $appointment_time = trim($_POST['appointment_time'] ?? '');
    $status = trim($_POST['status'] ?? 'Scheduled');

    if ($patient_id <= 0 || $doctor_id <= 0 || empty($appointment_date) || empty($appointment_time)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt_update = $pdo->prepare("
            UPDATE appointments
            SET patient_id = :patient_id, doctor_id = :doctor_id, appointment_date = :appointment_date, appointment_time = :appointment_time, status = :status
            WHERE id = :id
        ");
        if ($stmt_update->execute([
            'patient_id' => $patient_id,
            'doctor_id' => $doctor_id,
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time,
            'status' => $status,
            'id' => $id
        ])) {
            set_flash('success', "Appointment record updated successfully.");
            header("Location: index.php");
            exit;
        } else {
            $error = "Failed to update appointment.";
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Appointment</h4>
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
                        <label class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
                        <select class="form-select" name="patient_id" required>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo ($patient_id == $p['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Doctor <span class="text-danger">*</span></label>
                        <select class="form-select" name="doctor_id" required>
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?php echo $d['id']; ?>" <?php echo ($doctor_id == $d['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($d['name']); ?> (<?php echo htmlspecialchars($d['department_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="appointment_date" value="<?php echo $appointment_date; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="appointment_time" value="<?php echo $appointment_time; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            <option value="Scheduled" <?php echo ($status == 'Scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                            <option value="Completed" <?php echo ($status == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo ($status == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
