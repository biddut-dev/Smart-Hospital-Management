<?php
$depth = 1;
include "../connection.php";

// Fetch patients
$patients = mysqli_query($conn, "SELECT * FROM patients ORDER BY name ASC");

// Fetch available rooms
$rooms = mysqli_query($conn, "SELECT * FROM rooms WHERE status = 'Available' ORDER BY room_number ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admit New Patient - Smart Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 30px auto;">
        <h2>In-Patient Admission Form</h2>
        <br>

        <form action="save.php" method="POST">
            <div class="form-group">
                <label>Select Patient:</label>
                <select name="patient_id" class="form-control" required>
                    <option value="">-- Choose Registered Patient --</option>
                    <?php while($p = mysqli_fetch_assoc($patients)) { ?>
                        <option value="<?php echo $p['id']; ?>">
                            <?php echo $p['name']; ?> (Phone: <?php echo $p['phone']; ?>, Blood: <?php echo $p['blood_group']; ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Select Available Room / Ward:</label>
                <select name="room_id" class="form-control" required>
                    <option value="">-- Choose Available Room --</option>
                    <?php 
                    if (mysqli_num_rows($rooms) > 0) {
                        while($r = mysqli_fetch_assoc($rooms)) { ?>
                            <option value="<?php echo $r['id']; ?>">
                                <?php echo $r['room_number']; ?> - <?php echo $r['room_type']; ?> (৳<?php echo number_format($r['charge_per_day'], 0); ?>/day)
                            </option>
                        <?php } 
                    } else {
                        echo "<option value='' disabled>No rooms currently available!</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Admission Date:</label>
                <input type="date" name="admission_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit Admission</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
