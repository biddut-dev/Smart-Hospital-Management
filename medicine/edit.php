<?php
include "../connection.php";
$depth = 1;

$id = $_GET['id'];
$sql = "SELECT * FROM medicines WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Medicine - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 20px auto;">
        <h2>Edit Medicine Details</h2>
        <br>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label>Medicine Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
            </div>

            <div class="form-group">
                <label>Company Name:</label>
                <input type="text" name="company" class="form-control" value="<?php echo $row['company']; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price ($):</label>
                    <input type="text" name="price" class="form-control" value="<?php echo $row['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Stock Quantity:</label>
                    <input type="number" name="stock_quantity" class="form-control" value="<?php echo $row['stock_quantity']; ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-warning">Update Medicine</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>