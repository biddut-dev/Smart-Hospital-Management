<?php
include "../connection.php";
$depth = 1;

$sql = "SELECT * FROM medicines";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medicines Inventory - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="page-header">
        <h2>Pharmacy Medicine Inventory</h2>
        <a href="add.html" class="btn btn-primary">+ Add New Medicine</a>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>Medicine Name</th>
                <th>Company</th>
                <th>Price ($)</th>
                <th>Stock Quantity</th>
                <th>Actions</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['company']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['stock_quantity']; ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this medicine?');">Delete</a>
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