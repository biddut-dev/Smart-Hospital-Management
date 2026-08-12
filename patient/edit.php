<?php
include "../connection.php";
$depth = 1;

$id = $_GET['id'];
$sql = "SELECT * FROM patients WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 20px auto;">
        <h2>Edit Patient Information</h2>
        <br>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label>Patient Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="gender" class="form-control" required>
                        <option value="Male" <?php if($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if($row['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Age:</label>
                    <input type="number" name="age" class="form-control" value="<?php echo $row['age']; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Blood Group:</label>
                    <input type="text" name="blood_group" class="form-control" value="<?php echo $row['blood_group']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" class="form-control" rows="3" required><?php echo $row['address']; ?></textarea>
            </div>

            <button type="submit" class="btn btn-warning">Update Patient</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>