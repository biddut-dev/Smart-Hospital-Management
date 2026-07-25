<?php
/**
 * Appointment Management - Index & Search
 * Demonstrates INNER JOIN across 4 tables (Appointments, Patients, Doctors, Departments)
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Appointments";
$path_prefix = '../';

$search = trim($_GET['search'] ?? '');
if (!empty($search)) {
    $stmt = $pdo->prepare("
        SELECT a.*, p.name AS patient_name, d.name AS doctor_name, dep.name AS department_name
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN doctors d ON a.doctor_id = d.id
        JOIN departments dep ON d.department_id = dep.id
        WHERE p.name LIKE :search OR d.name LIKE :search OR a.appointment_date LIKE :search OR a.status LIKE :search
        ORDER BY a.appointment_date DESC, a.appointment_time ASC
    ");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $pdo->query("
        SELECT a.*, p.name AS patient_name, d.name AS doctor_name, dep.name AS department_name
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN doctors d ON a.doctor_id = d.id
        JOIN departments dep ON d.department_id = dep.id
        ORDER BY a.appointment_date DESC, a.appointment_time ASC
    ");
}
$appointments = $stmt->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-calendar-event-fill text-primary me-2"></i>Appointment Management</h3>
        <p class="text-muted small mb-0">Schedule and manage patient doctor consultations</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-calendar-plus me-1"></i> Book New Appointment
    </a>
</div>

<div class="custom-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" action="index.php" class="d-flex gap-2" style="max-width: 400px; flex-grow: 1;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" id="tableSearchInput" class="form-control" placeholder="Search patient, doctor, date (YYYY-MM-DD)..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <?php if (!empty($search)): ?>
                <a href="index.php" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </form>
        <small class="text-muted">Total Scheduled: <strong><?php echo count($appointments); ?></strong> Appointments</small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Appt ID</th>
                    <th>Patient Name</th>
                    <th>Doctor & Department</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($appointments)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No appointments found.</td></tr>
                <?php else: ?>
                    <?php foreach ($appointments as $app): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border">APP-<?php echo sprintf('%04d', $app['id']); ?></span></td>
                            <td class="fw-bold text-slate-800"><?php echo htmlspecialchars($app['patient_name']); ?></td>
                            <td>
                                <div><strong class="text-primary"><?php echo htmlspecialchars($app['doctor_name']); ?></strong></div>
                                <small class="text-muted"><?php echo htmlspecialchars($app['department_name']); ?></small>
                            </td>
                            <td>
                                <div><i class="bi bi-calendar3 me-1 text-muted"></i><?php echo htmlspecialchars($app['appointment_date']); ?></div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i><?php echo date('h:i A', strtotime($app['appointment_time'])); ?></small>
                            </td>
                            <td>
                                <?php
                                    $st_class = ($app['status'] == 'Completed') ? 'bg-success-subtle text-success border-success-subtle' : (($app['status'] == 'Scheduled') ? 'bg-primary-subtle text-primary border-primary-subtle' : 'bg-danger-subtle text-danger border-danger-subtle');
                                ?>
                                <span class="badge <?php echo $st_class; ?> border px-2 py-1"><?php echo $app['status']; ?></span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $app['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit Status/Time">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $app['id']; ?>" class="btn btn-sm btn-outline-danger" title="Cancel/Delete" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
