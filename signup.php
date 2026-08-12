<!DOCTYPE html>
<html>
<head>
    <title>Registration Status</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
include "connection.php";

$username = $_POST['username'];
$email    = $_POST['email'];
$password = $_POST['password'];

$sql = "INSERT INTO users(username, password, email) VALUES('$username', '$password', '$email')";

if (mysqli_query($conn, $sql)) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Registration Successful!</h2>";
    echo "<br>";
    echo "<a href='login.html' class='btn btn-primary'>Proceed to Login</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Registration Failed</h2>";
    echo "<br>";
    echo "<a href='signup.html' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>