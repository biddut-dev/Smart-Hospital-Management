<?php
/**
 * Add Medicine
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Add Medicine";
$path_prefix = '../';

$name = $company = $price = $stock_quantity = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $price = floatval($_POST['price'] ?? 0.00);
    $stock_quantity = intval($_POST['stock_quantity'] ?? 0);

    if (empty($name) || empty($company) || $price <= 0 || $stock_quantity < 0) {
        $error = "Please fill in all fields with valid information.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO medicines (name, company, price, stock_quantity)
            VALUES (:name, :company, :price, :stock_quantity)
        ");
        if ($stmt->execute([
            'name' => $name,
            'company' => $company,
            'price' => $price,
            'stock_quantity' => $stock_quantity
        ])) {
            set_flash('success', "Medicine '{$name}' added to inventory.");
            header("Location: index.php");
            exit;
        } else {
            $error = "Failed to add medicine.";
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-capsule-plus text-primary me-2"></i>Add Medicine to Inventory</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>

        <div class="custom-card p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="add.php">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Medicine Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="e.g. Paracetamol 650mg" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Company / Brand <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="company" placeholder="e.g. GlaxoSmithKline" value="<?php echo htmlspecialchars($company); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Unit Price ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="price" placeholder="15.00" value="<?php echo $price ?: ''; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="stock_quantity" min="0" placeholder="100" value="<?php echo $stock_quantity ?: ''; ?>" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Save Medicine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
