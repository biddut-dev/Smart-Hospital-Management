<?php
include "../connection.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "UPDATE bills SET payment_status = 'Paid' WHERE id = $id";
    mysqli_query($conn, $sql);
}

header("Location: index.php");
exit();
?>
