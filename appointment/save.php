<!DOCTYPE html>
<html>
<head>
    <title>Save Appointment Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$patient_id       = $_POST['patient_id'];
$doctor_id        = $_POST['doctor_id'];
$appointment_date = $_POST['appointment_date'];
$appointment_time = $_POST['appointment_time'];
$status           = $_POST['status'];

$sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status)
        VALUES ('$patient_id', '$doctor_id', '$appointment_date', '$appointment_time', '$status')";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Appointment Booked Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View Appointments</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Book Appointment</h2>";
    echo "<br>";
    echo "<a href='add.php' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>