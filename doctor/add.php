<?php
/**
 * Add Doctor Form & Processing
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Add Doctor";
$path_prefix = '../';

// Fetch departments for FK dropdown
$departments = $pdo->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

$name = $department_id = $phone = $email = $available_days = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $department_id = intval($_POST['department_id'] ?? 0);
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $available_days = trim($_POST['available_days'] ?? '');

    if (empty($name) || $department_id <= 0 || empty($phone) || empty($email) || empty($available_days)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO doctors (department_id, name, phone, email, available_days)
            VALUES (:department_id, :name, :phone, :email, :available_days)
        ");
        if ($stmt->execute([
            'department_id' => $department_id,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'available_days' => $available_days
        ])) {
            set_flash('success', "Doctor '{$name}' added successfully.");
            header("Location: index.php");
            exit;
        } else {
            $error = "Failed to add doctor.";
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-person-plus text-primary me-2"></i>Add New Doctor</h4>
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
                        <label class="form-label fw-semibold">Doctor Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="e.g. Dr. Robert Chen" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                        <select class="form-select" name="department_id" required>
                            <option value="">-- Select Department --</option>
                            <?php foreach ($departments as $dep): ?>
                                <option value="<?php echo $dep['id']; ?>" <?php echo ($department_id == $dep['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dep['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="phone" placeholder="+1 (555) 000-0000" value="<?php echo htmlspecialchars($phone); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" placeholder="doctor@hospital.org" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Available Days & Hours <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="available_days" placeholder="e.g. Mon, Wed, Fri (9 AM - 2 PM)" value="<?php echo htmlspecialchars($available_days); ?>" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Save Doctor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
