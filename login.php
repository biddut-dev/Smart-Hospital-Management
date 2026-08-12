<!DOCTYPE html>
<html>
<head>
    <title>Login Status</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
include "connection.php";

$user = $_POST['username'];
$pass = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #198754;'>Login Successful!</h2>";
    echo "<br>";
    echo "<a href='index.php' class='btn btn-primary'>Go to Dashboard</a>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='container' style='text-align: center; margin-top: 60px;'>";
    echo "<div class='card'>";
    echo "<h2 style='color: #dc3545;'>Invalid Username or Password</h2>";
    echo "<br>";
    echo "<a href='login.html' class='btn btn-secondary'>Try Again</a>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>