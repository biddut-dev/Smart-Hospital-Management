<?php
/**
 * Billing Management - Index
 * Demonstrates DB View (vw_patient_billing_summary) & Aggregate SUM calculations
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Billing";
$path_prefix = '../';

// Query View: vw_patient_billing_summary
$stmt = $pdo->query("SELECT * FROM vw_patient_billing_summary ORDER BY bill_id DESC");
$bills = $stmt->fetchAll();

// Total revenue aggregate calculations (SUM)
$total_revenue = $pdo->query("SELECT SUM(total_amount) FROM bills WHERE payment_status = 'Paid'")->fetchColumn() ?: 0.00;
$pending_revenue = $pdo->query("SELECT SUM(total_amount) FROM bills WHERE payment_status = 'Pending'")->fetchColumn() ?: 0.00;

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-receipt-cutoff text-primary me-2"></i>Billing Management</h3>
        <p class="text-muted small mb-0">Generate, view, and print hospital billing invoices</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-file-earmark-plus me-1"></i> Generate New Bill
    </a>
</div>

<!-- Revenue Metrics -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card border-start border-4 border-success d-flex align-items-center">
            <div class="stat-icon icon-green me-3">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-bold">Total Paid Revenue (SUM)</small>
                <h3 class="fw-bold text-success mb-0">$<?php echo number_format($total_revenue, 2); ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card border-start border-4 border-warning d-flex align-items-center">
            <div class="stat-icon icon-orange me-3">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-bold">Pending Receivables (SUM)</small>
                <h3 class="fw-bold text-warning mb-0">$<?php echo number_format($pending_revenue, 2); ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="input-group input-group-sm" style="max-width: 300px;">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="tableSearchInput" class="form-control" placeholder="Search patient name...">
        </div>
        <small class="text-muted">Total Invoices: <strong><?php echo count($bills); ?></strong></small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Patient Name</th>
                    <th>Admission Ref</th>
                    <th>Medicine Cost</th>
                    <th>Room Charge</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bills)): ?>
                    <tr><td colspan="8" class="text-center text-muted">No billing invoices generated.</td></tr>
                <?php else: ?>
                    <?php foreach ($bills as $b): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border">BILL-<?php echo sprintf('%04d', $b['bill_id']); ?></span></td>
                            <td class="fw-bold text-slate-800"><?php echo htmlspecialchars($b['patient_name']); ?></td>
                            <td><span class="badge bg-secondary-subtle text-dark">ADM-<?php echo sprintf('%04d', $b['admission_id']); ?></span></td>
                            <td>$<?php echo number_format($b['medicine_cost'], 2); ?></td>
                            <td>$<?php echo number_format($b['room_charge'], 2); ?></td>
                            <td class="fw-bold text-primary">$<?php echo number_format($b['total_amount'], 2); ?></td>
                            <td>
                                <?php if ($b['payment_status'] == 'Paid'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle me-1"></i>Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1"><i class="bi bi-clock me-1"></i>Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="view.php?id=<?php echo $b['bill_id']; ?>" class="btn btn-sm btn-outline-info me-1" title="Print Invoice">
                                    <i class="bi bi-printer"></i> View & Print
                                </a>
                                <a href="delete.php?id=<?php echo $b['bill_id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete invoice?');">
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
