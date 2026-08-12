<?php
$depth = 1;
include "../connection.php";

$sql = "SELECT admissions.*, patients.name AS patient_name, patients.phone AS patient_phone, 
               rooms.room_number, rooms.room_type, rooms.charge_per_day
        FROM admissions
        JOIN patients ON admissions.patient_id = patients.id
        JOIN rooms ON admissions.room_id = rooms.id
        ORDER BY admissions.id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>In-Patient Admissions - Smart Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="page-header">
        <h2>In-Patient Admissions</h2>
        <a href="add.php" class="btn btn-primary">+ Admit New Patient</a>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Assigned Room</th>
                <th>Room Type</th>
                <th>Admission Date</th>
                <th>Discharge Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $status_badge = ($row['status'] == 'Admitted') ? '#0d6efd' : '#198754';
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><strong><?php echo $row['patient_name']; ?></strong><br><small style="color: #6c757d;"><?php echo $row['patient_phone']; ?></small></td>
                <td><?php echo $row['room_number']; ?></td>
                <td><?php echo $row['room_type']; ?> (৳<?php echo number_format($row['charge_per_day'], 0); ?>/day)</td>
                <td><?php echo $row['admission_date']; ?></td>
                <td><?php echo $row['discharge_date'] ? $row['discharge_date'] : '<em>N/A (Active)</em>'; ?></td>
                <td><span style="color: <?php echo $status_badge; ?>; font-weight: bold;"><?php echo $row['status']; ?></span></td>
                <td>
                    <div class="action-buttons">
                        <?php if ($row['status'] == 'Admitted') { ?>
                            <a href="discharge.php?id=<?php echo $row['id']; ?>" class="btn btn-success" onclick="return confirm('Discharge this patient and generate bill?');">Discharge Patient</a>
                        <?php } ?>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this admission record?');">Delete</a>
                    </div>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='8'>No patient admissions found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
