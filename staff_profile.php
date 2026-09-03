<?php
// admin/staff_profile.php - Staff Member Profile & Foundation Management
require_once '../config/db_config.php';
require_once 'auth.php';
require_once '../includes/lang.php';

$staff_id = $_SESSION['staff_id'] ?? ($_SESSION['admin_id'] ?? 1);
$msg = "";

if (isset($_POST['update_staff_profile_btn'])) {
    $name_en    = trim($_POST['staff_name_en']);
    $desig_en   = trim($_POST['designation_en']);
    $found_en   = trim($_POST['foundation_en']);
    $exp        = intval($_POST['experience_years']);
    $best_en    = trim($_POST['best_in_en']);
    $phone      = trim($_POST['phone_number']);
    $whatsapp   = trim($_POST['whatsapp_number']);
    $bio_en     = trim($_POST['bio_en']);

    // ડેટાબેઝ સુસંગતતા માટે ગુજરાતી ફીલ્ડ્સમાં પણ અંગ્રેજી ડેટા સેવ થશે
    $name_gu    = $name_en;
    $desig_gu   = $desig_en;
    $found_gu   = $found_en;
    $best_gu    = $best_en;
    $bio_gu     = $bio_en;

    $img_name = $_POST['old_image'] ?? 'default_user.jpg';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $target_dir = __DIR__ . "/../uploads/";
            if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
            $img_name = "staff_" . $staff_id . "_" . time() . "." . $ext;
            move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_dir . $img_name);
        }
    }

    $stmt = $conn->prepare("UPDATE agro_staff SET 
        staff_name_en=?, staff_name_gu=?, designation_en=?, designation_gu=?, 
        foundation_en=?, foundation_gu=?, experience_years=?, best_in_en=?, best_in_gu=?, 
        phone_number=?, whatsapp_number=?, profile_image=?, bio_en=?, bio_gu=?, 
        approval_status='Pending' WHERE staff_id=?");
    
    $stmt->execute([$name_en, $name_gu, $desig_en, $desig_gu, $found_en, $found_gu, $exp, $best_en, $best_gu, $phone, $whatsapp, $img_name, $bio_en, $bio_gu, $staff_id]);
    $msg = "Profile updated successfully and submitted for Admin verification!";
}

// સ્ટાફ ડેટા ફેચ કરવો
$staff_stmt = $conn->prepare("SELECT * FROM agro_staff WHERE staff_id = ? LIMIT 1");
$staff_stmt->execute([$staff_id]);
$staff = $staff_stmt->fetch();

$img_src = (!empty($staff['profile_image']) && filter_var($staff['profile_image'], FILTER_VALIDATE_URL)) 
           ? $staff['profile_image'] 
           : "../uploads/" . ($staff['profile_image'] ?? 'default_user.jpg');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Staff Profile | AgroInputHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #080f0c; color: #ffffff; font-family: 'Segoe UI', system-ui, sans-serif; padding-top: 85px; padding-bottom: 60px; }
        .card-custom { background: #111e17 !important; border: 1px solid #1c3826 !important; border-radius: 16px; }
        .form-control { background: #0b1710 !important; border: 1px solid #1c3826 !important; color: #fff !important; border-radius: 10px; }
        .form-control:focus { border-color: #2ecc71 !important; box-shadow: 0 0 10px rgba(46, 204, 113, 0.3) !important; color: #fff !important; }
        .form-label-custom { color: #2ecc71 !important; font-size: 13px !important; font-weight: 600 !important; margin-bottom: 5px; display: block; }
    </style>
</head>
<body>

    <!-- ટોપ નેવિગેશન બાર -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-3" style="background: rgba(10, 20, 15, 0.98); border-bottom: 1px solid #1c3826;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="navbar-brand fw-bold text-success fs-5">
                <i class="fa-solid fa-seedling me-1"></i> AgroInputHub
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="admin_dashboard.php" class="btn btn-sm btn-outline-secondary text-light rounded-pill px-3">
                    <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
                </a>
                <a href="../farmerside/staff_experts.php" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
                    <i class="fa-solid fa-eye me-1"></i> View Live on Site
                </a>
            </div>
        </div>
    </nav>

    <div class="container text-start" style="max-width: 800px;">
        <?php if($msg): ?>
            <div class="alert alert-success py-2.5 small mb-3">
                <i class="fa-solid fa-circle-check me-1"></i> <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <div class="card card-custom p-4 shadow-lg">
            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-3 mb-4 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <img src="<?php echo htmlspecialchars($img_src); ?>" width="70" height="70" class="rounded-circle border border-2 border-success" style="object-fit:cover;">
                    <div>
                        <h4 class="fw-bold text-white mb-0"><?php echo htmlspecialchars($staff['staff_name_en'] ?? 'Staff Member'); ?></h4>
                        <div class="text-success small fw-bold"><?php echo htmlspecialchars($staff['designation_en'] ?? 'Agronomist'); ?></div>
                    </div>
                </div>
                <div>
                    <span class="badge <?php echo (($staff['approval_status'] ?? '') === 'Approved') ? 'bg-success' : ((($staff['approval_status'] ?? '') === 'Pending') ? 'bg-warning text-dark' : 'bg-danger'); ?> px-3 py-2 fs-6">
                        Approval Status: <?php echo $staff['approval_status'] ?? 'Pending'; ?>
                    </span>
                </div>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($staff['profile_image'] ?? ''); ?>">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Full Name</label>
                        <input type="text" name="staff_name_en" class="form-control" value="<?php echo htmlspecialchars($staff['staff_name_en'] ?? ''); ?>" placeholder="e.g. Dr. Rajesh Patel" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Designation / Role</label>
                        <input type="text" name="designation_en" class="form-control" value="<?php echo htmlspecialchars($staff['designation_en'] ?? ''); ?>" placeholder="e.g. Chief Agronomist" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Foundation / Academic Degree</label>
                        <input type="text" name="foundation_en" class="form-control" value="<?php echo htmlspecialchars($staff['foundation_en'] ?? ''); ?>" placeholder="e.g. M.Sc. Agri - Junagadh Agricultural University" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Experience (Years)</label>
                        <input type="number" name="experience_years" class="form-control" value="<?php echo $staff['experience_years'] ?? 5; ?>" min="1" max="50" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom text-warning">Core Specialization / Best In (Comma Separated)</label>
                    <input type="text" name="best_in_en" class="form-control" value="<?php echo htmlspecialchars($staff['best_in_en'] ?? ''); ?>" placeholder="e.g. Cotton Pest Control, Soil Health, Drip Fertigation" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Calling Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($staff['phone_number'] ?? ''); ?>" placeholder="10 Digit Mobile Number" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" class="form-control" value="<?php echo htmlspecialchars($staff['whatsapp_number'] ?? ''); ?>" placeholder="e.g. 919876543210" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                </div>

                <div class="mb-4">
                    <label class="form-label-custom">Brief Biography / Overview</label>
                    <textarea name="bio_en" class="form-control" rows="3" placeholder="Describe your field experience and advisory specialty..."><?php echo htmlspecialchars($staff['bio_en'] ?? ''); ?></textarea>
                </div>

                <button type="submit" name="update_staff_profile_btn" class="btn btn-success fw-bold text-dark w-100 py-3 rounded-pill shadow-lg fs-6">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Save & Submit for Admin Verification
                </button>
            </form>
        </div>
    </div>
</body>
</html>