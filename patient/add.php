<?php
/**
 * Add / Register Patient
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$page_title = "Register Patient";
$path_prefix = '../';

$name = $gender = $age = $phone = $address = $blood_group = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $gender = trim($_POST['gender'] ?? 'Male');
    $age = intval($_POST['age'] ?? 0);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $blood_group = trim($_POST['blood_group'] ?? 'O+');

    if (empty($name) || empty($phone) || $age <= 0 || empty($address)) {
        $error = "Please fill in all required fields properly.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO patients (name, gender, age, phone, address, blood_group)
            VALUES (:name, :gender, :age, :phone, :address, :blood_group)
        ");
        if ($stmt->execute([
            'name' => $name,
            'gender' => $gender,
            'age' => $age,
            'phone' => $phone,
            'address' => $address,
            'blood_group' => $blood_group
        ])) {
            set_flash('success', "Patient '{$name}' registered successfully.");
            header("Location: index.php");
            exit;
        } else {
            $error = "Failed to register patient.";
        }
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-person-plus text-primary me-2"></i>Register New Patient</h4>
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
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Patient Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="e.g. Jane Doe" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Blood Group <span class="text-danger">*</span></label>
                        <select class="form-select" name="blood_group" required>
                            <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg): ?>
                                <option value="<?php echo $bg; ?>" <?php echo ($blood_group == $bg) ? 'selected' : ''; ?>><?php echo $bg; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Age <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="age" min="1" max="120" value="<?php echo $age ?: ''; ?>" placeholder="30" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="phone" placeholder="+1 (555) 000-0000" value="<?php echo htmlspecialchars($phone); ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Residential Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Full street address..." required><?php echo htmlspecialchars($address); ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle me-1"></i> Register Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
