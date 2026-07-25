<?php
/**
 * Department Management - Index
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Departments";
$path_prefix = '../';

// Fetch departments with count of assigned doctors (LEFT JOIN + GROUP BY demo)
$stmt = $pdo->query("
    SELECT dep.*, COUNT(d.id) AS total_doctors
    FROM departments dep
    LEFT JOIN doctors d ON dep.id = d.department_id
    GROUP BY dep.id, dep.name, dep.description, dep.created_at
    ORDER BY dep.name ASC
");
$departments = $stmt->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-building-fill text-primary me-2"></i>Department Management</h3>
        <p class="text-muted small mb-0">Manage hospital medical departments</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-plus-lg me-1"></i> Add Department
    </a>
</div>

<div class="custom-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="input-group input-group-sm" style="max-width: 300px;">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="tableSearchInput" class="form-control" placeholder="Search departments...">
        </div>
        <small class="text-muted">Total: <strong><?php echo count($departments); ?></strong> Departments</small>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Dept ID</th>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th>Total Doctors</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($departments)): ?>
                    <tr><td colspan="5" class="text-center text-muted">No departments found.</td></tr>
                <?php else: ?>
                    <?php foreach ($departments as $dep): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border">DEP-<?php echo sprintf('%03d', $dep['id']); ?></span></td>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($dep['name']); ?></td>
                            <td><?php echo htmlspecialchars($dep['description'] ?: 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-2 py-1">
                                    <i class="bi bi-person-badge me-1"></i><?php echo $dep['total_doctors']; ?> Doctors
                                </span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $dep['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $dep['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure? Deleting a department will also affect assigned doctors.');">
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
