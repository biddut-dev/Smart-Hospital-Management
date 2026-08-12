<!DOCTYPE html>
<html>
<head>
    <title>Update Admission Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$id             = intval($_POST['id']);
$patient_id     = intval($_POST['patient_id']);
$room_id        = intval($_POST['room_id']);
$admission_date = mysqli_real_escape_string($conn, $_POST['admission_date']);
$discharge_date = !empty($_POST['discharge_date']) ? "'" . mysqli_real_escape_string($conn, $_POST['discharge_date']) . "'" : "NULL";
$status         = mysqli_real_escape_string($conn, $_POST['status']);

$sql = "UPDATE admissions SET 
        patient_id = $patient_id,
        room_id = $room_id,
        admission_date = '$admission_date',
        discharge_date = $discharge_date,
        status = '$status'
        WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    if ($status == 'Discharged') {
        mysqli_query($conn, "UPDATE rooms SET status = 'Available' WHERE id = $room_id");
    } else {
        mysqli_query($conn, "UPDATE rooms SET status = 'Occupied' WHERE id = $room_id");
    }
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Admission Updated Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View All Admissions</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Update Admission</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Admissions</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>
