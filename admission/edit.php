<?php
$depth = 1;
include "../connection.php";

$id = intval($_GET['id']);
$sql = "SELECT * FROM admissions WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$patients = mysqli_query($conn, "SELECT * FROM patients ORDER BY name ASC");
$rooms = mysqli_query($conn, "SELECT * FROM rooms ORDER BY room_number ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Admission - Smart Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 30px auto;">
        <h2>Edit Patient Admission</h2>
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
                <label>Assigned Room:</label>
                <select name="room_id" class="form-control" required>
                    <?php while($r = mysqli_fetch_assoc($rooms)) { ?>
                        <option value="<?php echo $r['id']; ?>" <?php if($r['id'] == $row['room_id']) echo 'selected'; ?>>
                            <?php echo $r['room_number']; ?> (<?php echo $r['room_type']; ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Admission Date:</label>
                    <input type="date" name="admission_date" class="form-control" value="<?php echo $row['admission_date']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Discharge Date:</label>
                    <input type="date" name="discharge_date" class="form-control" value="<?php echo $row['discharge_date']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Admission Status:</label>
                <select name="status" class="form-control" required>
                    <option value="Admitted" <?php if($row['status'] == 'Admitted') echo 'selected'; ?>>Admitted</option>
                    <option value="Discharged" <?php if($row['status'] == 'Discharged') echo 'selected'; ?>>Discharged</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">Update Admission</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
