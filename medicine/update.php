<!DOCTYPE html>
<html>
<head>
    <title>Update Medicine Result</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php
include "../connection.php";

$id             = $_POST['id'];
$name           = $_POST['name'];
$company        = $_POST['company'];
$price          = $_POST['price'];
$stock_quantity = $_POST['stock_quantity'];

$sql = "UPDATE medicines
        SET name='$name',
            company='$company',
            price='$price',
            stock_quantity='$stock_quantity'
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Medicine Updated Successfully!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>Back to Medicine Inventory</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Update Failed</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Medicine Inventory</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>