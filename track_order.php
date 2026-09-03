<?php
// farmerside/track_order.php - Fully Multi-Language Supported Tracking Page with Cancel Option
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/flang.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.php");
    exit;
}

$farmer_id = $_SESSION['farmer_id'];
$order_id = $_GET['order_id'] ?? null;
$msg = "";

try {
    // 🛑 જો ઓર્ડર પેન્ડિંગ હોય અને ખેડૂતે કેન્સલ કરવાની વિનંતી મોકલી હોય
    if (isset($_GET['action']) && $_GET['action'] === 'cancel' && $order_id) {
        $check_stmt = $conn->prepare("SELECT order_status FROM orders WHERE order_id = ? AND farmer_id = ?");
        $check_stmt->execute([$order_id, $farmer_id]);
        $ord_chk = $check_stmt->fetch();

        if ($ord_chk && $ord_chk['order_status'] === 'Pending') {
            $upd_cancel = $conn->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ? AND farmer_id = ?");
            $upd_cancel->execute([$order_id, $farmer_id]);
            header("Location: track_order.php?order_id=" . $order_id . "&msg=cancelled");
            exit;
        }
    }

    if ($order_id) {
        $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND farmer_id = ?");
        $stmt->execute([$order_id, $farmer_id]);
        $single_order = $stmt->fetch();
    }

    $stmt_all = $conn->prepare("SELECT order_id, product_name, order_status, order_date FROM orders WHERE farmer_id = ? ORDER BY order_id DESC");
    $stmt_all->execute([$farmer_id]);
    $all_orders = $stmt_all->fetchAll();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order | Agro Input Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../assets/css/dynamic_bg.css">
    <script src="../assets/js/dynamic_bg.js"></script>
    
    <style>
        body { background-color: #0d0f11; color: #e2e8f0; font-family: 'Segoe UI', system-ui, sans-serif; padding-bottom: 80px; padding-top: 100px; }
        .card-custom { background: #191c1f !important; border: 1px solid #2d3238 !important; border-radius: 15px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important; color: white; }
        
        .tracking-timeline { position: relative; padding-left: 30px; margin: 20px 0; }
        .tracking-timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 3px; background: #2d3238; z-index: 1; }

        .timeline-step { position: relative; margin-bottom: 30px; z-index: 2; }
        .timeline-step::before { content: ''; position: absolute; left: -24px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #2d3238; border: 2px solid #a0aec0; z-index: 3; }
        
        .timeline-step.completed::before { background: #28a745; border-color: #28a745; box-shadow: 0 0 10px rgba(40,167,69,0.5); }
        .timeline-step.active::before { background: #ffc107; border-color: #ffc107; box-shadow: 0 0 10px rgba(255,193,7,0.5); }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark market-nav mb-4 fixed-top">
        <div class="container">
            <a href="dashboard.php" class="premium-logo-container text-decoration-none">
                <div class="premium-logo-icon-wrapper text-success fs-3"><i class="fa-solid fa-wheat-awn"></i></div>
                <div class="premium-logo-text-wrapper">
                    <div class="logo-main-text text-white fw-bold">Agro <span>Input</span> Hub</div>
                    <div class="logo-sub-text text-muted small">Digital Kisan Portal</div>
                </div>
            </a>   
            
            <div class="d-flex align-items-center gap-2">
                <a href="dashboard.php" class="btn btn-sm btn-outline-success rounded-pill px-3 text-white fw-bold"><i class="fa-solid fa-house me-1"></i> Home</a>
                <a href="farmer_logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3 text-white fw-bold"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] === 'cancelled'): ?>
            <div class="alert alert-danger py-2 mb-3 text-center fw-bold">
                <i class="fa-solid fa-circle-exclamation me-1"></i> Your order has been successfully cancelled.
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Left Side: Order List -->
            <div class="col-md-4">
                <div class="card card-custom p-3 shadow-sm">
                    <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-list-check me-2"></i> <?php echo __('select_order'); ?></h5>
                    <div class="list-group bg-transparent">
                        <?php if(empty($all_orders)): ?>
                            <p class="text-muted small"><?php echo __('no_orders'); ?></p>
                        <?php else: foreach($all_orders as $ao): ?>
                            <a href="track_order.php?order_id=<?php echo $ao['order_id']; ?>" class="list-group-item list-group-item-action bg-dark text-white border-secondary mb-2 rounded <?php echo ($order_id == $ao['order_id']) ? 'border-success bg-opacity-75' : ''; ?>">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold font-monospace text-success">#ORD-0<?php echo $ao['order_id']; ?></span>
                                    <span class="badge bg-secondary small"><?php echo $ao['order_status']; ?></span>
                                </div>
                                <div class="small text-truncate mt-1 text-muted"><?php echo htmlspecialchars($ao['product_name']); ?></div>
                            </a>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Side: Tracking Details -->
            <div class="col-md-8">
                <div class="card card-custom p-4 shadow-sm">
                    <?php if (!$order_id || !isset($single_order)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-truck-fast fs-1 mb-3 text-success"></i>
                            <h5><?php echo __('select_prompt'); ?></h5>
                        </div>
                    <?php else: ?>
                        <?php 
                            $status = $single_order['order_status'];
                            $order_date = $single_order['order_date'];
                            $estimated_delivery = date('d-m-Y', strtotime($order_date . ' + 3 days'));

                            $progress_height = '0%';
                            if ($status == 'Pending') { $progress_height = '20%'; }
                            elseif ($status == 'Approved') { $progress_height = '60%'; }
                            elseif ($status == 'Delivered') { $progress_height = '100%'; }
                            elseif ($status == 'Cancelled') { $progress_height = '0%'; }
                        ?>
                        <div class="border-bottom border-secondary pb-3 mb-4 d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h4 class="fw-bold text-white"><i class="fa-solid fa-location-crosshairs text-warning me-2"></i> <?php echo __('order_tracking_title'); ?>: <span class="font-monospace text-success">#ORD-0<?php echo $single_order['order_id']; ?></span></h4>
                                <p class="text-muted small mb-1"><?php echo __('product'); ?>: <b class="text-white"><?php echo htmlspecialchars($single_order['product_name']); ?></b></p>
                                <p class="text-muted small mb-0"><?php echo __('order_date'); ?>: <b class="font-monospace text-white"><?php echo date('d-m-Y H:i', strtotime($order_date)); ?></b> | <?php echo __('est_delivery'); ?>: <b class="font-monospace text-warning"><?php echo $estimated_delivery; ?></b></p>
                            </div>

                            <!-- 🛑 Cancel / Delete Option (Only when Pending) -->
                            <?php if ($status === 'Pending'): ?>
                                <div>
                                    <a href="track_order.php?order_id=<?php echo $single_order['order_id']; ?>&action=cancel" 
                                       class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold shadow-sm"
                                       onclick="return confirm('Are you sure you want to cancel this pending order?');">
                                        <i class="fa-solid fa-trash-can me-1"></i> Cancel Order
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h6 class="fw-bold text-success mb-3"><?php echo __('live_timeline'); ?>:</h6>
                        <div class="tracking-timeline" style="position: relative;">
                            <div style="position: absolute; left: 10px; top: 0; width: 3px; height: <?php echo $progress_height; ?>; background: #28a745; z-index: 1; transition: height 0.5s ease;"></div>

                            <!-- Step 1 -->
                            <div class="timeline-step completed">
                                <h6 class="fw-bold text-white mb-1"><?php echo __('step_1'); ?></h6>
                                <p class="text-muted small mb-0"><?php echo __('desc_1'); ?></p>
                            </div>

                            <!-- Step 2 -->
                            <div class="timeline-step <?php echo ($status == 'Approved' || $status == 'Delivered') ? 'completed' : (($status == 'Pending') ? 'active' : ''); ?>">
                                <h6 class="fw-bold text-white mb-1"><?php echo __('step_2'); ?></h6>
                                <p class="text-muted small mb-0">
                                    <?php if($status == 'Pending'): ?>
                                        <span class="text-warning"><i class="fa-solid fa-spinner fa-spin me-1"></i> <?php echo __('desc_2_pending'); ?></span>
                                    <?php else: ?>
                                        <?php echo __('desc_2_approved'); ?>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <!-- Step 3 -->
                            <div class="timeline-step <?php echo ($status == 'Delivered') ? 'completed' : (($status == 'Approved') ? 'active' : ''); ?>">
                                <h6 class="fw-bold text-white mb-1"><?php echo __('step_3'); ?></h6>
                                <p class="text-muted small mb-0"><?php echo __('desc_3'); ?></p>
                            </div>

                            <!-- Step 4 -->
                            <div class="timeline-step <?php echo ($status == 'Delivered') ? 'completed' : ''; ?>">
                                <h6 class="fw-bold text-white mb-1"><?php echo __('step_4'); ?></h6>
                                <p class="text-muted small mb-0">
                                    <?php echo ($status == 'Delivered') ? '<span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> '.__('desc_4_delivered').'</span>' : __('desc_4_pending'); ?>
                                </p>
                            </div>
                        </div>

                        <?php if($status == 'Cancelled'): ?>
                            <div class="alert alert-danger mt-3 fw-bold text-center">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo __('cancelled_alert'); ?>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom text-center py-3 bg-dark border-top border-secondary text-muted" style="font-size: 13px;">
        &copy; 2026 Agro Input Hub | Kisan Portal | <b>Developed by: Jaydip Parmar & Pruthviraj Jadeja</b>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>