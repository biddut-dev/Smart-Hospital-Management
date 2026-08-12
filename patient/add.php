<!DOCTYPE html>
<html>
<head>
    <title>Add Patient Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$name        = $_POST['name'];
$gender      = $_POST['gender'];
$age         = $_POST['age'];
$phone       = $_POST['phone'];
$address     = $_POST['address'];
$blood_group = $_POST['blood_group'];

$sql = "INSERT INTO patients (name, gender, age, phone, address, blood_group)
        VALUES ('$name', '$gender', '$age', '$phone', '$address', '$blood_group')";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Patient Added Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View All Patients</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Add Patient</h2>";
    echo "<br>";
    echo "<a href='add.html' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>