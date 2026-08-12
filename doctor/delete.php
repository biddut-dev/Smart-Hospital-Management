<!DOCTYPE html>
<html>
<head>
    <title>Delete Doctor Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$id = $_GET['id'];
$sql = "DELETE FROM doctors WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Doctor Deleted Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>Back to Doctors List</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Delete Failed</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Doctors List</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>