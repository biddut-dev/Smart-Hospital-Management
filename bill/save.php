<!DOCTYPE html>
<html>
<head>
    <title>Generate Bill Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$patient_id    = intval($_POST['patient_id']);
$admission_id  = !empty($_POST['admission_id']) ? intval($_POST['admission_id']) : 'NULL';
$room_charge   = floatval($_POST['room_charge']);
$medicine_cost = floatval($_POST['medicine_cost']);
$total_amount  = $room_charge + $medicine_cost;
$status        = mysqli_real_escape_string($conn, $_POST['payment_status']);

$sql = "INSERT INTO bills (patient_id, admission_id, medicine_cost, room_charge, total_amount, payment_status)
        VALUES ($patient_id, $admission_id, $medicine_cost, $room_charge, $total_amount, '$status')";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Bill Invoice Generated Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View All Bills</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Generate Bill</h2>";
    echo "<br>";
    echo "<a href='add.php' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>
