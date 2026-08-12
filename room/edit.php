<?php
$depth = 1;
include "../connection.php";

$id = intval($_GET['id']);
$sql = "SELECT * FROM rooms WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Room - Smart Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 30px auto;">
        <h2>Edit Room Information</h2>
        <br>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="form-group">
                <label>Room Number / Code:</label>
                <input type="text" name="room_number" class="form-control" value="<?php echo $row['room_number']; ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Room Category / Type:</label>
                    <select name="room_type" class="form-control" required>
                        <option value="General" <?php if($row['room_type'] == 'General') echo 'selected'; ?>>General Ward</option>
                        <option value="Private" <?php if($row['room_type'] == 'Private') echo 'selected'; ?>>Private Cabin</option>
                        <option value="ICU" <?php if($row['room_type'] == 'ICU') echo 'selected'; ?>>ICU (Intensive Care)</option>
                        <option value="VIP" <?php if($row['room_type'] == 'VIP') echo 'selected'; ?>>VIP Suite</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Floor Level:</label>
                    <input type="number" name="floor" class="form-control" value="<?php echo $row['floor']; ?>" min="1" max="10" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Charge Per Day (BDT):</label>
                    <input type="number" step="0.01" name="charge_per_day" class="form-control" value="<?php echo $row['charge_per_day']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Availability Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="Available" <?php if($row['status'] == 'Available') echo 'selected'; ?>>Available</option>
                        <option value="Occupied" <?php if($row['status'] == 'Occupied') echo 'selected'; ?>>Occupied</option>
                        <option value="Maintenance" <?php if($row['status'] == 'Maintenance') echo 'selected'; ?>>Under Maintenance</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-warning">Update Room</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
