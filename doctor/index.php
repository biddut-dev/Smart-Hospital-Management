<?php
/**
 * Doctor Management - Index
 * Demonstrates View Query (vw_doctor_details) & INNER JOIN
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Doctors";
$path_prefix = '../';

$search = trim($_GET['search'] ?? '');
if (!empty($search)) {
    $stmt = $pdo->prepare("
        SELECT * FROM vw_doctor_details 
        WHERE doctor_name LIKE :search OR department_name LIKE :search OR available_days LIKE :search
        ORDER BY doctor_id DESC
    ");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM vw_doctor_details ORDER BY doctor_id DESC");
}
$doctors = $stmt->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-person-badge-fill text-primary me-2"></i>Doctor Management</h3>
        <p class="text-muted small mb-0">Manage hospital specialists, departments, and schedules</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-person-plus-fill me-1"></i> Add Doctor
    </a>
</div>

<div class="custom-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" action="index.php" class="d-flex gap-2" style="max-width: 400px; flex-grow: 1;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" id="tableSearchInput" class="form-control" placeholder="Search doctor name or department..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <?php if (!empty($search)): ?>
                <a href="index.php" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </form>
        <small class="text-muted">Total Doctors: <strong><?php echo count($doctors); ?></strong></small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Doctor ID</th>
                    <th>Doctor Name</th>
                    <th>Department</th>
                    <th>Contact Info</th>
                    <th>Available Days</th>
                    <th>Appointments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($doctors)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No doctors found.</td></tr>
                <?php else: ?>
                    <?php foreach ($doctors as $doc): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border">DOC-<?php echo sprintf('%03d', $doc['doctor_id']); ?></span></td>
                            <td class="fw-bold text-slate-800"><?php echo htmlspecialchars($doc['doctor_name']); ?></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    <i class="bi bi-building me-1"></i><?php echo htmlspecialchars($doc['department_name']); ?>
                                </span>
                            </td>
                            <td>
                                <div><i class="bi bi-telephone me-1 text-muted"></i><?php echo htmlspecialchars($doc['phone']); ?></div>
                                <small class="text-muted"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($doc['email']); ?></small>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-dark"><?php echo htmlspecialchars($doc['available_days']); ?></span></td>
                            <td>
                                <span class="badge bg-info-subtle text-info px-2 py-1">
                                    <?php echo $doc['total_appointments']; ?> Booked
                                </span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $doc['doctor_id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $doc['doctor_id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to remove this doctor?');">
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
