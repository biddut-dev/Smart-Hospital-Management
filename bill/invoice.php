<?php
include "../connection.php";

$id = intval($_GET['id']);
$sql = "SELECT bills.*, patients.name AS patient_name, patients.phone AS patient_phone, patients.address, patients.blood_group,
               admissions.admission_date, admissions.discharge_date, rooms.room_number, rooms.room_type
        FROM bills
        JOIN patients ON bills.patient_id = patients.id
        LEFT JOIN admissions ON bills.admission_id = admissions.id
        LEFT JOIN rooms ON admissions.room_id = rooms.id
        WHERE bills.id = $id";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    die("Bill not found.");
}

$bill = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #BILL-<?php echo str_pad($bill['id'], 4, '0', STR_PAD_LEFT); ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; color: #333; padding: 20px; }
        .invoice-card { max-width: 700px; margin: 20px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #0d6efd; margin: 0; font-size: 26px; }
        .header p { margin: 5px 0 0 0; color: #6c757d; font-size: 14px; }
        .info-grid { display: flex; justify-content: space-between; margin-bottom: 25px; font-size: 14px; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .invoice-table th, .invoice-table td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        .invoice-table th { background: #e9ecef; color: #495057; }
        .total-row { font-size: 18px; font-weight: bold; background: #f1f3f5; }
        .status-stamp { display: inline-block; padding: 6px 15px; border-radius: 4px; font-weight: bold; text-transform: uppercase; }
        .paid { background: #d1e7dd; color: #0f5132; }
        .pending { background: #f8d7da; color: #842029; }
        .print-btn { background: #0d6efd; color: white; border: none; padding: 10px 20px; font-size: 15px; border-radius: 4px; cursor: pointer; }
        @media print { .print-btn { display: none; } body { background: white; } }
    </style>
</head>
<body>

<div class="invoice-card">
    <div style="text-align: right; margin-bottom: 10px;">
        <button class="print-btn" onclick="window.print();">🖨️ Print Receipt</button>
    </div>

    <div class="header">
        <h1>SMART HOSPITAL MANAGEMENT SYSTEM</h1>
        <p>123 Healthcare Avenue, Medical District, Dhaka | Phone: +880 1711-000111</p>
        <p><strong>OFFICIAL BILL INVOICE RECEIPT</strong></p>
    </div>

    <div class="info-grid">
        <div>
            <strong>Patient Details:</strong><br>
            Name: <?php echo $bill['patient_name']; ?><br>
            Phone: <?php echo $bill['patient_phone']; ?><br>
            Address: <?php echo $bill['address']; ?><br>
            Blood Group: <?php echo $bill['blood_group']; ?>
        </div>
        <div>
            <strong>Invoice Details:</strong><br>
            Invoice #: <strong>BILL-<?php echo str_pad($bill['id'], 4, '0', STR_PAD_LEFT); ?></strong><br>
            Date: <?php echo date('d M Y, h:i A', strtotime($bill['created_at'])); ?><br>
            Room: <?php echo $bill['room_number'] ? $bill['room_number'] . " (" . $bill['room_type'] . ")" : "N/A (Out-Patient)"; ?><br>
            Status: <span class="status-stamp <?php echo strtolower($bill['payment_status']); ?>"><?php echo $bill['payment_status']; ?></span>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount (BDT)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Hospital Room / Ward Daily Tariff Charge</td>
                <td style="text-align: right;">৳ <?php echo number_format($bill['room_charge'], 2); ?></td>
            </tr>
            <tr>
                <td>Pharmacy & Medication Expenses</td>
                <td style="text-align: right;">৳ <?php echo number_format($bill['medicine_cost'], 2); ?></td>
            </tr>
            <tr class="total-row">
                <td style="text-align: right;">TOTAL DUE:</td>
                <td style="text-align: right; color: #0d6efd;">৳ <?php echo number_format($bill['total_amount'], 2); ?></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 40px; display: flex; justify-content: space-between; font-size: 13px; color: #6c757d;">
        <div>
            <p>Prepared By: System Admin</p>
            <p>Signature: ______________________</p>
        </div>
        <div style="text-align: right;">
            <p>Thank you for choosing Smart Hospital!</p>
            <p>Authorized Stamp</p>
        </div>
    </div>
</div>

</body>
</html>
