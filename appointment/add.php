<?php
include "../connection.php";
$depth = 1;

$patients = mysqli_query($conn, "SELECT id, name FROM patients");
$doctors  = mysqli_query($conn, "SELECT id, name FROM doctors");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment - Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 20px auto;">
        <h2>Book New Appointment</h2>
        <br>
        <form action="save.php" method="POST">
            <div class="form-group">
                <label>Select Patient:</label>
                <select name="patient_id" class="form-control" required>
                    <option value="">-- Choose Patient --</option>
                    <?php while($p = mysqli_fetch_assoc($patients)) { ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Select Doctor:</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">-- Choose Doctor --</option>
                    <?php while($d = mysqli_fetch_assoc($doctors)) { ?>
                        <option value="<?php echo $d['id']; ?>"><?php echo $d['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Appointment Date:</label>
                    <input type="date" name="appointment_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Appointment Time:</label>
                    <input type="time" name="appointment_time" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control" required>
                    <option value="Scheduled">Scheduled</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">Book Appointment</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>