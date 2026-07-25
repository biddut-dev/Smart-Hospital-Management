<?php
/**
 * DBMS Lab Reports Module
 * Showcases SQL Features: INNER JOIN, LEFT JOIN, GROUP BY, ORDER BY, COUNT, SUM, AVG, Views, Procedures & Triggers
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "DBMS Lab Reports";
$path_prefix = '../';

// Report 1: Department-wise Doctor Count & Appointment Statistics (INNER JOIN + GROUP BY + COUNT)
$sql1 = "SELECT dep.name AS department_name, 
                COUNT(DISTINCT d.id) AS total_doctors, 
                COUNT(a.id) AS total_appointments 
         FROM departments dep 
         INNER JOIN doctors d ON dep.id = d.department_id 
         LEFT JOIN appointments a ON d.id = a.doctor_id 
         GROUP BY dep.id, dep.name 
         ORDER BY total_appointments DESC";
$report1 = $pdo->query($sql1)->fetchAll();

// Report 2: Room Floor Analysis & Average Daily Rate (GROUP BY + AVG + SUM + COUNT)
$sql2 = "SELECT floor, 
                COUNT(id) AS total_rooms, 
                SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) AS available_rooms,
                AVG(charge_per_day) AS avg_charge, 
                MIN(charge_per_day) AS min_charge, 
                MAX(charge_per_day) AS max_charge 
         FROM rooms 
         GROUP BY floor 
         ORDER BY floor ASC";
$report2 = $pdo->query($sql2)->fetchAll();

// Report 3: Medicine Inventory Stock & Valuation Summary (SUM + AVG + COUNT)
$sql3 = "SELECT COUNT(id) AS total_medicines, 
                SUM(stock_quantity) AS total_stock_items, 
                AVG(price) AS avg_price, 
                SUM(price * stock_quantity) AS total_inventory_value 
         FROM medicines";
$report3 = $pdo->query($sql3)->fetch();

// Report 4: Active Admissions Summary via Database View (vw_active_admissions)
$sql4 = "SELECT * FROM vw_active_admissions ORDER BY total_days DESC";
$report4 = $pdo->query($sql4)->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>DBMS Lab SQL Reports</h3>
        <p class="text-muted small mb-0">Demonstrating Relational Algebra, Aggregations, Joins, Views, Procedures & Triggers</p>
    </div>
    <span class="badge bg-primary px-3 py-2 fs-6"><i class="bi bi-mortarboard-fill me-1"></i> DBMS Coursework Demo</span>
</div>

<!-- Section 1: Department Statistics (JOIN + GROUP BY + COUNT) -->
<div class="custom-card mb-4">
    <div class="card-header bg-white">
        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-diagram-3 me-2"></i>1. Department Doctor & Appointment Breakdown</h5>
        <small class="text-muted">Demonstrates <strong>INNER JOIN</strong>, <strong>LEFT JOIN</strong>, <strong>GROUP BY</strong>, <strong>COUNT()</strong>, and <strong>ORDER BY</strong></small>
    </div>
    <div class="p-3">
        <div class="sql-demo-box shadow-sm mb-3">
            <code><?php echo htmlspecialchars($sql1); ?></code>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Assigned Doctors (COUNT)</th>
                        <th>Total Appointments (COUNT)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report1 as $row): ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($row['department_name']); ?></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?php echo $row['total_doctors']; ?> Doctors</span></td>
                            <td><span class="badge bg-success-subtle text-success"><?php echo $row['total_appointments']; ?> Appointments</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Section 2: Room Floor Rates Analysis (AVG, SUM, GROUP BY) -->
<div class="custom-card mb-4">
    <div class="card-header bg-white">
        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-calculator me-2"></i>2. Room Floor Financial & Occupancy Analysis</h5>
        <small class="text-muted">Demonstrates Aggregate Functions: <strong>AVG()</strong>, <strong>SUM()</strong>, <strong>MIN()</strong>, <strong>MAX()</strong> with <strong>GROUP BY floor</strong></small>
    </div>
    <div class="p-3">
        <div class="sql-demo-box shadow-sm mb-3">
            <code><?php echo htmlspecialchars($sql2); ?></code>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th>Floor Level</th>
                        <th>Total Rooms</th>
                        <th>Available Rooms</th>
                        <th>Avg Daily Rate (AVG)</th>
                        <th>Rate Range (MIN - MAX)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report2 as $row): ?>
                        <tr>
                            <td class="fw-bold">Floor <?php echo $row['floor']; ?></td>
                            <td><?php echo $row['total_rooms']; ?> Rooms</td>
                            <td><span class="badge bg-success-subtle text-success"><?php echo $row['available_rooms']; ?> Available</span></td>
                            <td class="fw-bold text-primary">$<?php echo number_format($row['avg_charge'], 2); ?></td>
                            <td>$<?php echo number_format($row['min_charge'], 2); ?> - $<?php echo number_format($row['max_charge'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Section 3: Medicine Inventory Financial Summary (SUM & AVG) -->
<div class="custom-card mb-4">
    <div class="card-header bg-white">
        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-capsule-fill me-2"></i>3. Pharmacy Stock Valuation Summary</h5>
        <small class="text-muted">Demonstrates Multi-column Aggregations: <strong>SUM(price * stock_quantity)</strong> and <strong>AVG(price)</strong></small>
    </div>
    <div class="p-3">
        <div class="sql-demo-box shadow-sm mb-3">
            <code><?php echo htmlspecialchars($sql3); ?></code>
        </div>
        <div class="row g-3 text-center">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded border">
                    <small class="text-muted text-uppercase fw-bold">Total Products</small>
                    <h4 class="fw-bold text-slate-800 mb-0"><?php echo number_format($report3['total_medicines']); ?></h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded border">
                    <small class="text-muted text-uppercase fw-bold">Total Stock Items</small>
                    <h4 class="fw-bold text-slate-800 mb-0"><?php echo number_format($report3['total_stock_items']); ?> Units</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded border">
                    <small class="text-muted text-uppercase fw-bold">Average Medicine Price</small>
                    <h4 class="fw-bold text-primary mb-0">$<?php echo number_format($report3['avg_price'], 2); ?></h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded border">
                    <small class="text-muted text-uppercase fw-bold">Total Inventory Value</small>
                    <h4 class="fw-bold text-success mb-0">$<?php echo number_format($report3['total_inventory_value'], 2); ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 4: Querying Database Views (vw_active_admissions) -->
<div class="custom-card">
    <div class="card-header bg-white">
        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-eye-fill me-2"></i>4. Active Admissions Report via MySQL View</h5>
        <small class="text-muted">Demonstrates selecting from MySQL Database View: <code>vw_active_admissions</code></small>
    </div>
    <div class="p-3">
        <div class="sql-demo-box shadow-sm mb-3">
            <code><?php echo htmlspecialchars($sql4); ?></code>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0">
                <thead>
                    <tr>
                        <th>Adm ID</th>
                        <th>Patient Name</th>
                        <th>Assigned Room</th>
                        <th>Admission Date</th>
                        <th>Days Stayed (DATEDIFF)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($report4)): ?>
                        <tr><td colspan="5" class="text-center text-muted">No active admissions.</td></tr>
                    <?php else: ?>
                        <?php foreach ($report4 as $row): ?>
                            <tr>
                                <td>#ADM-<?php echo sprintf('%04d', $row['admission_id']); ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                <td>Room <?php echo htmlspecialchars($row['room_number']); ?> (<?php echo htmlspecialchars($row['room_type']); ?>)</td>
                                <td><?php echo htmlspecialchars($row['admission_date']); ?></td>
                                <td><span class="badge bg-warning text-dark"><?php echo $row['total_days']; ?> Day(s)</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
