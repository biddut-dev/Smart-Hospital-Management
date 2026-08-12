<?php
$depth = 1;
include "../connection.php";

$sql = "SELECT bills.*, patients.name AS patient_name, patients.phone AS patient_phone,
               admissions.admission_date, admissions.discharge_date
        FROM bills
        JOIN patients ON bills.patient_id = patients.id
        LEFT JOIN admissions ON bills.admission_id = admissions.id
        ORDER BY bills.id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Billing & Invoices - Smart Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="page-header">
        <h2>Billing \& Invoices</h2>
        <a href="add.php" class="btn btn-primary">+ Generate Manual Bill</a>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>Bill #</th>
                <th>Patient Name</th>
                <th>Room Charge</th>
                <th>Medicine Cost</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $status_color = ($row['payment_status'] == 'Paid') ? '#198754' : '#dc3545';
            ?>
            <tr>
                <td>#BILL-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                <td><strong><?php echo $row['patient_name']; ?></strong><br><small style="color: #6c757d;"><?php echo $row['patient_phone']; ?></small></td>
                <td>৳ <?php echo number_format($row['room_charge'], 2); ?></td>
                <td>৳ <?php echo number_format($row['medicine_cost'], 2); ?></td>
                <td><strong>৳ <?php echo number_format($row['total_amount'], 2); ?></strong></td>
                <td><span style="color: <?php echo $status_color; ?>; font-weight: bold;"><?php echo $row['payment_status']; ?></span></td>
                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="invoice.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" target="_blank">Print Invoice</a>
                        <?php if ($row['payment_status'] == 'Pending') { ?>
                            <a href="pay.php?id=<?php echo $row['id']; ?>" class="btn btn-success" onclick="return confirm('Mark this bill as PAID?');">Mark Paid</a>
                        <?php } ?>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this bill?');">Delete</a>
                    </div>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='8'>No bill records found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
