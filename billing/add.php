<?php
/**
 * Generate Hospital Bill for Admission
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Generate Bill";
$path_prefix = '../';

// Fetch admissions for dropdown with patient name and room charge info
$admissions = $pdo->query("
    SELECT adm.id, p.id AS patient_id, p.name AS patient_name, r.room_number, r.charge_per_day, adm.admission_date, adm.discharge_date,
           GREATEST(1, DATEDIFF(COALESCE(adm.discharge_date, CURRENT_DATE()), adm.admission_date)) AS days_stayed
    FROM admissions adm
    JOIN patients p ON adm.patient_id = p.id
    JOIN rooms r ON adm.room_id = r.id
    ORDER BY adm.id DESC
")->fetchAll();

$selected_admission_id = intval($_GET['admission_id'] ?? 0);

$admission_id = $patient_id = $medicine_cost = $room_charge = $total_amount = '';
$payment_status = 'Pending';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admission_id = intval($_POST['admission_id'] ?? 0);
    $medicine_cost = floatval($_POST['medicine_cost'] ?? 0.00);
    $room_charge = floatval($_POST['room_charge'] ?? 0.00);
    $payment_status = trim($_POST['payment_status'] ?? 'Pending');

    if ($admission_id <= 0) {
        $error = "Please select an admission record.";
    } else {
        // Fetch patient_id for selected admission
        $stmt_adm = $pdo->prepare("SELECT patient_id FROM admissions WHERE id = :id");
        $stmt_adm->execute(['id' => $admission_id]);
        $adm_rec = $stmt_adm->fetch();

        if (!$adm_rec) {
            $error = "Selected admission record not found.";
        } else {
            $patient_id = $adm_rec['patient_id'];
            $total_amount = $medicine_cost + $room_charge;

            $stmt = $pdo->prepare("
                INSERT INTO bills (patient_id, admission_id, medicine_cost, room_charge, total_amount, payment_status)
                VALUES (:patient_id, :admission_id, :medicine_cost, :room_charge, :total_amount, :payment_status)
            ");
            if ($stmt->execute([
                'patient_id' => $patient_id,
                'admission_id' => $admission_id,
                'medicine_cost' => $medicine_cost,
                'room_charge' => $room_charge,
                'total_amount' => $total_amount,
                'payment_status' => $payment_status
            ])) {
                set_flash('success', "Billing invoice generated successfully.");
                header("Location: index.php");
                exit;
            } else {
                $error = "Failed to generate bill.";
            }
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-receipt text-primary me-2"></i>Generate Patient Bill</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>

        <div class="custom-card p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="add.php" id="billForm">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Select Admission Record <span class="text-danger">*</span></label>
                        <select class="form-select" name="admission_id" id="admissionSelect" required onchange="updateCalc()">
                            <option value="">-- Choose Patient Admission --</option>
                            <?php foreach ($admissions as $a): 
                                $est_charge = $a['days_stayed'] * $a['charge_per_day'];
                            ?>
                                <option value="<?php echo $a['id']; ?>" 
                                        data-days="<?php echo $a['days_stayed']; ?>" 
                                        data-daily="<?php echo $a['charge_per_day']; ?>"
                                        data-charge="<?php echo $est_charge; ?>"
                                        <?php echo ($selected_admission_id == $a['id']) ? 'selected' : ''; ?>>
                                    ADM-<?php echo sprintf('%04d', $a['id']); ?>: <?php echo htmlspecialchars($a['patient_name']); ?> (Room <?php echo htmlspecialchars($a['room_number']); ?>, <?php echo $a['days_stayed']; ?> Days = $<?php echo number_format($est_charge, 2); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Room Charge ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="room_charge" id="room_charge" placeholder="0.00" value="0.00" required oninput="updateTotal()">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Medicine Cost ($)</label>
                        <input type="number" step="0.01" class="form-control" name="medicine_cost" id="medicine_cost" placeholder="0.00" value="0.00" oninput="updateTotal()">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Payment Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="payment_status" required>
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-primary">Total Calculated Amount ($)</label>
                        <input type="text" class="form-control fw-bold text-primary bg-light" id="total_display" value="$0.00" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Generate Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateCalc() {
    const sel = document.getElementById("admissionSelect");
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.charge) {
        document.getElementById("room_charge").value = parseFloat(opt.dataset.charge).toFixed(2);
        updateTotal();
    }
}
function updateTotal() {
    const rm = parseFloat(document.getElementById("room_charge").value) || 0;
    const med = parseFloat(document.getElementById("medicine_cost").value) || 0;
    const tot = rm + med;
    document.getElementById("total_display").value = "$" + tot.toFixed(2);
}
document.addEventListener("DOMContentLoaded", updateCalc);
</script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
