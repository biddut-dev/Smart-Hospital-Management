<?php
/**
 * Smart Hospital Management System
 * Shared Sidebar Navigation
 */
if (!isset($path_prefix)) {
    $path_prefix = './';
}
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<nav id="sidebar">
    <div class="sidebar-header shadow-sm">
        <h3><i class="bi bi-heart-pulse-fill me-2"></i>Smart HMS</h3>
        <small class="text-white-50">DBMS Lab Project</small>
    </div>

    <ul class="list-unstyled components">
        <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>dashboard.php">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'patient') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>patient/index.php">
                <i class="bi bi-people-fill"></i> Patients
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'doctor') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>doctor/index.php">
                <i class="bi bi-person-badge-fill"></i> Doctors
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'department') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>department/index.php">
                <i class="bi bi-building-fill"></i> Departments
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'appointment') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>appointment/index.php">
                <i class="bi bi-calendar-event-fill"></i> Appointments
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'room') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>room/index.php">
                <i class="bi bi-door-open-fill"></i> Rooms
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'admission') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>admission/index.php">
                <i class="bi bi-hospital-fill"></i> Admissions
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'medicine') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>medicine/index.php">
                <i class="bi bi-capsule"></i> Medicines
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'prescription') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>prescription/index.php">
                <i class="bi bi-file-earmark-medical-fill"></i> Prescriptions
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'billing') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>billing/index.php">
                <i class="bi bi-receipt-cutoff"></i> Billing
            </a>
        </li>
        <li class="<?php echo ($current_dir == 'reports') ? 'active' : ''; ?>">
            <a href="<?php echo $path_prefix; ?>reports/index.php">
                <i class="bi bi-bar-chart-line-fill"></i> DBMS Reports
            </a>
        </li>
    </ul>

    <div class="px-3 py-4 text-center mt-auto border-top border-secondary">
        <small class="text-slate-400 d-block mb-1">MySQL Normalized DB</small>
        <span class="badge bg-primary text-wrap">Triggers • Views • Procedures</span>
    </div>
</nav>
