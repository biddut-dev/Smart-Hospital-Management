<?php
$depth = 1;
include "../connection.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Room - Smart Hospital System</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include "../nav.php"; ?>

<div class="container">
    <div class="card" style="max-width: 600px; margin: 30px auto;">
        <h2>Add New Room / Ward</h2>
        <br>
        <form action="save.php" method="POST">
            <div class="form-group">
                <label>Room Number / Code:</label>
                <input type="text" name="room_number" class="form-control" placeholder="e.g. Cabin-205, General-103, ICU-302" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Room Category / Type:</label>
                    <select name="room_type" class="form-control" required>
                        <option value="General">General Ward</option>
                        <option value="Private">Private Cabin</option>
                        <option value="ICU">ICU (Intensive Care)</option>
                        <option value="VIP">VIP Suite</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Floor Level:</label>
                    <input type="number" name="floor" class="form-control" value="1" min="1" max="10" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Charge Per Day (BDT):</label>
                    <input type="number" step="0.01" name="charge_per_day" class="form-control" placeholder="e.g. 1500.00" required>
                </div>

                <div class="form-group">
                    <label>Availability Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="Available">Available</option>
                        <option value="Occupied">Occupied</option>
                        <option value="Maintenance">Under Maintenance</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Room</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
