<?php
/**
 * View Patient Details & Medical History
 * Demonstrates Stored Procedure Execution: CALL sp_get_patient_history(?);
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Patient Profile";
$path_prefix = '../';

$id = intval($_GET['id'] ?? 0);

// Call Stored Procedure sp_get_patient_history
try {
    $stmt_sp = $pdo->prepare("CALL sp_get_patient_history(:id)");
    $stmt_sp->execute(['id' => $id]);

    // 1st Result Set: Patient Details
    $patient = $stmt_sp->fetch();
    
    // 2nd Result Set: Appointments
    $stmt_sp->nextRowset();
    $appointments = $stmt_sp->fetchAll();

    // 3rd Result Set: Prescriptions
    $stmt_sp->nextRowset();
    $prescriptions = $stmt_sp->fetchAll();

} catch (PDOException $e) {
    // Fallback if stored procedure has rowset issue on certain PDO drivers
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $patient = $stmt->fetch();

    $stmt_app = $pdo->prepare("
        SELECT a.id AS appointment_id, d.name AS doctor_name, dep.name AS department_name, a.appointment_date, a.appointment_time, a.status
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        JOIN departments dep ON d.department_id = dep.id
        WHERE a.patient_id = :id ORDER BY a.appointment_date DESC
    ");
    $stmt_app->execute(['id' => $id]);
    $appointments = $stmt_app->fetchAll();

    $stmt_pres = $pdo->prepare("
        SELECT pr.id AS prescription_id, d.name AS doctor_name, m.name AS medicine_name, pr.dosage, pr.duration, pr.created_at
        FROM prescriptions pr
        JOIN doctors d ON pr.doctor_id = d.id
        JOIN medicines m ON pr.medicine_id = m.id
        WHERE pr.patient_id = :id ORDER BY pr.created_at DESC
    ");
    $stmt_pres->execute(['id' => $id]);
    $prescriptions = $stmt_pres->fetchAll();
}

if (!$patient) {
    set_flash('error', "Patient record not found.");
    header("Location: index.php");
    exit;
}

include_once __DIR__ . '/../includes/header.php';
?>

<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-person-bounding-box text-primary me-2"></i>Patient Profile</h3>
        <p class="text-muted small mb-0">ID: <strong>PAT-<?php echo sprintf('%04d', $patient['id']); ?></strong> | Loaded via MySQL Stored Procedure</p>
    </div>
    <div>
        <a href="index.php" class="btn btn-outline-secondary btn-sm me-1"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
        <a href="edit.php?id=<?php echo $patient['id']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit Profile</a>
    </div>
</div>

<!-- DBMS Feature Showcase Alert -->
<div class="sql-demo-box shadow-sm mb-4">
    <div class="d-flex align-items-center mb-1">
        <i class="bi bi-gear-wide-connected text-warning fs-5 me-2"></i>
        <strong class="text-white">DBMS Feature Applied: MySQL Stored Procedure</strong>
    </div>
    <code>CALL sp_get_patient_history(<?php echo $patient['id']; ?>);</code>
    <small class="d-block text-slate-400 mt-1">This page executes a stored procedure to return 3 distinct result sets (Patient Profile, Appointment Logs, and Issued Prescriptions) in a single DB call.</small>
</div>

<!-- Patient Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="custom-card p-4 h-100">
            <div class="text-center mb-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-2" style="width: 70px; height: 70px;">
                    <i class="bi bi-person-fill fs-1"></i>
                </div>
                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($patient['name']); ?></h5>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                    <i class="bi bi-droplet-fill me-1"></i>Blood Group: <?php echo htmlspecialchars($patient['blood_group']); ?>
                </span>
            </div>
            <hr>
            <ul class="list-unstyled mb-0 small">
                <li class="mb-2"><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></li>
                <li class="mb-2"><strong>Age:</strong> <?php echo $patient['age']; ?> Years</li>
                <li class="mb-2"><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone']); ?></li>
                <li class="mb-2"><strong>Address:</strong> <?php echo htmlspecialchars($patient['address']); ?></li>
                <li><strong>Registered On:</strong> <?php echo date('M d, Y', strtotime($patient['created_at'])); ?></li>
            </ul>
        </div>
    </div>

    <!-- Right Side: Medical Records -->
    <div class="col-md-8">
        <!-- Appointment History -->
        <div class="custom-card mb-4">
            <div class="card-header fw-bold bg-white"><i class="bi bi-calendar2-check text-primary me-2"></i>Appointment History</div>
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Appt ID</th>
                            <th>Doctor & Dept</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr><td colspan="4" class="text-center text-muted">No appointments recorded for this patient.</td></tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $app): ?>
                                <tr>
                                    <td>#APP-<?php echo sprintf('%03d', $app['appointment_id']); ?></td>
                                    <td>
                                        <strong class="d-block"><?php echo htmlspecialchars($app['doctor_name']); ?></strong>
                                        <small class="text-muted"><?php echo htmlspecialchars($app['department_name']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($app['appointment_date']); ?> at <?php echo htmlspecialchars($app['appointment_time']); ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($app['status']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Prescriptions -->
        <div class="custom-card">
            <div class="card-header fw-bold bg-white"><i class="bi bi-prescription text-primary me-2"></i>Issued Prescriptions</div>
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($prescriptions)): ?>
                            <tr><td colspan="4" class="text-center text-muted">No prescriptions recorded for this patient.</td></tr>
                        <?php else: ?>
                            <?php foreach ($prescriptions as $pr): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pr['doctor_name']); ?></td>
                                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($pr['medicine_name']); ?></td>
                                    <td><?php echo htmlspecialchars($pr['dosage']); ?></td>
                                    <td><span class="badge bg-secondary-subtle text-dark"><?php echo htmlspecialchars($pr['duration']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
