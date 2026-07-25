<?php
/**
 * Room Management - Index
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Rooms";
$path_prefix = '../';

$stmt = $pdo->query("SELECT * FROM rooms ORDER BY floor ASC, room_number ASC");
$rooms = $stmt->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-door-open-fill text-primary me-2"></i>Room Management</h3>
        <p class="text-muted small mb-0">Track hospital wards, rooms, floor levels, and occupancy status</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-plus-circle me-1"></i> Add New Room
    </a>
</div>

<div class="custom-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="input-group input-group-sm" style="max-width: 300px;">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="tableSearchInput" class="form-control" placeholder="Search room number, type, floor...">
        </div>
        <small class="text-muted">Total Rooms: <strong><?php echo count($rooms); ?></strong></small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Room No.</th>
                    <th>Room Type</th>
                    <th>Floor</th>
                    <th>Charge / Day</th>
                    <th>Availability Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rooms)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No rooms recorded.</td></tr>
                <?php else: ?>
                    <?php foreach ($rooms as $r): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($r['room_number']); ?></td>
                            <td><span class="badge bg-secondary-subtle text-dark border"><?php echo htmlspecialchars($r['room_type']); ?></span></td>
                            <td>Floor <?php echo $r['floor']; ?></td>
                            <td class="fw-bold text-slate-800">$<?php echo number_format($r['charge_per_day'], 2); ?></td>
                            <td>
                                <?php
                                    $st_class = ($r['status'] == 'Available') ? 'bg-success-subtle text-success border-success-subtle' : (($r['status'] == 'Occupied') ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-warning-subtle text-warning border-warning-subtle');
                                ?>
                                <span class="badge <?php echo $st_class; ?> border px-2 py-1"><?php echo $r['status']; ?></span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
