<!DOCTYPE html>
<html>
<head>
    <title>Save Doctor Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$name           = $_POST['name'];
$department_id = $_POST['department_id'];
$phone          = $_POST['phone'];
$email          = $_POST['email'];
$available_days = $_POST['available_days'];

$sql = "INSERT INTO doctors (department_id, name, phone, email, available_days)
        VALUES ('$department_id', '$name', '$phone', '$email', '$available_days')";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Doctor Added Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-success'>View All Doctors</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Add Doctor</h2>";
    echo "<br>";
    echo "<a href='add.php' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>