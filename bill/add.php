<?php
$depth = 1;
include "../connection.php";

$patients = mysqli_query($conn, "SELECT * FROM patients ORDER BY name ASC");
$admissions = mysqli_query($conn, "SELECT admissions.*, patients.name AS patient_name, rooms.room_number 
                                  FROM admissions 
                                  JOIN patients ON admissions.patient_id = patients.id 
                                  JOIN rooms ON admissions.room_id = rooms.id 
                                  ORDER BY admissions.id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Manual Bill - Smart Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 30px auto;">
        <h2>Generate Bill / Invoice</h2>
        <br>

        <form action="save.php" method="POST">
            <div class="form-group">
                <label>Select Patient:</label>
                <select name="patient_id" class="form-control" required>
                    <option value="">-- Choose Patient --</option>
                    <?php while($p = mysqli_fetch_assoc($patients)) { ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?> (Phone: <?php echo $p['phone']; ?>)</option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Link Admission Record (Optional):</label>
                <select name="admission_id" class="form-control">
                    <option value="">-- Out-Patient / None --</option>
                    <?php while($a = mysqli_fetch_assoc($admissions)) { ?>
                        <option value="<?php echo $a['id']; ?>">
                            Adm #<?php echo $a['id']; ?> - <?php echo $a['patient_name']; ?> (Room: <?php echo $a['room_number']; ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Room Charges (BDT):</label>
                    <input type="number" step="0.01" name="room_charge" class="form-control" value="0.00" required>
                </div>

                <div class="form-group">
                    <label>Medicine / Pharmacy Cost (BDT):</label>
                    <input type="number" step="0.01" name="medicine_cost" class="form-control" value="0.00" required>
                </div>
            </div>

            <div class="form-group">
                <label>Payment Status:</label>
                <select name="payment_status" class="form-control" required>
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Generate Invoice</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
