<!DOCTYPE html>
<html>
<head>
    <title>Add Department Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$name        = $_POST['name'];
$description = $_POST['description'];

$sql = "INSERT INTO departments (name, description) VALUES ('$name', '$description')";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Department Added Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View Departments</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Add Department</h2>";
    echo "<br>";
    echo "<a href='add.html' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>