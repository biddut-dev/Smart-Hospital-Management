<!DOCTYPE html>
<html>
<head>
    <title>Update Room Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$id          = intval($_POST['id']);
$room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
$room_type   = mysqli_real_escape_string($conn, $_POST['room_type']);
$floor       = intval($_POST['floor']);
$charge      = floatval($_POST['charge_per_day']);
$status      = mysqli_real_escape_string($conn, $_POST['status']);

$sql = "UPDATE rooms SET 
        room_number = '$room_number',
        room_type = '$room_type',
        floor = '$floor',
        charge_per_day = '$charge',
        status = '$status'
        WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Room Updated Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View All Rooms</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Update Room</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Rooms</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>
