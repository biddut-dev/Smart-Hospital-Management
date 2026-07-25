<?php
/**
 * Smart Hospital Management System
 * Admin Dashboard
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$page_title = "Dashboard";
$path_prefix = './';

// Fetch Dashboard Metrics using SQL Aggregate functions
$total_patients = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$total_doctors = $pdo->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
$today_appointments = $pdo->query("SELECT COUNT(*) FROM appointments WHERE appointment_date = CURRENT_DATE()")->fetchColumn();
$available_rooms = $pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'Available'")->fetchColumn();
$total_medicines = $pdo->query("SELECT COUNT(*) FROM medicines")->fetchColumn();

// Fetch Recent Appointments with INNER JOIN
$stmt_recent_app = $pdo->query("
    SELECT a.id, p.name AS patient_name, d.name AS doctor_name, dep.name AS department_name, a.appointment_date, a.appointment_time, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN doctors d ON a.doctor_id = d.id
    JOIN departments dep ON d.department_id = dep.id
    ORDER BY a.created_at DESC LIMIT 5
");
$recent_appointments = $stmt_recent_app->fetchAll();

// Fetch Active Admissions using Database View (vw_active_admissions)
$stmt_active_adm = $pdo->query("SELECT * FROM vw_active_admissions LIMIT 5");
$active_admissions = $stmt_active_adm->fetchAll();

include_once __DIR__ . '/includes/header.php';
?>

<!-- Page Title & Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-slate-800 mb-1"><i class="bi bi-speedometer2 text-primary me-2"></i>Hospital Overview</h2>
        <p class="text-muted small mb-0">Real-time stats and management shortcut metrics</p>
    </div>
    <div>
        <a href="appointment/add.php" class="btn btn-primary btn-sm me-2 fw-semibold">
            <i class="bi bi-plus-circle me-1"></i> Book Appointment
        </a>
        <a href="patient/add.php" class="btn btn-outline-primary btn-sm fw-semibold">
            <i class="bi bi-person-plus me-1"></i> Add Patient
        </a>
    </div>
</div>

<!-- Key Stat Cards (5 Cards as required) -->
<div class="row g-3 mb-4">
    <!-- Total Patients -->
    <div class="col-12 col-sm-6 col-xl-2-4 col-md-4">
        <div class="stat-card d-flex align-items-center">
            <div class="stat-icon icon-blue me-3">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Total Patients</small>
                <h3 class="fw-bold mb-0 text-slate-800"><?php echo number_format($total_patients); ?></h3>
            </div>
        </div>
    </div>

    <!-- Total Doctors -->
    <div class="col-12 col-sm-6 col-xl-2-4 col-md-4">
        <div class="stat-card d-flex align-items-center">
            <div class="stat-icon icon-purple me-3">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Total Doctors</small>
                <h3 class="fw-bold mb-0 text-slate-800"><?php echo number_format($total_doctors); ?></h3>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="col-12 col-sm-6 col-xl-2-4 col-md-4">
        <div class="stat-card d-flex align-items-center">
            <div class="stat-icon icon-green me-3">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Today's Appts</small>
                <h3 class="fw-bold mb-0 text-slate-800"><?php echo number_format($today_appointments); ?></h3>
            </div>
        </div>
    </div>

    <!-- Available Rooms -->
    <div class="col-12 col-sm-6 col-xl-2-4 col-md-6">
        <div class="stat-card d-flex align-items-center">
            <div class="stat-icon icon-orange me-3">
                <i class="bi bi-door-open-fill"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Available Rooms</small>
                <h3 class="fw-bold mb-0 text-slate-800"><?php echo number_format($available_rooms); ?></h3>
            </div>
        </div>
    </div>

    <!-- Total Medicines -->
    <div class="col-12 col-sm-6 col-xl-2-4 col-md-6">
        <div class="stat-card d-flex align-items-center">
            <div class="stat-icon icon-red me-3">
                <i class="bi bi-capsule"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Total Medicines</small>
                <h3 class="fw-bold mb-0 text-slate-800"><?php echo number_format($total_medicines); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Content Grid -->
<div class="row g-4">
    <!-- Recent Appointments Table -->
    <div class="col-12 col-lg-7">
        <div class="custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-calendar-event me-2 text-primary"></i>Recent Appointments</span>
                <a href="appointment/index.php" class="btn btn-sm btn-link text-decoration-none">View All &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_appointments)): ?>
                            <tr><td colspan="4" class="text-center text-muted">No appointments recorded.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recent_appointments as $app): ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($app['patient_name']); ?></td>
                                    <td>
                                        <div><?php echo htmlspecialchars($app['doctor_name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($app['department_name']); ?></small>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($app['appointment_date']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($app['appointment_time']); ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                            $st_class = ($app['status'] == 'Completed') ? 'bg-success-subtle text-success' : (($app['status'] == 'Scheduled') ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger');
                                        ?>
                                        <span class="badge <?php echo $st_class; ?> px-2 py-1"><?php echo $app['status']; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Currently Admitted Patients (Demonstrating DB View) -->
    <div class="col-12 col-lg-5">
        <div class="custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-hospital me-2 text-primary"></i>Active Admissions (View Query)</span>
                <a href="admission/index.php" class="btn btn-sm btn-link text-decoration-none">Manage &rarr;</a>
            </div>
            <div class="p-3">
                <?php if (empty($active_admissions)): ?>
                    <p class="text-center text-muted my-3">No active admissions.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($active_admissions as $adm): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($adm['patient_name']); ?></h6>
                                    <small class="text-muted">Room: <strong><?php echo htmlspecialchars($adm['room_number']); ?></strong> (<?php echo htmlspecialchars($adm['room_type']); ?>)</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning text-dark mb-1">Admitted <?php echo $adm['total_days']; ?> Day(s)</span>
                                    <small class="d-block text-muted">$<?php echo number_format($adm['charge_per_day'], 2); ?>/day</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
