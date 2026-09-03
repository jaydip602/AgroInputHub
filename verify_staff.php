<?php
// admin/verify_staff.php - Staff Verification Panel
require_once __DIR__ . '/../config/db_config.php';
session_start();

$msg = "";

// ૧. Approve અથવા Reject એક્શન
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = ($_GET['action'] === 'approve') ? 'Approved' : 'Rejected';
    
    $upd = $conn->prepare("UPDATE agro_staff SET approval_status = ? WHERE staff_id = ?");
    $upd->execute([$action, $id]);
    header("Location: verify_staff.php?msg=" . strtolower($action));
    exit;
}

// ૨. તમામ સ્ટાફ રેકોર્ડ્સ મેળવવા (Pending સૌથી ઉપર દેખાશે)
$staffs = $conn->query("SELECT * FROM agro_staff ORDER BY (approval_status = 'Pending') DESC, staff_id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Verification | AgroInputHub Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #080f0c; color: #ffffff; font-family: 'Segoe UI', system-ui, sans-serif; padding-top: 85px; padding-bottom: 60px; }
        .card-custom { background: #111e17; border: 1px solid #1c3826; border-radius: 14px; }
        .table-dark-custom th { background: #0f291a !important; color: #2ecc71; border-bottom: 1px solid #1c3826; }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-3" style="background: rgba(10, 20, 15, 0.98); border-bottom: 1px solid #1c3826;">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="btn btn-outline-secondary text-light rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            <span class="fw-bold text-success fs-5">Staff Verification Panel</span>
            <a href="../farmerside/staff_experts.php" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">View Live Site</a>
        </div>
    </nav>

<div class="container-fluid px-4 text-start">

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success py-2.5 small mb-3">
            <i class="fa-solid fa-circle-check me-1"></i> Status successfully updated to: <b><?php echo ucfirst($_GET['msg']); ?></b>
        </div>
    <?php endif; ?>

    <div class="card card-custom p-3 shadow-sm">
        <div class="table-responsive">
            <table class="table table-dark table-hover table-dark-custom align-middle mb-0 text-center">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Foundation / Degree</th>
                        <th>Best In</th>
                        <th>Submitted By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($staffs)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">No staff records found in database.</td></tr>
                    <?php endif; ?>

                    <?php foreach($staffs as $s): 
                        $img = (filter_var($s['profile_image'], FILTER_VALIDATE_URL)) ? $s['profile_image'] : '../uploads/'.$s['profile_image'];
                    ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($img); ?>" width="50" height="50" class="rounded-circle border border-success" style="object-fit:cover;"></td>
                        <td class="fw-bold text-white text-start"><?php echo htmlspecialchars($s['staff_name_en']); ?></td>
                        <td><?php echo htmlspecialchars($s['designation_en']); ?></td>
                        <td class="text-start small"><?php echo htmlspecialchars($s['foundation_en']); ?></td>
                        <td class="text-start"><span class="badge bg-dark border border-warning text-warning"><?php echo htmlspecialchars($s['best_in_en']); ?></span></td>
                        <td><span class="badge bg-secondary"><?php echo $s['added_by']; ?></span></td>
                        <td>
                            <span class="badge <?php echo ($s['approval_status'] === 'Approved') ? 'bg-success' : (($s['approval_status'] === 'Pending') ? 'bg-warning text-dark' : 'bg-danger'); ?> px-3 py-2">
                                <?php echo $s['approval_status']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <?php if($s['approval_status'] !== 'Approved'): ?>
                                    <a href="verify_staff.php?action=approve&id=<?php echo $s['staff_id']; ?>" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">
                                        <i class="fa-solid fa-check me-1"></i> Approve
                                    </a>
                                <?php endif; ?>
                                
                                <?php if($s['approval_status'] !== 'Rejected'): ?>
                                    <a href="verify_staff.php?action=reject&id=<?php echo $s['staff_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fa-solid fa-xmark me-1"></i> Reject
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>