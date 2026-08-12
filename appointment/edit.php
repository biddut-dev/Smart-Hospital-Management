<?php
include "../connection.php";
$depth = 1;

$id = $_GET['id'];
$sql = "SELECT * FROM appointments WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$patients = mysqli_query($conn, "SELECT id, name FROM patients");
$doctors  = mysqli_query($conn, "SELECT id, name FROM doctors");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 20px auto;">
        <h2>Edit Appointment Details</h2>
        <br>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label>Select Patient:</label>
                <select name="patient_id" class="form-control" required>
                    <?php while($p = mysqli_fetch_assoc($patients)) { ?>
                        <option value="<?php echo $p['id']; ?>" <?php if($p['id'] == $row['patient_id']) echo 'selected'; ?>>
                            <?php echo $p['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Select Doctor:</label>
                <select name="doctor_id" class="form-control" required>
                    <?php while($d = mysqli_fetch_assoc($doctors)) { ?>
                        <option value="<?php echo $d['id']; ?>" <?php if($d['id'] == $row['doctor_id']) echo 'selected'; ?>>
                            <?php echo $d['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Appointment Date:</label>
                    <input type="date" name="appointment_date" class="form-control" value="<?php echo $row['appointment_date']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Appointment Time:</label>
                    <input type="time" name="appointment_time" class="form-control" value="<?php echo $row['appointment_time']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control" required>
                    <option value="Scheduled" <?php if($row['status'] == 'Scheduled') echo 'selected'; ?>>Scheduled</option>
                    <option value="Completed" <?php if($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    <option value="Cancelled" <?php if($row['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">Update Appointment</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>