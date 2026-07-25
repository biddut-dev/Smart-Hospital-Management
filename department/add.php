<?php
/**
 * Add Department Form & Processing
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Add Department";
$path_prefix = '../';

$name = $description = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($name)) {
        $error = "Department name is required.";
    } else {
        // Check duplicate name
        $stmt_check = $pdo->prepare("SELECT id FROM departments WHERE name = :name");
        $stmt_check->execute(['name' => $name]);
        if ($stmt_check->fetch()) {
            $error = "A department with this name already exists.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO departments (name, description) VALUES (:name, :description)");
            if ($stmt->execute(['name' => $name, 'description' => $description])) {
                set_flash('success', "Department '{$name}' added successfully.");
                header("Location: index.php");
                exit;
            } else {
                $error = "Failed to add department. Please try again.";
            }
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-building-add text-primary me-2"></i>Add New Department</h4>
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
                <div class="mb-3">
                    <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" placeholder="e.g. Cardiology" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Brief description of services provided..."><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Save Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
