<?php
/**
 * Patient Management - Index & Search
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Patients";
$path_prefix = '../';

// Search filter logic
$search = trim($_GET['search'] ?? '');
if (!empty($search)) {
    $stmt = $pdo->prepare("
        SELECT * FROM patients 
        WHERE name LIKE :search OR phone LIKE :search OR blood_group LIKE :search
        ORDER BY id DESC
    ");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM patients ORDER BY id DESC");
}
$patients = $stmt->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Patient Management</h3>
        <p class="text-muted small mb-0">Register, edit, search, and view patient history</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-person-plus-fill me-1"></i> Register New Patient
    </a>
</div>

<div class="custom-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" action="index.php" class="d-flex gap-2" style="max-width: 400px; flex-grow: 1;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" id="tableSearchInput" class="form-control" placeholder="Search by name, phone, blood group..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <?php if (!empty($search)): ?>
                <a href="index.php" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </form>
        <small class="text-muted">Total Registered: <strong><?php echo count($patients); ?></strong> Patients</small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Gender / Age</th>
                    <th>Phone</th>
                    <th>Blood Group</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($patients)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No patients found.</td></tr>
                <?php else: ?>
                    <?php foreach ($patients as $p): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border">PAT-<?php echo sprintf('%04d', $p['id']); ?></span></td>
                            <td class="fw-bold text-slate-800">
                                <a href="view.php?id=<?php echo $p['id']; ?>" class="text-decoration-none text-primary">
                                    <?php echo htmlspecialchars($p['name']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($p['gender']); ?>, <?php echo $p['age']; ?> yrs</td>
                            <td><i class="bi bi-telephone text-muted me-1"></i><?php echo htmlspecialchars($p['phone']); ?></td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger px-2 py-1 border border-danger-subtle">
                                    <i class="bi bi-droplet-fill me-1"></i><?php echo htmlspecialchars($p['blood_group']); ?>
                                </span>
                            </td>
                            <td><small class="text-muted"><?php echo htmlspecialchars($p['address']); ?></small></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="view.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-info" title="View Details & Procedure History">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete patient record?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
