<!DOCTYPE html>
<html>
<head>
    <title>Update Appointment Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$id               = $_POST['id'];
$patient_id       = $_POST['patient_id'];
$doctor_id        = $_POST['doctor_id'];
$appointment_date = $_POST['appointment_date'];
$appointment_time = $_POST['appointment_time'];
$status           = $_POST['status'];

$sql = "UPDATE appointments
        SET patient_id='$patient_id',
            doctor_id='$doctor_id',
            appointment_date='$appointment_date',
            appointment_time='$appointment_time',
            status='$status'
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Appointment Updated Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>Back to Appointments List</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Update Failed</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Appointments List</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>