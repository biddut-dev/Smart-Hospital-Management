<?php
/**
 * View & Print Hospital Bill Invoice
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Bill Invoice";
$path_prefix = '../';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("
    SELECT b.*, p.name AS patient_name, p.phone AS patient_phone, p.address AS patient_address,
           adm.admission_date, adm.discharge_date, r.room_number, r.room_type, r.charge_per_day
    FROM bills b
    JOIN patients p ON b.patient_id = p.id
    JOIN admissions adm ON b.admission_id = adm.id
    JOIN rooms r ON adm.room_id = r.id
    WHERE b.id = :id
");
$stmt->execute(['id' => $id]);
$bill = $stmt->fetch();

if (!$bill) {
    set_flash('error', "Invoice not found.");
    header("Location: index.php");
    exit;
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-file-earmark-spreadsheet text-primary me-2"></i>Invoice Details</h3>
        <p class="text-muted small mb-0">BILL-<?php echo sprintf('%04d', $bill['id']); ?></p>
    </div>
    <div>
        <a href="index.php" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <button onclick="printDocument('printableInvoice')" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Print Invoice</button>
    </div>
</div>

<!-- Printable Invoice Card Container -->
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">
        <div class="custom-card p-5 bg-white shadow-sm" id="printableInvoice">
            <!-- Hospital Branding Header -->
            <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                <div>
                    <h3 class="fw-bold text-primary mb-1"><i class="bi bi-hospital me-2"></i>Smart Hospital</h3>
                    <p class="text-muted small mb-0">123 Healthcare Boulevard, Metro City</p>
                    <p class="text-muted small mb-0">Phone: +1 (555) 800-4000 | Email: billing@smarthospital.org</p>
                </div>
                <div class="text-end">
                    <h4 class="fw-bold text-uppercase text-secondary mb-1">INVOICE</h4>
                    <span class="badge bg-light text-dark border fs-6">BILL-<?php echo sprintf('%04d', $bill['id']); ?></span>
                    <p class="text-muted small mt-2 mb-0">Date: <strong><?php echo date('M d, Y', strtotime($bill['created_at'])); ?></strong></p>
                </div>
            </div>

            <!-- Patient & Admission Info Grid -->
            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="text-uppercase text-muted fw-bold small">Billed To:</h6>
                    <h5 class="fw-bold text-slate-800 mb-1"><?php echo htmlspecialchars($bill['patient_name']); ?></h5>
                    <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i><?php echo htmlspecialchars($bill['patient_phone']); ?></p>
                    <p class="text-muted small mb-0"><?php echo htmlspecialchars($bill['patient_address']); ?></p>
                </div>
                <div class="col-6 text-end">
                    <h6 class="text-uppercase text-muted fw-bold small">Admission Reference:</h6>
                    <p class="mb-1">Ref ID: <strong>ADM-<?php echo sprintf('%04d', $bill['admission_id']); ?></strong></p>
                    <p class="mb-1">Room: <strong><?php echo htmlspecialchars($bill['room_number']); ?></strong> (<?php echo htmlspecialchars($bill['room_type']); ?>)</p>
                    <p class="mb-0">Period: <?php echo htmlspecialchars($bill['admission_date']); ?> to <?php echo $bill['discharge_date'] ?: 'Present'; ?></p>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Hospital Room Charge</strong>
                                <small class="d-block text-muted">Daily Rate: $<?php echo number_format($bill['charge_per_day'], 2); ?></small>
                            </td>
                            <td class="text-end fw-semibold">$<?php echo number_format($bill['room_charge'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Pharmacy & Medicine Expenses</strong>
                                <small class="d-block text-muted">Prescribed medications & lab supplies</small>
                            </td>
                            <td class="text-end fw-semibold">$<?php echo number_format($bill['medicine_cost'], 2); ?></td>
                        </tr>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td class="text-end fw-bold fs-5">Total Billed Amount:</td>
                            <td class="text-end fw-bold fs-5 text-primary">$<?php echo number_format($bill['total_amount'], 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Status & Signatures -->
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <div>
                    <span class="text-muted small me-2">Payment Status:</span>
                    <?php if ($bill['payment_status'] === 'Paid'): ?>
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>PAID IN FULL</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i>PAYMENT PENDING</span>
                    <?php endif; ?>
                </div>
                <div class="text-center" style="min-width: 200px;">
                    <div class="border-bottom pb-4"></div>
                    <small class="text-muted d-block mt-1">Authorized Cashier Signature</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
