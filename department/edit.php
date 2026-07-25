<?php
/**
 * Edit Department
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Edit Department";
$path_prefix = '../';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM departments WHERE id = :id");
$stmt->execute(['id' => $id]);
$dep = $stmt->fetch();

if (!$dep) {
    set_flash('error', "Department not found.");
    header("Location: index.php");
    exit;
}

$name = $dep['name'];
$description = $dep['description'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($name)) {
        $error = "Department name is required.";
    } else {
        // Check duplicate name on other record
        $stmt_check = $pdo->prepare("SELECT id FROM departments WHERE name = :name AND id != :id");
        $stmt_check->execute(['name' => $name, 'id' => $id]);
        if ($stmt_check->fetch()) {
            $error = "Another department with this name already exists.";
        } else {
            $stmt_update = $pdo->prepare("UPDATE departments SET name = :name, description = :description WHERE id = :id");
            if ($stmt_update->execute(['name' => $name, 'description' => $description, 'id' => $id])) {
                set_flash('success', "Department updated successfully.");
                header("Location: index.php");
                exit;
            } else {
                $error = "Failed to update department.";
            }
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Department</h4>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>

        <div class="custom-card p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit.php?id=<?php echo $id; ?>">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Update Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
