<?php
include "../connection.php";
$depth = 1;

$sql = "SELECT doctors.*, departments.name AS department_name 
        FROM doctors 
        LEFT JOIN departments ON doctors.department_id = departments.id";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctors List - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="page-header">
        <h2>All Hospital Doctors</h2>
        <a href="add.php" class="btn btn-success">+ Add New Doctor</a>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>Doctor Name</th>
                <th>Department</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Available Days</th>
                <th>Actions</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['department_name']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['available_days']; ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this doctor?');">Delete</a>
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>