<?php
/**
 * Medicine Inventory Management - Index & Search
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Medicines";
$path_prefix = '../';

$search = trim($_GET['search'] ?? '');
if (!empty($search)) {
    $stmt = $pdo->prepare("
        SELECT * FROM medicines 
        WHERE name LIKE :search OR company LIKE :search 
        ORDER BY name ASC
    ");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM medicines ORDER BY name ASC");
}
$medicines = $stmt->fetchAll();

include_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-slate-800 mb-0"><i class="bi bi-capsule text-primary me-2"></i>Medicine Management</h3>
        <p class="text-muted small mb-0">Track pharmacy inventory, prices, and stock counts</p>
    </div>
    <a href="add.php" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-plus-circle me-1"></i> Add New Medicine
    </a>
</div>

<div class="custom-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" action="index.php" class="d-flex gap-2" style="max-width: 400px; flex-grow: 1;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" id="tableSearchInput" class="form-control" placeholder="Search medicine name or manufacturer..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <?php if (!empty($search)): ?>
                <a href="index.php" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </form>
        <small class="text-muted">Total Products: <strong><?php echo count($medicines); ?></strong></small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Med ID</th>
                    <th>Medicine Name</th>
                    <th>Company / Manufacturer</th>
                    <th>Price / Unit</th>
                    <th>Stock Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($medicines)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No medicines found in inventory.</td></tr>
                <?php else: ?>
                    <?php foreach ($medicines as $med): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark border">MED-<?php echo sprintf('%03d', $med['id']); ?></span></td>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($med['name']); ?></td>
                            <td><?php echo htmlspecialchars($med['company']); ?></td>
                            <td class="fw-bold text-slate-800">$<?php echo number_format($med['price'], 2); ?></td>
                            <td>
                                <?php if ($med['stock_quantity'] <= 10): ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Low Stock (<?php echo $med['stock_quantity']; ?> left)
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <?php echo $med['stock_quantity']; ?> Units
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $med['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit / Restock">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $med['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete medicine?');">
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
