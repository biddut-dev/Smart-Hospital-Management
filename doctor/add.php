<?php
include "../connection.php";
$depth = 1;

$dep_result = mysqli_query($conn, "SELECT * FROM departments");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 20px auto;">
        <h2>Add New Doctor</h2>
        <br>
        <form action="save.php" method="POST">
            <div class="form-group">
                <label>Doctor Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Department:</label>
                <select name="department_id" class="form-control" required>
                    <option value="">-- Select Department --</option>
                    <?php while($d = mysqli_fetch_assoc($dep_result)) { ?>
                        <option value="<?php echo $d['id']; ?>"><?php echo $d['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label>Available Days:</label>
                <input type="text" name="available_days" class="form-control" placeholder="e.g. Mon, Wed, Fri" required>
            </div>

            <button type="submit" class="btn btn-success">Save Doctor</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>