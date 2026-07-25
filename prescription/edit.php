<?php
/**
 * Edit Prescription
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Edit Prescription";
$path_prefix = '../';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM prescriptions WHERE id = :id");
$stmt->execute(['id' => $id]);
$pr = $stmt->fetch();

if (!$pr) {
    set_flash('error', "Prescription record not found.");
    header("Location: index.php");
    exit;
}

$patients = $pdo->query("SELECT id, name FROM patients ORDER BY name ASC")->fetchAll();
$doctors = $pdo->query("SELECT id, name FROM doctors ORDER BY name ASC")->fetchAll();
$medicines = $pdo->query("SELECT id, name, company FROM medicines ORDER BY name ASC")->fetchAll();

$patient_id = $pr['patient_id'];
$doctor_id = $pr['doctor_id'];
$medicine_id = $pr['medicine_id'];
$dosage = $pr['dosage'];
$duration = $pr['duration'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = intval($_POST['patient_id'] ?? 0);
    $doctor_id = intval($_POST['doctor_id'] ?? 0);
    $medicine_id = intval($_POST['medicine_id'] ?? 0);
    $dosage = trim($_POST['dosage'] ?? '');
    $duration = trim($_POST['duration'] ?? '');

    if ($patient_id <= 0 || $doctor_id <= 0 || $medicine_id <= 0 || empty($dosage) || empty($duration)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt_update = $pdo->prepare("
            UPDATE prescriptions 
            SET patient_id = :patient_id, doctor_id = :doctor_id, medicine_id = :medicine_id, dosage = :dosage, duration = :duration 
            WHERE id = :id
        ");
        if ($stmt_update->execute([
            'patient_id' => $patient_id,
            'doctor_id' => $doctor_id,
            'medicine_id' => $medicine_id,
            'dosage' => $dosage,
            'duration' => $duration,
            'id' => $id
        ])) {
            set_flash('success', "Prescription updated successfully.");
            header("Location: index.php");
            exit;
        } else {
            $error = "Failed to update prescription.";
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Prescription</h4>
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
                                    <?php echo htmlspecialchars($d['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Medicine <span class="text-danger">*</span></label>
                        <select class="form-select" name="medicine_id" required>
                            <?php foreach ($medicines as $m): ?>
                                <option value="<?php echo $m['id']; ?>" <?php echo ($medicine_id == $m['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($m['name']); ?> (<?php echo htmlspecialchars($m['company']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Dosage & Instructions <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="dosage" value="<?php echo htmlspecialchars($dosage); ?>" required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Duration <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="duration" value="<?php echo htmlspecialchars($duration); ?>" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Update Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
