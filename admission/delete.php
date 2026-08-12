<!DOCTYPE html>
<html>
<head>
    <title>Delete Admission Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$id = $_GET['id'];
$sql = "DELETE FROM admissions WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Admission Record Deleted Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View All Admissions</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Delete Admission</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Admissions</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>
