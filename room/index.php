<?php
$depth = 1;
include "../connection.php";

$sql = "SELECT * FROM rooms ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Room Management - Smart Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="page-header">
        <h2>Room & Ward Management</h2>
        <a href="add.php" class="btn btn-primary">+ Add New Room</a>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>Room Number</th>
                <th>Room Type</th>
                <th>Floor</th>
                <th>Daily Charge (BDT)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $status_color = ($row['status'] == 'Available') ? '#198754' : (($row['status'] == 'Occupied') ? '#dc3545' : '#ffc107');
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><strong><?php echo $row['room_number']; ?></strong></td>
                <td><?php echo $row['room_type']; ?></td>
                <td>Floor <?php echo $row['floor']; ?></td>
                <td>৳ <?php echo number_format($row['charge_per_day'], 2); ?></td>
                <td><span style="color: <?php echo $status_color; ?>; font-weight: bold;"><?php echo $row['status']; ?></span></td>
                <td>
                    <div class="action-buttons">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                    </div>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='7'>No rooms found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
