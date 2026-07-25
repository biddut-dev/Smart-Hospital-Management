<?php
/**
 * Prescription Management - Index
 * Demonstrates 3-way INNER JOIN & MySQL Trigger execution on Insert
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Prescriptions";
$path_prefix = '../';

$stmt = $pdo->query("
    SELECT pr.*, p.name AS patient_name, d.name AS doctor_name, m.name AS medicine_name, m.stock_quantity AS current_stock
    FROM prescriptions pr
    JOIN patients p ON pr.patient_id = p.id
    JOIN doctors d ON pr.doctor_id = d.id
    JOIN medicines m ON pr.medicine_id = m.id
    ORDER BY pr.created_at DESC
");
$prescriptions = $stmt->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-file-earmark-medical-fill text-primary me-2"></i>Prescription Management</h3>
        <p class="text-muted small mb-0">Create and track medical prescriptions for patients</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-plus-circle me-1"></i> Issue New Prescription
    </a>
</div>

<!-- DBMS Feature Showcase Alert -->
<div class="sql-demo-box shadow-sm mb-4">
    <div class="d-flex align-items-center mb-1">
        <i class="bi bi-lightning-charge-fill text-warning fs-5 me-2"></i>
        <strong class="text-white">DBMS Feature Applied: AFTER INSERT Trigger</strong>
    </div>
    <code>CREATE TRIGGER reduce_medicine_stock_after_prescription AFTER INSERT ON prescriptions ...</code>
    <small class="d-block text-slate-400 mt-1">Whenever a prescription is created below, MySQL automatically executes a database trigger to reduce the stock quantity of the prescribed medicine in real-time.</small>
</div>

<div class="custom-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="input-group input-group-sm" style="max-width: 300px;">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="tableSearchInput" class="form-control" placeholder="Search patient, doctor, medicine...">
        </div>
        <small class="text-muted">Total Prescriptions: <strong><?php echo count($prescriptions); ?></strong></small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Rx ID</th>
                    <th>Patient Name</th>
                    <th>Prescribing Doctor</th>
                    <th>Prescribed Medicine</th>
                    <th>Dosage & Instructions</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prescriptions)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No prescriptions issued yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($prescriptions as $pr): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border">RX-<?php echo sprintf('%04d', $pr['id']); ?></span></td>
                            <td class="fw-bold text-slate-800"><?php echo htmlspecialchars($pr['patient_name']); ?></td>
                            <td><i class="bi bi-person-badge text-muted me-1"></i><?php echo htmlspecialchars($pr['doctor_name']); ?></td>
                            <td>
                                <strong class="text-primary"><?php echo htmlspecialchars($pr['medicine_name']); ?></strong>
                                <small class="d-block text-muted">Rem. Stock: <?php echo $pr['current_stock']; ?> units</small>
                            </td>
                            <td><?php echo htmlspecialchars($pr['dosage']); ?></td>
                            <td><span class="badge bg-info-subtle text-info border border-info-subtle"><?php echo htmlspecialchars($pr['duration']); ?></span></td>
                            <td>
                                <a href="edit.php?id=<?php echo $pr['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $pr['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete prescription?');">
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
