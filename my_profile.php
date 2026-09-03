<?php
// staff/my_profile.php - Staff Self Profile Submission to Pending Queue
require_once __DIR__ . '/../config/db_config.php';
session_start();

$staff_id = $_SESSION['staff_id'] ?? 1; // ડેમો સેશન સ્ટાફ ID
$msg = "";
$error = "";

if (isset($_POST['save_staff_profile_btn'])) {
    $name_en    = trim($_POST['staff_name_en']);
    $name_gu    = trim($_POST['staff_name_gu']);
    $desig_en   = trim($_POST['designation_en']);
    $desig_gu   = trim($_POST['designation_gu']);
    $found_en   = trim($_POST['foundation_en']);
    $found_gu   = trim($_POST['foundation_gu']);
    $exp        = intval($_POST['experience_years']);
    $best_en    = trim($_POST['best_in_en']);
    $best_gu    = trim($_POST['best_in_gu']);
    $phone      = trim($_POST['phone_number']);
    $whatsapp   = trim($_POST['whatsapp_number']);
    $bio_en     = trim($_POST['bio_en']);
    $bio_gu     = trim($_POST['bio_gu']);

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
    $msg = "✅ Profile updated! Sent for Admin Verification. (એડમિન મંજૂર કરશે પછી ખેડૂત સાઇડ લાઈવ દેખાશે)";
}

$staff = $conn->query("SELECT * FROM agro_staff WHERE staff_id = $staff_id")->fetch();
?>
<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>Staff Profile | AgroInputHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #080f0c; color: #ffffff; font-family: 'Segoe UI', system-ui, sans-serif; padding-top: 30px; }
        .card-custom { background: #111e17; border: 1px solid #1c3826; border-radius: 16px; }
        .form-control { background: #0b1710 !important; border: 1px solid #1c3826 !important; color: #fff !important; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container" style="max-width: 850px;">
    <div class="card card-custom p-4 shadow-lg text-start">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-success mb-0"><i class="fa-solid fa-user-pen me-2"></i>સ્ટાફ પ્રોફાઇલ અને નિષ્ણાત વિગતો</h4>
            <span class="badge <?php echo ($staff['approval_status'] === 'Approved') ? 'bg-success' : (($staff['approval_status'] === 'Pending') ? 'bg-warning text-dark' : 'bg-danger'); ?> px-3 py-2">
                Approval: <?php echo $staff['approval_status'] ?? 'Pending'; ?>
            </span>
        </div>

        <?php if($msg): ?><div class="alert alert-info py-2 small"><?php echo $msg; ?></div><?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($staff['profile_image'] ?? ''); ?>">

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="small text-success fw-bold">Full Name (English)</label>
                    <input type="text" name="staff_name_en" class="form-control" value="<?php echo htmlspecialchars($staff['staff_name_en'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="small text-success fw-bold">પૂરું નામ (ગુજરાતી)</label>
                    <input type="text" name="staff_name_gu" class="form-control" value="<?php echo htmlspecialchars($staff['staff_name_gu'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="small text-success fw-bold">Designation (English)</label>
                    <input type="text" name="designation_en" class="form-control" value="<?php echo htmlspecialchars($staff['designation_en'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="small text-success fw-bold">હોદ્દો (ગુજરાતી)</label>
                    <input type="text" name="designation_gu" class="form-control" value="<?php echo htmlspecialchars($staff['designation_gu'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="small text-success fw-bold">Foundation / Degree (English)</label>
                    <input type="text" name="foundation_en" class="form-control" value="<?php echo htmlspecialchars($staff['foundation_en'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="small text-success fw-bold">ફાઉન્ડેશન / ડિગ્રી (ગુજરાતી)</label>
                    <input type="text" name="foundation_gu" class="form-control" value="<?php echo htmlspecialchars($staff['foundation_gu'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="small text-warning fw-bold">Best In / Specialization (English - Comma Separated)</label>
                    <input type="text" name="best_in_en" class="form-control" value="<?php echo htmlspecialchars($staff['best_in_en'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="small text-warning fw-bold">શેમાં નિષ્ણાત છે (ગુજરાતી - અલ્પવિરામ સાથે)</label>
                    <input type="text" name="best_in_gu" class="form-control" value="<?php echo htmlspecialchars($staff['best_in_gu'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="small text-white">Experience (Years)</label>
                    <input type="number" name="experience_years" class="form-control" value="<?php echo $staff['experience_years'] ?? 5; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="small text-white">Mobile Number</label>
                    <input type="text" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($staff['phone_number'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="small text-white">WhatsApp (e.g. 919876543210)</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="<?php echo htmlspecialchars($staff['whatsapp_number'] ?? ''); ?>" required>
                </div>
                <div class="col-md-12">
                    <label class="small text-white">Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control">
                </div>
            </div>

            <button type="submit" name="save_staff_profile_btn" class="btn btn-success fw-bold text-dark w-100 py-2.5 rounded-pill shadow-sm">
                Save & Submit for Verification (વેરિફિકેશન માટે મોકલો)
            </button>
        </form>
    </div>
</div>
</body>
</html>