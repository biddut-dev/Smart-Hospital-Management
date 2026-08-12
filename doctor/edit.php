<?php
include "../connection.php";
$depth = 1;

$id = $_GET['id'];
$sql = "SELECT * FROM doctors WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$dep_result = mysqli_query($conn, "SELECT * FROM departments");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 20px auto;">
        <h2>Edit Doctor Information</h2>
        <br>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label>Doctor Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
            </div>

            <div class="form-group">
                <label>Department:</label>
                <select name="department_id" class="form-control" required>
                    <?php while($d = mysqli_fetch_assoc($dep_result)) { ?>
                        <option value="<?php echo $d['id']; ?>" <?php if($d['id'] == $row['department_id']) echo 'selected'; ?>>
                            <?php echo $d['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Available Days:</label>
                <input type="text" name="available_days" class="form-control" value="<?php echo $row['available_days']; ?>" required>
            </div>

            <button type="submit" class="btn btn-warning">Update Doctor</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>