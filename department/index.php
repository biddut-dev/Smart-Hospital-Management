<?php
include "../connection.php";
$depth = 1;

$sql = "SELECT * FROM departments";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Departments - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="page-header">
        <h2>Hospital Departments</h2>
        <a href="add.html" class="btn btn-primary">+ Add New Department</a>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>Department Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this department?');">Delete</a>
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