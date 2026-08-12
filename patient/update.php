<!DOCTYPE html>
<html>
<head>
    <title>Update Patient Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$id          = $_POST['id'];
$name        = $_POST['name'];
$gender      = $_POST['gender'];
$age         = $_POST['age'];
$phone       = $_POST['phone'];
$address     = $_POST['address'];
$blood_group = $_POST['blood_group'];

$sql = "UPDATE patients
        SET name='$name',
            gender='$gender',
            age='$age',
            phone='$phone',
            address='$address',
            blood_group='$blood_group'
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Patient Updated Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>Back to Patients List</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Update Failed</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Patients List</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>