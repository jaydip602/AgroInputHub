<?php
// admin/product_returns.php - Admin Side Product Return & Verification Panel
require_once '../config/db_config.php';
session_start();

// એડમિન લૉગિન ચેક
if(!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$message = "";

// ⚡ રિટર્ન સ્ટેટસ અપડેટ કરવાની લોજિક (Approved / Rejected)
if(isset($_POST['update_status'])) {
    $return_id = intval($_POST['return_id']);
    $new_status = trim($_POST['return_status']);
    
    try {
        $update_stmt = $conn->prepare("UPDATE product_returns SET return_status = ? WHERE return_id = ?");
        if($update_stmt->execute([$new_status, $return_id])) {
            $message = "<div class='alert alert-success alert-dismissible fade show'>✨ Return request status updated successfully to <b>$new_status</b>!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    } catch(Exception $e) {
        $message = "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
    }
}

// તમામ રિટર્ન રિક્વેસ્ટ્સ ફાર્મર અને ઓર્ડરની વિગતો સાથે ફેચ કરો
$returns_list = [];
try {
    $query = "SELECT r.*, o.total_amount, o.payment_mode, f.farmer_name, f.phone_number, f.village 
              FROM product_returns r 
              JOIN orders o ON r.order_id = o.order_id 
              JOIN farmers f ON r.farmer_id = f.farmer_id 
              ORDER BY r.return_id DESC";
    $returns_list = $conn->query($query)->fetchAll();
} catch(Exception $e) {
    // જો ટેબલ હજુ ન બન્યું હોય તો એરર હેન્ડલિંગ
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Return Requests | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', sans-serif; padding-top: 100px; padding-bottom: 80px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .table-custom th { background-color: #0f1112 !important; color: #a0aec0 !important; border-color: #2d3238 !important; }
        .table-custom td { background-color: transparent !important; color: #ffffff !important; border-color: #2d3238 !important; vertical-align: middle; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-nav py-3 mb-4 shadow-sm fixed-top" style="background-color: #191c1f; border-bottom: 1px solid #2d3238;">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="admin_dashboard.php" class="premium-logo-container text-decoration-none">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-success text-white p-2 rounded-circle"><i class="fa-solid fa-wheat-awn"></i></div>
                    <div>
                        <div class="fw-bold text-white fs-5">Agro <span class="text-success">Admin</span> Hub</div>
                        <small class="text-muted" style="font-size: 11px;">Return Control Center</small>
                    </div>
                </div>
            </a>   
            <a href="admin_dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-4 fw-bold"><i class="fa-solid fa-house me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <?php if(!empty($message)) echo $message; ?>

        <div class="card card-custom p-4 shadow">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                <h4 class="fw-bold text-success m-0"><i class="fa-solid fa-rotate-left me-2"></i> Farmer Product Return & Verification Requests</h4>
                <span class="badge bg-secondary p-2 font-monospace"><?php echo count($returns_list); ?> Requests Found</span>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover table-bordered align-middle table-custom text-center mb-0">
                    <thead>
                        <tr>
                            <th>Return ID</th>
                            <th>Order ID</th>
                            <th>Farmer Details</th>
                            <th>Return Reason</th>
                            <th>Date</th>
                            <th>Current Status</th>
                            <th>Action / Verification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($returns_list)): ?>
                            <tr>
                                <td colspan="7" class="text-muted py-5">🎉 No product return requests submitted by farmers yet!</td>
                            </tr>
                        <?php else: foreach($returns_list as $ret): ?>
                            <tr>
                                <td class="fw-bold font-monospace text-warning">#RET-<?php echo $ret['return_id']; ?></td>
                                <td class="fw-bold font-monospace text-success">#ORD-0<?php echo $ret['order_id']; ?></td>
                                <td class="text-start">
                                    <strong class="text-white"><?php echo htmlspecialchars($ret['farmer_name']); ?></strong><br>
                                    <small class="text-muted font-monospace"><?php echo htmlspecialchars($ret['village']); ?> | <?php echo htmlspecialchars($ret['phone_number']); ?></small>
                                </td>
                                <td class="text-start text-muted small" style="max-width: 250px;">
                                    <?php echo htmlspecialchars($ret['reason']); ?>
                                </td>
                                <td class="small text-muted font-monospace">
                                    <?php echo date('d-m-Y H:i', strtotime($ret['created_at'])); ?>
                                </td>
                                <td>
                                    <?php 
                                    $status = $ret['return_status'];
                                    if($status == 'Pending') {
                                        echo "<span class='badge bg-warning text-dark fw-bold px-3 py-1.5'>Pending</span>";
                                    } elseif($status == 'Approved') {
                                        echo "<span class='badge bg-success fw-bold px-3 py-1.5'>Approved</span>";
                                    } else {
                                        echo "<span class='badge bg-danger fw-bold px-3 py-1.5'>Rejected</span>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <form action="" method="POST" class="d-flex gap-2 justify-content-center">
                                        <input type="hidden" name="return_id" value="<?php echo $ret['return_id']; ?>">
                                        <select name="return_status" class="form-select form-select-sm bg-black text-white border-secondary fw-bold" style="width: 120px;">
                                            <option value="Pending" <?php if($status=='Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Approved" <?php if($status=='Approved') echo 'selected'; ?>>Approve</option>
                                            <option value="Rejected" <?php if($status=='Rejected') echo 'selected'; ?>>Reject</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-success fw-bold px-3">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>