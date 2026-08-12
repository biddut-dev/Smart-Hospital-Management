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

$res4 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM rooms");
$row4 = mysqli_fetch_assoc($res4);
$total_rooms = $row4['total'];

$res5 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM admissions WHERE status = 'Admitted'");
$row5 = mysqli_fetch_assoc($res5);
$active_admissions = $row5['total'];

$res6 = mysqli_query($conn, "SELECT SUM(total_amount) AS revenue FROM bills WHERE payment_status = 'Paid'");
$row6 = mysqli_fetch_assoc($res6);
$total_revenue = $row6['revenue'] ? $row6['revenue'] : 0.00;

$sql_adm = "SELECT admissions.*, patients.name AS patient_name, rooms.room_number, rooms.room_type 
            FROM admissions
            JOIN patients ON admissions.patient_id = patients.id
            JOIN rooms ON admissions.room_id = rooms.id
            WHERE admissions.status = 'Admitted'
            ORDER BY admissions.id DESC LIMIT 5";
$recent_adm_result = mysqli_query($conn, $sql_adm);

$sql_app = "SELECT appointments.*, patients.name AS patient_name, doctors.name AS doctor_name 
            FROM appointments
            JOIN patients ON appointments.patient_id = patients.id
            JOIN doctors ON appointments.doctor_id = doctors.id
            ORDER BY appointments.id DESC LIMIT 5";
$recent_app_result = mysqli_query($conn, $sql_app);
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
        <div>
            <a href="admission/add.php" class="btn btn-success">+ Admit Patient</a>
            <a href="patient/add.html" class="btn btn-primary">+ Add New Patient</a>
        </div>
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
            <p>Appointments</p>
            <h3 style="color: #ffc107;"><?php echo $total_appointments; ?></h3>
            <a href="appointment/index.php" class="btn btn-warning">Manage Appointments</a>
        </div>
        <div class="stat-card" style="border-left-color: #0dcaf0;">
            <p>Total Rooms</p>
            <h3 style="color: #0dcaf0;"><?php echo $total_rooms; ?></h3>
            <a href="room/index.php" class="btn btn-secondary">Manage Rooms</a>
        </div>
        <div class="stat-card" style="border-left-color: #fd7e14;">
            <p>Active Admissions</p>
            <h3 style="color: #fd7e14;"><?php echo $active_admissions; ?></h3>
            <a href="admission/index.php" class="btn btn-primary" style="background: #fd7e14;">In-Patient Wards</a>
        </div>
        <div class="stat-card" style="border-left-color: #20c997;">
            <p>Total Paid Revenue</p>
            <h3 style="color: #20c997;">৳ <?php echo number_format($total_revenue, 0); ?></h3>
            <a href="bill/index.php" class="btn btn-success" style="background: #20c997;">Manage Bills</a>
        </div>
    </div>

    <div class="card">
        <div class="page-header" style="margin-bottom: 10px;">
            <h3>Currently Admitted Patients (In-Patient Wards)</h3>
            <a href="admission/index.php" class="btn btn-primary">View All Admissions</a>
        </div>
        <table>
            <tr>
                <th>Patient Name</th>
                <th>Assigned Room</th>
                <th>Room Type</th>
                <th>Admission Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            if (mysqli_num_rows($recent_adm_result) > 0) {
                while ($adm = mysqli_fetch_assoc($recent_adm_result)) {
            ?>
            <tr>
                <td><strong><?php echo $adm['patient_name']; ?></strong></td>
                <td><?php echo $adm['room_number']; ?></td>
                <td><?php echo $adm['room_type']; ?></td>
                <td><?php echo $adm['admission_date']; ?></td>
                <td><span style="color: #0d6efd; font-weight: bold;"><?php echo $adm['status']; ?></span></td>
                <td><a href="admission/discharge.php?id=<?php echo $adm['id']; ?>" class="btn btn-success btn-sm">Discharge & Bill</a></td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6'>No active admissions currently.</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="card">
        <div class="page-header" style="margin-bottom: 10px;">
            <h3>Recent Doctor Appointments</h3>
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
</div>

</body>
</html>