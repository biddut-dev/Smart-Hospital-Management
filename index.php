<?php
include "connection.php";

$res1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM patients");
$row1 = mysqli_fetch_assoc($res1);
$total_patients = $row1['total'];

$res2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM doctors");
$row2 = mysqli_fetch_assoc($res2);
$total_doctors = $row2['total'];

$res3 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments");
$row3 = mysqli_fetch_assoc($res3);
$total_appointments = $row3['total'];

$res4 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM medicines");
$row4 = mysqli_fetch_assoc($res4);
$total_medicines = $row4['total'];

$sql_app = "SELECT appointments.*, patients.name AS patient_name, doctors.name AS doctor_name 
            FROM appointments
            JOIN patients ON appointments.patient_id = patients.id
            JOIN doctors ON appointments.doctor_id = doctors.id
            ORDER BY appointments.id DESC LIMIT 5";
$recent_app_result = mysqli_query($conn, $sql_app);

$sql_doc = "SELECT doctors.*, departments.name AS department_name 
            FROM doctors
            LEFT JOIN departments ON doctors.department_id = departments.id
            ORDER BY doctors.id DESC LIMIT 5";
$recent_doc_result = mysqli_query($conn, $sql_doc);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Smart Hospital Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "nav.php"; ?>

<div class="container">
    <div class="page-header">
        <h2>Hospital Management Dashboard</h2>
        <a href="patient/add.html" class="btn btn-primary">+ Add New Patient</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <p>Total Patients</p>
            <h3><?php echo $total_patients; ?></h3>
            <a href="patient/index.php" class="btn btn-primary">Manage Patients</a>
        </div>
        <div class="stat-card" style="border-left-color: #198754;">
            <p>Total Doctors</p>
            <h3 style="color: #198754;"><?php echo $total_doctors; ?></h3>
            <a href="doctor/index.php" class="btn btn-success">Manage Doctors</a>
        </div>
        <div class="stat-card" style="border-left-color: #ffc107;">
            <p>Total Appointments</p>
            <h3 style="color: #ffc107;"><?php echo $total_appointments; ?></h3>
            <a href="appointment/index.php" class="btn btn-warning">Manage Appointments</a>
        </div>
        <div class="stat-card" style="border-left-color: #0dcaf0;">
            <p>Total Medicines</p>
            <h3 style="color: #0dcaf0;"><?php echo $total_medicines; ?></h3>
            <a href="medicine/index.php" class="btn btn-secondary">Manage Medicines</a>
        </div>
    </div>

    <div class="card">
        <div class="page-header" style="margin-bottom: 10px;">
            <h3>Recent Appointments</h3>
            <a href="appointment/index.php" class="btn btn-warning">View All Appointments</a>
        </div>
        <table>
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php
            while ($app = mysqli_fetch_assoc($recent_app_result)) {
            ?>
            <tr>
                <td><?php echo $app['patient_name']; ?></td>
                <td><?php echo $app['doctor_name']; ?></td>
                <td><?php echo $app['appointment_date']; ?></td>
                <td><?php echo $app['appointment_time']; ?></td>
                <td><strong><?php echo $app['status']; ?></strong></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>

    <div class="card">
        <div class="page-header" style="margin-bottom: 10px;">
            <h3>Active Doctors List</h3>
            <a href="doctor/index.php" class="btn btn-success">View All Doctors</a>
        </div>
        <table>
            <tr>
                <th>Doctor Name</th>
                <th>Department</th>
                <th>Phone</th>
                <th>Available Days</th>
            </tr>
            <?php
            while ($doc = mysqli_fetch_assoc($recent_doc_result)) {
            ?>
            <tr>
                <td><?php echo $doc['name']; ?></td>
                <td><?php echo $doc['department_name']; ?></td>
                <td><?php echo $doc['phone']; ?></td>
                <td><?php echo $doc['available_days']; ?></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>