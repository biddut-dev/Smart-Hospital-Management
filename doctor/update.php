<!DOCTYPE html>
<html>
<head>
    <title>Update Doctor Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$id             = $_POST['id'];
$name           = $_POST['name'];
$department_id = $_POST['department_id'];
$phone          = $_POST['phone'];
$email          = $_POST['email'];
$available_days = $_POST['available_days'];

$sql = "UPDATE doctors
        SET name='$name',
            department_id='$department_id',
            phone='$phone',
            email='$email',
            available_days='$available_days'
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Doctor Updated Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>Back to Doctors List</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Update Failed</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Doctors List</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>