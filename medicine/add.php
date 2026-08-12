<!DOCTYPE html>
<html>
<head>
    <title>Add Medicine Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$name           = $_POST['name'];
$company        = $_POST['company'];
$price          = $_POST['price'];
$stock_quantity = $_POST['stock_quantity'];

$sql = "INSERT INTO medicines (name, company, price, stock_quantity)
        VALUES ('$name', '$company', '$price', '$stock_quantity')";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Medicine Added Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>View Medicine Inventory</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Failed to Add Medicine</h2>";
    echo "<br>";
    echo "<a href='add.html' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>