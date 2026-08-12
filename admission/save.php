<!DOCTYPE html>
<html>
<head>
    <title>Save Admission Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$patient_id     = $_POST['patient_id'];
$room_id        = $_POST['room_id'];
$admission_date = $_POST['admission_date'];

$sql = "INSERT INTO admissions (patient_id, room_id, admission_date, status) 
        VALUES ('$patient_id', '$room_id', '$admission_date', 'Admitted')";

if (mysqli_query($conn, $sql)) {
    mysqli_query($conn, "UPDATE rooms SET status = 'Occupied' WHERE id = '$room_id'");
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Patient Admitted Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View All Admissions</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Admit Patient</h2>";
    echo "<br>";
    echo "<a href='add.php' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>
