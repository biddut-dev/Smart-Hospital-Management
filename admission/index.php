<?php
/**
 * Admission Management - Index
 * Demonstrates INNER JOIN across Admissions, Patients, and Rooms
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Admissions";
$path_prefix = '../';

$stmt = $pdo->query("
    SELECT adm.*, p.name AS patient_name, p.phone AS patient_phone, r.room_number, r.room_type, r.charge_per_day
    FROM admissions adm
    JOIN patients p ON adm.patient_id = p.id
    JOIN rooms r ON adm.room_id = r.id
    ORDER BY adm.status ASC, adm.admission_date DESC
");
$admissions = $stmt->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-hospital-fill text-primary me-2"></i>Admission Management</h3>
        <p class="text-muted small mb-0">Manage patient ward admissions, room assignments, and discharge dates</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-box-arrow-in-right me-1"></i> Admit Patient
    </a>
</div>

<div class="custom-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="input-group input-group-sm" style="max-width: 300px;">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="tableSearchInput" class="form-control" placeholder="Search patient or room...">
        </div>
        <small class="text-muted">Total Admissions: <strong><?php echo count($admissions); ?></strong></small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Adm ID</th>
                    <th>Patient Name</th>
                    <th>Assigned Room</th>
                    <th>Admission Date</th>
                    <th>Discharge Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($admissions)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No patient admissions recorded.</td></tr>
                <?php else: ?>
                    <?php foreach ($admissions as $adm): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border">ADM-<?php echo sprintf('%04d', $adm['id']); ?></span></td>
                            <td class="fw-bold text-slate-800">
                                <?php echo htmlspecialchars($adm['patient_name']); ?>
                                <small class="d-block text-muted"><?php echo htmlspecialchars($adm['patient_phone']); ?></small>
                            </td>
                            <td>
                                <strong class="text-primary"><?php echo htmlspecialchars($adm['room_number']); ?></strong>
                                <small class="text-muted">(<?php echo htmlspecialchars($adm['room_type']); ?> - $<?php echo number_format($adm['charge_per_day'], 2); ?>/day)</small>
                            </td>
                            <td><?php echo htmlspecialchars($adm['admission_date']); ?></td>
                            <td><?php echo $adm['discharge_date'] ? htmlspecialchars($adm['discharge_date']) : '<span class="text-muted fs-7">N/A (Active)</span>'; ?></td>
                            <td>
                                <?php if ($adm['status'] == 'Admitted'): ?>
                                    <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-clock me-1"></i>Admitted</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle me-1"></i>Discharged</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="edit.php?id=<?php echo $adm['id']; ?>" class="btn btn-outline-primary" title="Update / Discharge">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($adm['status'] == 'Discharged'): ?>
                                        <a href="../billing/add.php?admission_id=<?php echo $adm['id']; ?>" class="btn btn-outline-success" title="Generate Bill">
                                            <i class="bi bi-receipt"></i> Bill
                                        </a>
                                    <?php endif; ?>
                                    <a href="delete.php?id=<?php echo $adm['id']; ?>" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Delete admission record?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
