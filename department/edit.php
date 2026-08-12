<?php
include "../connection.php";
$depth = 1;

$id = $_GET['id'];
$sql = "SELECT * FROM departments WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Department - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 20px auto;">
        <h2>Edit Department Details</h2>
        <br>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label>Department Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control" rows="4"><?php echo $row['description']; ?></textarea>
            </div>

            <button type="submit" class="btn btn-warning">Update Department</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>