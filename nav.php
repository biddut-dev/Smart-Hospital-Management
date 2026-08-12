<?php
$base = isset($depth) ? "../" : "./";
?>
<nav class="navbar">
    <h1>Smart Hospital Management System</h1>
    <ul class="nav-links">
        <li><a href="<?php echo $base; ?>index.php">Dashboard</a></li>
        <li><a href="<?php echo $base; ?>patient/index.php">Patients</a></li>
        <li><a href="<?php echo $base; ?>doctor/index.php">Doctors</a></li>
        <li><a href="<?php echo $base; ?>appointment/index.php">Appointments</a></li>
        <li><a href="<?php echo $base; ?>room/index.php">Rooms</a></li>
        <li><a href="<?php echo $base; ?>admission/index.php">Admissions</a></li>
        <li><a href="<?php echo $base; ?>bill/index.php">Billing</a></li>
        <li><a href="<?php echo $base; ?>medicine/index.php">Medicines</a></li>
        <li><a href="<?php echo $base; ?>department/index.php">Departments</a></li>
        <li><a href="<?php echo $base; ?>login.html">Login</a></li>
    </ul>
</nav>